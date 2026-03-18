<?php
if(session_status() == PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../admin_login.php');
    exit;
}
include '../includes/header.php';
?>

<main class="dashboard-main">
    <div class="dashboard-header">
        <h1>Welcome, <?php echo $_SESSION['username'] ?? 'Admin'; ?>!</h1>
        <p>Manage users, content, events, and view reports.</p>
    </div>

    <div class="dashboard-cards">
        <!-- User Management -->
        <!-- <div class="card">
            <i class="fas fa-user-cog fa-2x"></i>
            <h3>User Management</h3>
            <p>Manage students, faculty, and admin accounts.</p>
            <a href="../admin/user_management.php" class="btn btn-secondary">Manage</a>
        </div> -->

        <!-- Content Moderation -->
        <div class="card">
            <i class="fas fa-clipboard-check fa-2x"></i>
            <h3>Content Moderation</h3>
            <p>Approve or remove uploaded resources and posts.</p>
            <a href="../admin/content_moderation.php" class="btn btn-secondary">Moderate</a>
        </div>

        <!-- Event Control -->
        <div class="card">
            <i class="fas fa-calendar-alt fa-2x"></i>
            <h3>Event Control</h3>
            <p>Create, update, and manage campus events.</p>
            <a href="../admin/event_control.php" class="btn btn-secondary">Manage</a>
        </div>

        <!-- Reports -->
        <div class="card">
            <i class="fas fa-chart-pie fa-2x"></i>
            <h3>Reports</h3>
            <p>Generate reports for attendance, marks, and activity logs.</p>
            <a href="../admin/reports.php" class="btn btn-secondary">View</a>
        </div>

        <!-- Permissions -->
        <div class="card">
            <i class="fas fa-key fa-2x"></i>
            <h3>Permissions</h3>
            <p>Set and update access levels for users.</p>
            <a href="../admin/permissions.php" class="btn btn-secondary">Update</a>
        </div>

        <!-- Database Tools -->
        <div class="card">
            <i class="fas fa-database fa-2x"></i>
            <h3>Database Tools</h3>
            <p>Backup, restore, and maintain the database.</p>
            <a href="../admin/database_tools.php" class="btn btn-secondary">Access</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
