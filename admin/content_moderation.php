<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Ensure admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../admin_login.php');
    exit;
}

// Handle resource moderation (approve/delete)
if (isset($_POST['action']) && isset($_POST['resource_id'])) {
    $resource_id = $_POST['resource_id'];
    $action = $_POST['action'];

    if ($action === 'approve') {
        $stmt = $conn->prepare("UPDATE resources SET category = CONCAT(category, ' (Approved)') WHERE resource_id = ?");
        $stmt->bind_param("i", $resource_id);
        $stmt->execute();
        $message = "Resource approved successfully!";
    } elseif ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM resources WHERE resource_id = ?");
        $stmt->bind_param("i", $resource_id);
        $stmt->execute();
        $message = "Resource deleted successfully!";
    }
}

// Handle collaboration moderation (delete only)
if (isset($_POST['delete_collab'])) {
    $collab_id = $_POST['collab_id'];
    $stmt = $conn->prepare("DELETE FROM collaboration WHERE post_id = ?");
    $stmt->bind_param("i", $collab_id);
    $stmt->execute();
    $message = "Collaboration post deleted successfully!";
}

// Fetch unapproved resources
$resources = $conn->query("SELECT * FROM resources ORDER BY upload_date DESC");

// Fetch all collaboration posts
$collabs = $conn->query("
    SELECT c.*, 
           CASE WHEN c.author_type='faculty' THEN f.name ELSE s.name END AS author_name
    FROM collaboration c
    LEFT JOIN faculty f ON c.author_type='faculty' AND c.author_id=f.faculty_id
    LEFT JOIN students s ON c.author_type='student' AND c.author_id=s.student_id
    ORDER BY c.created_at DESC
");
?>

<main class="dashboard-main">
    <h2 style="text-align:center;">Content Moderation Panel</h2>

    <?php if(isset($message)): ?>
        <p class="success" style="text-align:center;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Resource Moderation -->
    <section class="moderation-section">
        <h3>📚 Uploaded Resources</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Category</th>
                <th>Uploaded By</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            <?php while($r = $resources->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($r['title']) ?></td>
                <td><?= htmlspecialchars($r['description']) ?></td>
                <td><?= htmlspecialchars($r['category']) ?></td>
                <td><?= htmlspecialchars($r['uploaded_by']) ?></td>
                <td><?= htmlspecialchars($r['upload_date']) ?></td>
                <td>
                    <form method="POST" style="display:flex;gap:5px;">
                        <input type="hidden" name="resource_id" value="<?= $r['resource_id'] ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                        <button type="submit" name="action" value="delete" class="btn btn-danger" onclick="return confirm('Delete this resource?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Collaboration Moderation -->
    <section class="moderation-section">
        <h3>📝 Collaboration Posts</h3>
        <table>
            <tr>
                <th>Author</th>
                <th>Type</th>
                <th>Title</th>
                <th>Content</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while($c = $collabs->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($c['author_name']) ?></td>
                <td><?= htmlspecialchars(ucfirst($c['author_type'])) ?></td>
                <td><?= htmlspecialchars($c['title']) ?></td>
                <td><?= htmlspecialchars($c['content']) ?></td>
                <td><?= htmlspecialchars($c['created_at']) ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Delete this collaboration post?')">
                        <input type="hidden" name="collab_id" value="<?= $c['post_id'] ?>">
                        <button type="submit" name="delete_collab" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
