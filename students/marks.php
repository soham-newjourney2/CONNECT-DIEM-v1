<?php
if (session_status() == PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header('Location: ../student_login.php');
    exit;
}

require_once '../includes/db.php';
include '../includes/header.php';

// Fetch student marks
$student_id = $_SESSION['user_id'];
$sql = "SELECT subject, exam_type, score, total, remarks, recorded_at 
        FROM marks 
        WHERE student_id = ?
        ORDER BY recorded_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="content-section">
    <section class="marks-section">
        <h1 class="section-title">📘 Marks Overview</h1>
        <p class="section-subtitle">View your marks and performance across all exams.</p>

        <div class="marks-container">
            <table class="marks-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Exam Type</th>
                        <th>Score</th>
                        <th>Total</th>
                        <th>Percentage</th>
                        <th>Remarks</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): 
                            $percentage = ($row['total'] > 0) ? round(($row['score'] / $row['total']) * 100, 2) : 0;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['exam_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['score']); ?></td>
                                <td><?php echo htmlspecialchars($row['total']); ?></td>
                                <td><?php echo $percentage . '%'; ?></td>
                                <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                                <td><?php echo date('d M Y', strtotime($row['recorded_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-data">No marks records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
