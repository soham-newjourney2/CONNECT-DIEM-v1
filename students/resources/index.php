<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../student_login.php');
    exit;
}

require_once '../../includes/db.php';

// Fetch all resources
$resources_sql = "SELECT r.*, f.name AS faculty_name 
                  FROM resources r 
                  LEFT JOIN faculty f ON r.faculty_id = f.faculty_id 
                  ORDER BY r.upload_date DESC";
$resources_result = $conn->query($resources_sql);

$resources = [];
while ($row = $resources_result->fetch_assoc()) {
    $resources[] = $row;
}

include '../../includes/header.php';
?>

<main class="content-section">
    <div class="resources-section">
        <h1 class="section-title">📚 Learning Resources</h1>
        <p class="section-subtitle">Access study materials uploaded by your faculty or administration.</p>

        <?php if (count($resources) > 0): ?>
            <div class="resources-grid">
                <?php foreach ($resources as $res): ?>
                    <div class="resource-card">
                        <h3 class="resource-title"><?= htmlspecialchars($res['title']); ?></h3>
                        <p class="resource-category"><strong>Category:</strong> <?= htmlspecialchars($res['category']); ?></p>
                        <p class="resource-uploaded">Uploaded by: <?= htmlspecialchars($res['faculty_name'] ?? $res['uploaded_by']); ?> on <?= date('d M Y', strtotime($res['upload_date'])); ?></p>
                        <p class="resource-desc"><?= nl2br(htmlspecialchars($res['description'])); ?></p>
                        <?php if (!empty($res['file_path'])): ?>
                            <a href="../../uploads/resources/<?= htmlspecialchars($res['file_path']); ?>" class="btn btn-primary" target="_blank">View / Download</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-data">No resources available at the moment.</p>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
