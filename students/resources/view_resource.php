<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../student_login.php');
    exit;
}

require_once '../../includes/db.php';

// Validate resource_id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$resource_id = intval($_GET['id']);
$sql = "SELECT r.*, f.name AS faculty_name 
        FROM resources r 
        LEFT JOIN faculty f ON r.faculty_id = f.faculty_id 
        WHERE r.resource_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $resource_id);
$stmt->execute();
$result = $stmt->get_result();
$resource = $result->fetch_assoc();

if (!$resource) {
    header('Location: index.php');
    exit;
}

include '../../includes/header.php';
?>

<main class="content-section">
    <div class="resource-view">
        <h1 class="section-title"><?= htmlspecialchars($resource['title']); ?></h1>
        <p class="section-subtitle">Uploaded by <?= htmlspecialchars($resource['faculty_name'] ?? $resource['uploaded_by']); ?> on <?= date('d M Y', strtotime($resource['upload_date'])); ?></p>

        <div class="resource-details">
            <p><strong>Category:</strong> <?= htmlspecialchars($resource['category']); ?></p>
            <p><?= nl2br(htmlspecialchars($resource['description'])); ?></p>
            <?php if (!empty($resource['file_path'])): ?>
                <a href="../../uploads/resources/<?= htmlspecialchars($resource['file_path']); ?>" class="btn btn-primary" target="_blank">View / Download File</a>
            <?php endif; ?>
        </div>
        <a href="index.php" class="btn btn-secondary mt-3">Back to Resources</a>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
