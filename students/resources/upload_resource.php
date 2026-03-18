<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../student_login.php');
    exit;
}

require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $uploaded_by = 'student';
    $student_id = $_SESSION['user_id'];
    $file_path = null;

    if (isset($_FILES['resource_file']) && $_FILES['resource_file']['error'] === 0) {
        $upload_dir = '../../uploads/resources/';
        $file_name = time() . '_' . basename($_FILES['resource_file']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['resource_file']['tmp_name'], $target_file)) {
            $file_path = $file_name;
        }
    }

    $stmt = $conn->prepare("INSERT INTO resources (title, category, description, uploaded_by, faculty_id, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    $faculty_id = null; // student uploads will have null faculty_id
    $stmt->bind_param("ssssis", $title, $category, $description, $uploaded_by, $faculty_id, $file_path);
    $stmt->execute();
    header('Location: index.php');
    exit;
}

include '../../includes/header.php';
?>

<main class="content-section">
    <div class="resource-upload">
        <h1 class="section-title">Upload Resource</h1>

        <form action="" method="POST" enctype="multipart/form-data" class="resource-form">
            <label>Title</label>
            <input type="text" name="title" required>

            <label>Category</label>
            <input type="text" name="category" required>

            <label>Description</label>
            <textarea name="description" rows="4" required></textarea>

            <label>File (optional)</label>
            <input type="file" name="resource_file">

            <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
