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

// Handle new attendance submission
if(isset($_POST['submit_attendance'])){
    $date = $_POST['attendance_date'];
    $subject = $_POST['subject'];
    $students = $_POST['student_id'];
    $statuses = $_POST['status'];
    $remarks_list = $_POST['remarks'];

    foreach($students as $i => $student_id){
        $status = $statuses[$i];
        $remarks = $remarks_list[$i];

        // Insert new record
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, faculty_id, subject, attendance_date, status, remarks)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $student_id, $faculty_id, $subject, $date, $status, $remarks);
        $stmt->execute();
    }

    $success_msg = "Attendance submitted successfully!";
}

// Fetch previous attendance for this faculty
$prev_sql = "SELECT a.attendance_id, s.name, s.roll_no, a.subject, a.attendance_date, a.status, a.remarks
             FROM attendance a
             JOIN students s ON a.student_id = s.student_id
             WHERE a.faculty_id = ?
             ORDER BY a.attendance_date DESC, s.name ASC";
$stmt_prev = $conn->prepare($prev_sql);
$stmt_prev->bind_param("i", $faculty_id);
$stmt_prev->execute();
$prev_result = $stmt_prev->get_result();

// Fetch students to mark new attendance
$students_sql = "SELECT student_id, name, roll_no FROM students ORDER BY name";
$students_result = $conn->query($students_sql);
?>

<main class="dashboard-main">
    <h2 style="text-align: center;">Faculty Attendance</h2>

    <!-- Previous Attendance Section -->
    <section class="prev-attendance">
        <h3>Previous Attendance Records</h3>
        <table>
            <tr>
                <th>Student</th>
                <th>Roll No</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
            </tr>
            <?php while($row = $prev_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['roll_no']) ?></td>
                <td><?= htmlspecialchars($row['subject']) ?></td>
                <td><?= htmlspecialchars($row['attendance_date']) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= htmlspecialchars($row['remarks']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- New Attendance Section -->
    <section class="new-attendance">
        <h3>Mark New Attendance</h3>
        <?php if(isset($success_msg)): ?>
            <p class="success"><?= $success_msg ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="attendance_date">Date:</label>
            <input type="date" name="attendance_date" id="attendance_date" required>

            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" required placeholder="Enter subject">

            <table>
                <tr>
                    <th>Student</th>
                    <th>Roll No</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
                <?php while($student = $students_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['roll_no']) ?></td>
                    <td>
                        <select name="status[]">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Late">Late</option>
                        </select>
                    </td>
                    <td><input type="text" name="remarks[]"></td>
                    <input type="hidden" name="student_id[]" value="<?= $student['student_id'] ?>">
                </tr>
                <?php endwhile; ?>
            </table>
            <button type="submit" name="submit_attendance">Submit Attendance</button>
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
