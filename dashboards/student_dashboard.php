<?php
if(session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student'){
    header('Location: ../student_login.php');
    exit;
}
include '../includes/header.php';
?>

<main class="dashboard-main">
    <div class="dashboard-header">
        <h1>Welcome, <?php echo $_SESSION['name'] ?? 'Student'; ?>!</h1>
        <p>Here's an overview of your activities and progress.</p>
    </div>

    <div class="dashboard-cards">
        <!-- Attendance Card -->
        <div class="card">
            <i class="fas fa-calendar-check fa-2x"></i>
            <h3>Attendance</h3>
            <p>View your attendance records and status.</p>
            <a href="../students/attendance.php" class="btn btn-secondary">View</a>
        </div>

        <!-- Marks Card -->
        <div class="card">
            <i class="fas fa-chart-line fa-2x"></i>
            <h3>Marks</h3>
            <p>Check your exam scores and performance trends.</p>
            <a href="../students/marks.php" class="btn btn-secondary">View</a>
        </div>

        <!-- Learning Hub Card -->
        <div class="card">
            <i class="fas fa-book fa-2x"></i>
            <h3>Learning Hub</h3>
            <p>Access study materials, notes, and resources.</p>
            <a href="../students/learning_hub.php" class="btn btn-secondary">Open</a>
        </div>

        <!-- Collaboration Card -->
        <div class="card">
            <i class="fas fa-users fa-2x"></i>
            <h3>Collaboration</h3>
            <p>Join discussions and collaborate with peers.</p>
            <a href="../students/collaboration.php" class="btn btn-secondary">Go</a>
        </div>

        <!-- Doubts Card -->
        <div class="card">
            <i class="fas fa-question-circle fa-2x"></i>
            <h3>Doubts</h3>
            <p>Submit your doubts or check answers from faculty.</p>
            <a href="../students/doubt_form.php" class="btn btn-secondary">Ask</a>
        </div>

        <!-- Events Card -->
        <div class="card">
            <i class="fas fa-calendar-alt fa-2x"></i>
            <h3>Events</h3>
            <p>See upcoming events and register to participate.</p>
            <a href="../students/events.php" class="btn btn-secondary">Explore</a>
        </div>

        <!-- Resources Card -->
        <div class="card">
            <i class="fas fa-folder-open fa-2x"></i>
            <h3>Resources</h3>
            <p>Download or view resources uploaded by faculty.</p>
            <a href="../students/resources/index.php" class="btn btn-secondary">Access</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
