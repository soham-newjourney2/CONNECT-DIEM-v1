<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../student_login.php');
    exit;
}
require_once '../includes/db.php';

// Fetch resources from DB
$sql = "SELECT r.title, r.description, r.file_path, r.category, f.name AS faculty_name
        FROM resources r
        LEFT JOIN faculty f ON r.faculty_id = f.faculty_id
        ORDER BY r.upload_date DESC";
$result = $conn->query($sql);

$resources = [];
while ($row = $result->fetch_assoc()) {
    $resources[] = $row;
}

include '../includes/header.php';
?>

<main class="content-section">
    <div class="learning-hub">
        <h1 class="section-title">📚 Learning Hub</h1>
        <p class="section-subtitle">Access study materials, notes, and resources uploaded by faculty.</p>

        <?php if (count($resources) > 0): ?>
            <div class="resources-grid">
                <?php foreach ($resources as $res): ?>
                    <div class="resource-card">
                        <h3><?= htmlspecialchars($res['title']); ?></h3>
                        <p class="resource-desc"><?= htmlspecialchars($res['description']); ?></p>
                        <p class="resource-meta"><strong>Category:</strong> <?= htmlspecialchars($res['category']); ?> | <strong>Uploaded by:</strong> <?= htmlspecialchars($res['faculty_name'] ?? 'Admin'); ?></p>
                        <a href="../students/resources/<?= htmlspecialchars($res['file_path']); ?>" class="btn btn-primary" target="_blank">Download / View</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-data">No resources available yet.</p>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
