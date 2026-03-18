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

// Handle new notification submission
if(isset($_POST['submit_notification'])){
    $title = $_POST['title'];
    $message = $_POST['message'];
    $target = $_POST['target'];

    $stmt = $conn->prepare("INSERT INTO notifications (title, message, target, created_by) VALUES (?, ?, ?, ?)");
    $created_by = $faculty_id;
    $stmt->bind_param("sssi", $title, $message, $target, $created_by);
    $stmt->execute();

    $success_msg = "Notification sent successfully!";
}

// Fetch previous notifications
$prev_sql = "SELECT * FROM notifications WHERE created_by = ? ORDER BY created_at DESC";
$stmt_prev = $conn->prepare($prev_sql);
$stmt_prev->bind_param("i", $faculty_id);
$stmt_prev->execute();
$prev_result = $stmt_prev->get_result();
?>

<main class="dashboard-main">
    <h2 style="text-align: center;">Faculty Notifications</h2>

    <!-- Previous Notifications -->
    <section class="prev-notifications">
        <h3>Previous Notifications</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Message</th>
                <th>Target</th>
                <th>Date</th>
            </tr>
            <?php while($row = $prev_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= htmlspecialchars($row['target']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Add New Notification -->
    <section class="new-notification">
        <h3>Add New Notification</h3>
        <?php if(isset($success_msg)): ?>
            <p class="success"><?= $success_msg ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required placeholder="Enter notification title">

            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="4" required placeholder="Enter notification message"></textarea>

            <label for="target">Target Audience:</label>
            <select name="target" id="target">
                <option value="all">All</option>
                <option value="students">Students</option>
                <option value="faculty">Faculty</option>
                <option value="admins">Admins</option>
            </select>

            <button type="submit" name="submit_notification">Send Notification</button>
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
