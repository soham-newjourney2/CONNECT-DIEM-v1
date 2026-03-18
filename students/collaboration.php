<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../student_login.php');
    exit;
}
require_once '../includes/db.php';

// Fetch collaboration posts
$sql = "SELECT c.post_id, c.title, c.content, c.attachment, c.created_at, c.author_type, 
               COALESCE(s.name, f.name) AS author_name
        FROM collaboration c
        LEFT JOIN students s ON (c.author_type='student' AND c.author_id=s.student_id)
        LEFT JOIN faculty f ON (c.author_type='faculty' AND c.author_id=f.faculty_id)
        ORDER BY c.created_at DESC";
$result = $conn->query($sql);

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

include '../includes/header.php';
?>

<main class="content-section">
    <div class="collaboration-section">
        <h1 class="section-title">💬 Collaboration</h1>
        <p class="section-subtitle">Join discussions, share ideas, and collaborate with peers and faculty.</p>

        <?php if (count($posts) > 0): ?>
            <div class="posts-grid">
                <?php foreach ($posts as $post): ?>
                    <div class="post-card">
                        <h3><?= htmlspecialchars($post['title']); ?></h3>
                        <p class="post-meta">By <?= htmlspecialchars($post['author_name']); ?> (<?= ucfirst($post['author_type']); ?>) | <?= date('d M Y', strtotime($post['created_at'])); ?></p>
                        <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                        <?php if (!empty($post['attachment'])): ?>
                            <a href="../students/resources/<?= htmlspecialchars($post['attachment']); ?>" class="btn btn-secondary" target="_blank">View Attachment</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-data">No collaboration posts available yet.</p>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
