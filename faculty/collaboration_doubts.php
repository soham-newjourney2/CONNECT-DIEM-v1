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

// Handle new collaboration post submission
if(isset($_POST['submit_collab'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO collaboration (author_type, author_id, title, content) VALUES ('faculty', ?, ?, ?)");
    $stmt->bind_param("iss", $faculty_id, $title, $content);
    $stmt->execute();
    $success_msg = "Collaboration post added successfully!";
}

// Handle answering doubts
if(isset($_POST['answer_doubt'])){
    $doubt_id = $_POST['doubt_id'];
    $answer = $_POST['answer'];
    $stmt = $conn->prepare("UPDATE doubts SET answer = ?, status = 'Answered', faculty_id = ? WHERE doubt_id = ?");
    $stmt->bind_param("sii", $answer, $faculty_id, $doubt_id);
    $stmt->execute();
    $answer_msg = "Doubt answered successfully!";
}

// Fetch previous collaboration posts
$collab_sql = "SELECT * FROM collaboration WHERE author_type='faculty' AND author_id=? ORDER BY created_at DESC";
$stmt_collab = $conn->prepare($collab_sql);
$stmt_collab->bind_param("i", $faculty_id);
$stmt_collab->execute();
$collab_result = $stmt_collab->get_result();

// Fetch doubts assigned to this faculty OR unassigned
$doubts_sql = "SELECT d.*, s.name AS student_name, s.roll_no 
               FROM doubts d 
               JOIN students s ON d.student_id = s.student_id 
               WHERE (d.faculty_id=? OR d.faculty_id IS NULL) AND d.status!='Closed' 
               ORDER BY d.created_at DESC";
$stmt_doubts = $conn->prepare($doubts_sql);
$stmt_doubts->bind_param("i", $faculty_id);
$stmt_doubts->execute();
$doubts_result = $stmt_doubts->get_result();
?>

<main class="dashboard-main">
    <h2 style="text-align: center;">Collaboration & Doubts</h2>

    <!-- Previous Collaboration Posts -->
    <section class="prev-collaboration">
        <h3>Your Previous Collaboration Posts</h3>
        <?php if($collab_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
            </tr>
            <?php while($row = $collab_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= nl2br(htmlspecialchars($row['content'])) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No collaboration posts yet.</p>
        <?php endif; ?>
    </section>

    <!-- New Collaboration Post -->
    <section class="new-collaboration">
        <h3>Add New Collaboration Post</h3>
        <?php if(isset($success_msg)) echo "<p class='success'>$success_msg</p>"; ?>
        <form method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required placeholder="Enter post title">

            <label for="content">Content:</label>
            <textarea name="content" id="content" rows="5" required placeholder="Write your collaboration content..."></textarea>

            <button type="submit" name="submit_collab">Post</button>
        </form>
    </section>

    <!-- Doubts Section -->
    <section class="faculty-doubts">
        <h3>Student Doubts</h3>
        <?php if(isset($answer_msg)) echo "<p class='success'>$answer_msg</p>"; ?>
        <?php if($doubts_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Student</th>
                <th>Roll No</th>
                <th>Subject</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Action</th>
            </tr>
            <?php while($doubt = $doubts_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($doubt['student_name']) ?></td>
                <td><?= htmlspecialchars($doubt['roll_no']) ?></td>
                <td><?= htmlspecialchars($doubt['subject']) ?></td>
                <td><?= nl2br(htmlspecialchars($doubt['question'])) ?></td>
                <td><?= nl2br(htmlspecialchars($doubt['answer'] ?? '-')) ?></td>
                <td>
                    <?php if(!$doubt['answer']): ?>
                    <form method="POST" style="display:flex; flex-direction:column; gap:5px;">
                        <input type="hidden" name="doubt_id" value="<?= $doubt['doubt_id'] ?>">
                        <textarea name="answer" rows="2" placeholder="Write your answer..." required></textarea>
                        <button type="submit" name="answer_doubt">Answer</button>
                    </form>
                    <?php else: ?>
                        Answered
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No doubts assigned or available.</p>
        <?php endif; ?>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
