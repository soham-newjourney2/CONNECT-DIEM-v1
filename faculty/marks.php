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

// Handle new marks submission
if(isset($_POST['submit_marks'])){
    $subject = $_POST['subject'];
    $exam_type = $_POST['exam_type'];
    $students = $_POST['student_id'];
    $scores = $_POST['score'];
    $totals = $_POST['total'];
    $remarks_list = $_POST['remarks'];

    foreach($students as $i => $student_id){
        $score = $scores[$i];
        $total = $totals[$i];
        $remarks = $remarks_list[$i];

        $stmt = $conn->prepare("INSERT INTO marks (student_id, faculty_id, subject, exam_type, score, total, remarks)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissdds", $student_id, $faculty_id, $subject, $exam_type, $score, $total, $remarks);
        $stmt->execute();
    }

    $success_msg = "Marks submitted successfully!";
}

// Fetch previous marks for this faculty
$prev_sql = "SELECT m.mark_id, s.name, s.roll_no, m.subject, m.exam_type, m.score, m.total, m.remarks, m.recorded_at
             FROM marks m
             JOIN students s ON m.student_id = s.student_id
             WHERE m.faculty_id = ?
             ORDER BY m.recorded_at DESC, s.name ASC";
$stmt_prev = $conn->prepare($prev_sql);
$stmt_prev->bind_param("i", $faculty_id);
$stmt_prev->execute();
$prev_result = $stmt_prev->get_result();

// Fetch students to mark new marks
$students_sql = "SELECT student_id, name, roll_no FROM students ORDER BY name";
$students_result = $conn->query($students_sql);
?>

<main class="dashboard-main marks-page">
    <h2 style="text-align: center;">Faculty Marks</h2>

    <!-- Previous Marks Section -->
    <section class="prev-marks">
        <h3>Previous Marks Records</h3>
        <div class="marks-table-container">
            <table class="marks-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Roll No</th>
                        <th>Subject</th>
                        <th>Exam Type</th>
                        <th>Score</th>
                        <th>Total</th>
                        <th>Remarks</th>
                        <th>Recorded At</th>
                    </tr>
                </thead>
                <tbody>
                <?php while($row = $prev_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['roll_no']) ?></td>
                        <td><?= htmlspecialchars($row['subject']) ?></td>
                        <td><?= htmlspecialchars($row['exam_type']) ?></td>
                        <td><?= htmlspecialchars($row['score']) ?></td>
                        <td><?= htmlspecialchars($row['total']) ?></td>
                        <td><?= htmlspecialchars($row['remarks']) ?></td>
                        <td><?= htmlspecialchars($row['recorded_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- New Marks Section -->
    <section class="new-marks">
        <h3>Enter New Marks</h3>
        <?php if(isset($success_msg)): ?>
            <p class="success"><?= $success_msg ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" required placeholder="Enter subject">

            <label for="exam_type">Exam Type:</label>
            <select name="exam_type" id="exam_type" required>
                <option value="Midterm">Midterm</option>
                <option value="Final">Final</option>
                <option value="Assignment">Assignment</option>
                <option value="Project">Project</option>
            </select>

            <div class="marks-table-container">
                <table class="marks-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Roll No</th>
                            <th>Score</th>
                            <th>Total</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($student = $students_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['roll_no']) ?></td>
                            <td><input type="number" name="score[]" step="0.01" required></td>
                            <td><input type="number" name="total[]" step="0.01" required></td>
                            <td><input type="text" name="remarks[]"></td>
                            <input type="hidden" name="student_id[]" value="<?= $student['student_id'] ?>">
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <button type="submit" name="submit_marks">Submit Marks</button>
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
