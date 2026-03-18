<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Ensure user is faculty
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty'){
    header('Location: ../faculty_login.php');
    exit;
}

$faculty_id = $_SESSION['user_id'];

// Handle new resource upload
if(isset($_POST['submit_resource'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $file_path = '';

    if(isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0){
        $target_dir = "../uploads/resources/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . '_' . basename($_FILES['resource_file']['name']);
        $target_file = $target_dir . $file_name;
        if(move_uploaded_file($_FILES['resource_file']['tmp_name'], $target_file)){
            $file_path = "uploads/resources/" . $file_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO resources (title, description, file_path, category, uploaded_by, faculty_id) 
                            VALUES (?, ?, ?, ?, 'faculty', ?)");
    $stmt->bind_param("ssssi", $title, $description, $file_path, $category, $faculty_id);
    $stmt->execute();

    $success_msg = "Resource uploaded successfully!";
}

// Fetch previous resources uploaded by this faculty
$stmt_prev = $conn->prepare("SELECT * FROM resources WHERE faculty_id = ? ORDER BY upload_date DESC");
$stmt_prev->bind_param("i", $faculty_id);
$stmt_prev->execute();
$prev_result = $stmt_prev->get_result();
?>

<main class="dashboard-main">
    <h2 style="text-align: center;">Faculty Resources</h2>

    <!-- Previous Resources -->
    <section class="prev-resources">
        <h3>Previous Resources</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Description</th>
                <th>File</th>
                <th>Uploaded At</th>
            </tr>
            <?php while($row = $prev_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <?php if($row['file_path']): ?>
                        <a href="../<?= htmlspecialchars($row['file_path']) ?>" target="_blank">Download</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['upload_date']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Add New Resource -->
    <section class="new-resource">
        <h3>Add New Resource</h3>
        <?php if(isset($success_msg)): ?>
            <p class="success"><?= $success_msg ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="category">Category:</label>
            <input type="text" name="category" id="category" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="3"></textarea>

            <label for="resource_file">File:</label>
            <input type="file" name="resource_file" id="resource_file">

            <button type="submit" name="submit_resource">Upload Resource</button>
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
