<?php
session_start();

// Redirect if not logged in or wrong role
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header('Location: ../student_login.php');
    exit;
}

require_once '../includes/db.php';
include '../includes/header.php';

// Get student ID
$student_id = $_SESSION['user_id'];

// Fetch attendance records
$sql = "SELECT a.attendance_date, a.subject, a.status, a.remarks, f.name AS faculty_name
        FROM attendance a
        JOIN faculty f ON a.faculty_id = f.faculty_id
        WHERE a.student_id = ?
        ORDER BY a.attendance_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<main class="dashboard-main">
    <div class="dashboard-header">
        <h1><i class="fas fa-calendar-check"></i> Attendance</h1>
        <p>Track your daily attendance and subject-wise participation.</p>
    </div>

    <section class="attendance-section">
        <?php if($result->num_rows > 0): ?>
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Faculty</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr class="<?php echo strtolower($row['status']); ?>">
                            <td><?php echo date('d M Y', strtotime($row['attendance_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['subject']); ?></td>
                            <td>
                                <?php if($row['status'] === 'Present'): ?>
                                    <span class="status present">✔ Present</span>
                                <?php elseif($row['status'] === 'Absent'): ?>
                                    <span class="status absent">✖ Absent</span>
                                <?php else: ?>
                                    <span class="status late">⏰ Late</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['remarks'] ?? '-'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <?php
            // Attendance summary
            $present = $conn->query("SELECT COUNT(*) AS c FROM attendance WHERE student_id=$student_id AND status='Present'")->fetch_assoc()['c'];
            $total = $conn->query("SELECT COUNT(*) AS c FROM attendance WHERE student_id=$student_id")->fetch_assoc()['c'];
            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
            ?>
            <div class="attendance-message">
                <p><strong>Attendance Summary:</strong> You have attended <strong><?php echo $present; ?></strong> out of <strong><?php echo $total; ?></strong> classes.</p>
                <p>Your overall attendance percentage is <strong><?php echo $percentage; ?>%</strong>.</p>
            </div>
        <?php else: ?>
            <div class="attendance-message">
                <p>No attendance records found yet. Please check back later.</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php
include '../includes/footer.php';
$conn->close();
?>
