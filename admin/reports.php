<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Ensure user is admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../admin_login.php');
    exit;
}

// Handle deletion
if(isset($_POST['delete_type']) && isset($_POST['delete_id'])){
    $type = $_POST['delete_type'];
    $id = $_POST['delete_id'];
    if($type === 'mark'){
        $stmt = $conn->prepare("DELETE FROM marks WHERE mark_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif($type === 'attendance'){
        $stmt = $conn->prepare("DELETE FROM attendance WHERE attendance_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif($type === 'doubt'){
        $stmt = $conn->prepare("DELETE FROM doubts WHERE doubt_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    $success_msg = ucfirst($type)." deleted successfully!";
}

// Handle modification (via form)
if(isset($_POST['edit_type']) && isset($_POST['edit_id'])){
    $type = $_POST['edit_type'];
    $id = $_POST['edit_id'];

    if($type === 'mark'){
        $score = $_POST['score'];
        $total = $_POST['total'];
        $remarks = $_POST['remarks'];
        $stmt = $conn->prepare("UPDATE marks SET score=?, total=?, remarks=? WHERE mark_id=?");
        $stmt->bind_param("ddsi", $score, $total, $remarks, $id);
        $stmt->execute();
    } elseif($type === 'attendance'){
        $status = $_POST['status'];
        $remarks = $_POST['remarks'];
        $stmt = $conn->prepare("UPDATE attendance SET status=?, remarks=? WHERE attendance_id=?");
        $stmt->bind_param("ssi", $status, $remarks, $id);
        $stmt->execute();
    } elseif($type === 'doubt'){
        $answer = $_POST['answer'];
        $status = $_POST['status'];
        $stmt = $conn->prepare("UPDATE doubts SET answer=?, status=? WHERE doubt_id=?");
        $stmt->bind_param("ssi", $answer, $status, $id);
        $stmt->execute();
    }
    $success_msg = ucfirst($type)." updated successfully!";
}

// Fetch students
$students_sql = "SELECT student_id, name, roll_no FROM students ORDER BY name";
$students_result = $conn->query($students_sql);

// Fetch marks
$marks_sql = "SELECT m.*, s.name, s.roll_no FROM marks m JOIN students s ON m.student_id=s.student_id ORDER BY s.name, m.subject";
$marks_result = $conn->query($marks_sql);

// Fetch attendance
$attendance_sql = "SELECT a.*, s.name, s.roll_no FROM attendance a JOIN students s ON a.student_id=s.student_id ORDER BY s.name, a.attendance_date DESC";
$attendance_result = $conn->query($attendance_sql);

// Fetch doubts
$doubts_sql = "SELECT d.*, s.name, s.roll_no FROM doubts d JOIN students s ON d.student_id=s.student_id ORDER BY d.created_at DESC";
$doubts_result = $conn->query($doubts_sql);

// Handle CSV download
if(isset($_POST['download_csv'])){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Type','Student','Roll No','Subject/Question','Score/Status','Total/Answer','Remarks/Status']);

    while($row = $marks_result->fetch_assoc()){
        fputcsv($output, ['Mark', $row['name'], $row['roll_no'], $row['subject'], $row['score'], $row['total'], $row['remarks']]);
    }
    $marks_result->data_seek(0); // reset pointer

    while($row = $attendance_result->fetch_assoc()){
        fputcsv($output, ['Attendance', $row['name'], $row['roll_no'], $row['subject'], $row['status'], '', $row['remarks']]);
    }
    $attendance_result->data_seek(0);

    while($row = $doubts_result->fetch_assoc()){
        fputcsv($output, ['Doubt', $row['name'], $row['roll_no'], $row['subject'], $row['question'], $row['answer'], $row['status']]);
    }
    fclose($output);
    exit;
}
?>

<main class="dashboard-main">
    <h2 style="text-align: center;">Reports & Management</h2>
    <?php if(isset($success_msg)) echo "<p class='success'>$success_msg</p>"; ?>

    <!-- CSV Download -->
    <form method="POST">
        <button type="submit" name="download_csv" class="btn btn-primary">Download CSV</button>
    </form>

    <!-- Student Selection for Graph -->
    <section class="student-graph">
        <h3>Student Performance Graph</h3>
        <form method="GET">
            <select name="student_id" onchange="this.form.submit()">
                <option value="">Select Student</option>
                <?php while($student = $students_result->fetch_assoc()): ?>
                <option value="<?= $student['student_id'] ?>" <?= isset($_GET['student_id']) && $_GET['student_id']==$student['student_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($student['name'])." (".$student['roll_no'].")" ?>
                </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php
        if(isset($_GET['student_id']) && !empty($_GET['student_id'])){
            $sid = intval($_GET['student_id']);
            $marks_q = $conn->prepare("SELECT subject, score, total FROM marks WHERE student_id=?");
            $marks_q->bind_param("i",$sid);
            $marks_q->execute();
            $marks_res = $marks_q->get_result();

            $attendance_q = $conn->prepare("SELECT subject, COUNT(*) as total_classes, SUM(status='Present') as present_count FROM attendance WHERE student_id=? GROUP BY subject");
            $attendance_q->bind_param("i",$sid);
            $attendance_q->execute();
            $att_res = $attendance_q->get_result();

            // Prepare data for JS chart
            $subjects = [];
            $scores = [];
            $totals = [];
            while($m=$marks_res->fetch_assoc()){
                $subjects[] = $m['subject'];
                $scores[] = $m['score'];
                $totals[] = $m['total'];
            }
            $att_subjects = [];
            $present_counts = [];
            while($a=$att_res->fetch_assoc()){
                $att_subjects[] = $a['subject'];
                $present_counts[] = $a['present_count'];
            }
            if(count($subjects) >0 || count($att_subjects)>0):
        ?>
        <canvas id="studentChart" width="800" height="400"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('studentChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode(array_merge($subjects,$att_subjects)) ?>,
                    datasets: [
                        {
                            label: 'Marks',
                            data: <?= json_encode($scores) ?>,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)'
                        },
                        {
                            label: 'Attendance (Present Count)',
                            data: <?= json_encode($present_counts) ?>,
                            backgroundColor: 'rgba(255, 159, 64, 0.6)'
                        }
                    ]
                },
                options: {
                    responsive:true,
                    scales:{ y:{ beginAtZero:true } }
                }
            });
        </script>
        <?php endif; } ?>
    </section>

    <!-- Marks Section -->
    <section class="report-section">
        <h3>Marks</h3>
        <table>
            <tr>
                <th>Student</th>
                <th>Roll No</th>
                <th>Subject</th>
                <th>Score</th>
                <th>Total</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $marks_result->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['roll_no']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><input type="number" name="score" value="<?= $row['score'] ?>" required></td>
                    <td><input type="number" name="total" value="<?= $row['total'] ?>" required></td>
                    <td><input type="text" name="remarks" value="<?= htmlspecialchars($row['remarks']) ?>"></td>
                    <td>
                        <input type="hidden" name="edit_type" value="mark">
                        <input type="hidden" name="edit_id" value="<?= $row['mark_id'] ?>">
                        <button type="submit">Update</button>
                    </td>
                </form>
                <td>
                    <form method="POST">
                        <input type="hidden" name="delete_type" value="mark">
                        <input type="hidden" name="delete_id" value="<?= $row['mark_id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Attendance Section -->
    <section class="report-section">
        <h3>Attendance</h3>
        <table>
            <tr>
                <th>Student</th>
                <th>Roll No</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $attendance_result->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['roll_no']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td>
                        <select name="status">
                            <option value="Present" <?= $row['status']=='Present'?'selected':'' ?>>Present</option>
                            <option value="Absent" <?= $row['status']=='Absent'?'selected':'' ?>>Absent</option>
                            <option value="Late" <?= $row['status']=='Late'?'selected':'' ?>>Late</option>
                        </select>
                    </td>
                    <td><input type="text" name="remarks" value="<?= htmlspecialchars($row['remarks']) ?>"></td>
                    <td>
                        <input type="hidden" name="edit_type" value="attendance">
                        <input type="hidden" name="edit_id" value="<?= $row['attendance_id'] ?>">
                        <button type="submit">Update</button>
                    </td>
                </form>
                <td>
                    <form method="POST">
                        <input type="hidden" name="delete_type" value="attendance">
                        <input type="hidden" name="delete_id" value="<?= $row['attendance_id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Doubts Section -->
    <section class="report-section">
        <h3>Doubts</h3>
        <table>
            <tr>
                <th>Student</th>
                <th>Roll No</th>
                <th>Subject</th>
                <th>Question</th>
                <th>Answer</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $doubts_result->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['roll_no']) ?></td>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= htmlspecialchars($row['question']) ?></td>
                    <td><input type="text" name="answer" value="<?= htmlspecialchars($row['answer']) ?>"></td>
                    <td>
                        <select name="status">
                            <option value="Open" <?= $row['status']=='Open'?'selected':'' ?>>Open</option>
                            <option value="Answered" <?= $row['status']=='Answered'?'selected':'' ?>>Answered</option>
                            <option value="Closed" <?= $row['status']=='Closed'?'selected':'' ?>>Closed</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="edit_type" value="doubt">
                        <input type="hidden" name="edit_id" value="<?= $row['doubt_id'] ?>">
                        <button type="submit">Update</button>
                    </td>
                </form>
                <td>
                    <form method="POST">
                        <input type="hidden" name="delete_type" value="doubt">
                        <input type="hidden" name="delete_id" value="<?= $row['doubt_id'] ?>">
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
