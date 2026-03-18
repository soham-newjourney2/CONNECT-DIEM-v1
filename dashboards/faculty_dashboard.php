<?php
if(session_status() == PHP_SESSION_NONE) session_start();

// Restrict access to faculty only
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty'){
    header('Location: ../faculty_login.php');
    exit;
}

// Include header
include '../includes/header.php';
include '../includes/db.php';

// Get faculty ID from session
$faculty_id = $_SESSION['user_id'];

// Fetch quick stats
// Total students in faculty's department
$student_count_query = "SELECT COUNT(*) as total_students FROM students WHERE department = (SELECT department FROM faculty WHERE faculty_id = $faculty_id)";
$student_count = $conn->query($student_count_query)->fetch_assoc()['total_students'] ?? 0;

// Today's attendance summary
$today = date('Y-m-d');
$attendance_query = "SELECT status, COUNT(*) as count 
                     FROM attendance 
                     WHERE faculty_id = $faculty_id AND attendance_date = '$today' 
                     GROUP BY status";
$attendance_result = $conn->query($attendance_query);
$attendance_summary = ['Present'=>0,'Absent'=>0,'Late'=>0];
while($row = $attendance_result->fetch_assoc()){
    $attendance_summary[$row['status']] = $row['count'];
}

// Recent marks entered
$marks_query = "SELECT COUNT(*) as recent_marks FROM marks WHERE faculty_id = $faculty_id AND recorded_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$recent_marks = $conn->query($marks_query)->fetch_assoc()['recent_marks'] ?? 0;

// Upcoming events
$events_query = "SELECT COUNT(*) as upcoming_events FROM events WHERE created_by='faculty' AND organizer=(SELECT name FROM faculty WHERE faculty_id = $faculty_id) AND event_date >= CURDATE()";
$upcoming_events = $conn->query($events_query)->fetch_assoc()['upcoming_events'] ?? 0;

// Notifications for faculty
$notif_query = "SELECT COUNT(*) as notifications FROM notifications WHERE target IN ('all','faculty')";
$notifications = $conn->query($notif_query)->fetch_assoc()['notifications'] ?? 0;
?>

<main class="dashboard-main">
    <div class="dashboard-header" style="text-align:center;">
        <h1>Welcome, <?php echo $_SESSION['name'] ?? 'Faculty'; ?>!</h1>
        <p>Here's an overview of your activities and quick access to modules.</p>
    </div>

    <div class="dashboard-cards">
        <!-- Total Students Card -->
        <div class="card">
            <i class="fas fa-users fa-2x"></i>
            <h3>Total Students</h3>
            <p><?php echo $student_count; ?> students in your department</p>
        </div>

        <!-- Attendance Summary Card -->
        <div class="card">
            <i class="fas fa-calendar-check fa-2x"></i>
            <h3>Today's Attendance</h3>
            <p>Present: <?php echo $attendance_summary['Present']; ?> | Absent: <?php echo $attendance_summary['Absent']; ?> | Late: <?php echo $attendance_summary['Late']; ?></p>
            <a href="../faculty/attendance.php" class="btn btn-secondary">Manage</a>
        </div>

        <!-- Recent Marks Card -->
        <div class="card">
            <i class="fas fa-chart-line fa-2x"></i>
            <h3>Recent Marks</h3>
            <p><?php echo $recent_marks; ?> entries in last 7 days</p>
            <a href="../faculty/marks.php" class="btn btn-secondary">Enter/Modify</a>
        </div>

        <!-- Upcoming Events Card -->
        <div class="card">
            <i class="fas fa-calendar-alt fa-2x"></i>
            <h3>Upcoming Events</h3>
            <p><?php echo $upcoming_events; ?> events</p>
            <a href="../faculty/events.php" class="btn btn-secondary">Manage</a>
        </div>

        <!-- Notifications Card -->
        <div class="card">
            <i class="fas fa-bell fa-2x"></i>
            <h3>Notifications</h3>
            <p><?php echo $notifications; ?> new</p>
            <a href="../faculty/notifications.php" class="btn btn-secondary">View</a>
        </div>

        <!-- Attendance Module Shortcut -->
        <!-- <div class="card">
            <i class="fas fa-clipboard-list fa-2x"></i>
            <h3>Give Attendance</h3>
            <p>Mark attendance for your classes</p>
            <a href="../faculty/attendance.php" class="btn btn-secondary">Open</a>
        </div> -->

        <!-- Marks Module Shortcut -->
        <!-- <div class="card">
            <i class="fas fa-edit fa-2x"></i>
            <h3>Marks</h3>
            <p>Enter or modify student marks</p>
            <a href="../faculty/performance.php" class="btn btn-secondary">Open</a>
        </div> -->

        <!-- Collaboration / Doubts Shortcut -->
        <div class="card">
            <i class="fas fa-comments fa-2x"></i>
            <h3>Collaboration & Doubts</h3>
            <p>Post collaboration notes or answer doubts</p>
            <a href="../faculty/collaboration_doubts.php" class="btn btn-secondary">Go</a>
        </div>

        <!-- Resources Shortcut -->
        <div class="card">
            <i class="fas fa-folder-open fa-2x"></i>
            <h3>Resources</h3>
            <p>Upload and manage educational resources</p>
            <a href="../faculty/resources.php" class="btn btn-secondary">Manage</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
