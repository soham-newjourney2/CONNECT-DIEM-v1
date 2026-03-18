<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../student_login.php');
    exit;
}
require_once '../includes/db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $conn->real_escape_string($_POST['subject']);
    $question = $conn->real_escape_string($_POST['question']);
    $student_id = $_SESSION['user_id'];

    $insert_sql = "INSERT INTO doubts (student_id, question, subject) VALUES ('$student_id', '$question', '$subject')";
    if ($conn->query($insert_sql)) {
        $success_msg = "Your doubt has been submitted successfully.";
    } else {
        $error_msg = "Error submitting your doubt. Please try again.";
    }
}

// Fetch existing doubts of the student
$student_id = $_SESSION['user_id'];
$doubt_sql = "SELECT d.*, f.name AS faculty_name
              FROM doubts d
              LEFT JOIN faculty f ON d.faculty_id = f.faculty_id
              WHERE d.student_id = '$student_id'
              ORDER BY d.created_at DESC";
$doubt_result = $conn->query($doubt_sql);

$doubts = [];
while ($row = $doubt_result->fetch_assoc()) {
    $doubts[] = $row;
}

include '../includes/header.php';
?>

<main class="content-section">
    <div class="doubt-section">
        <h1 class="section-title">❓ Doubts</h1>
        <p class="section-subtitle">Submit your questions and view answers from faculty.</p>

        <!-- Submission Form -->
        <div class="doubt-form-card">
            <?php if (!empty($success_msg)): ?>
                <p class="form-success"><?= $success_msg; ?></p>
            <?php endif; ?>
            <?php if (!empty($error_msg)): ?>
                <p class="form-error"><?= $error_msg; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" id="subject" placeholder="Enter subject" required>
                </div>
                <div class="form-group">
                    <label for="question">Your Question</label>
                    <textarea name="question" id="question" rows="4" placeholder="Type your question here..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Doubt</button>
            </form>
        </div>

        <!-- Existing Doubts -->
        <div class="doubts-list">
            <?php if (count($doubts) > 0): ?>
                <?php foreach ($doubts as $doubt): ?>
                    <div class="doubt-card">
                        <p class="doubt-subject"><?= htmlspecialchars($doubt['subject']); ?></p>
                        <p class="doubt-content"><?= nl2br(htmlspecialchars($doubt['question'])); ?></p>
                        <p class="doubt-meta">
                            Submitted on <?= date('d M Y', strtotime($doubt['created_at'])); ?>
                            <?php if ($doubt['status'] !== 'Open'): ?>
                                | Answered by <?= htmlspecialchars($doubt['faculty_name']); ?>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($doubt['answer'])): ?>
                            <div class="doubt-answer">
                                <strong>Answer:</strong>
                                <p><?= nl2br(htmlspecialchars($doubt['answer'])); ?></p>
                            </div>
                        <?php endif; ?>
                        <p class="doubt-status">Status: <?= $doubt['status']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-data">You have not submitted any doubts yet.</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
