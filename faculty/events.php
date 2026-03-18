<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Ensure user is faculty
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty'){
    header('Location: ../faculty_login.php');
    exit;
}

$faculty_name = $_SESSION['name'] ?? 'Faculty';

// Fetch previous events
$events_sql = "SELECT * FROM events ORDER BY event_date DESC, event_time DESC";
$events_result = $conn->query($events_sql);

// Optional: fetch only faculty-created events
$faculty_events_sql = "SELECT * FROM events WHERE created_by='faculty' ORDER BY event_date DESC";
$faculty_events_result = $conn->query($faculty_events_sql);
?>

<main class="dashboard-main">
    <h2 style="text-align: center;">Events</h2>

    <!-- Previous Events Section -->
    <section class="prev-events">
        <h3>All Previous Events</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
                <th>Venue</th>
                <th>Organizer</th>
            </tr>
            <?php if($events_result->num_rows > 0): ?>
                <?php while($row = $events_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td><?= htmlspecialchars($row['event_date']) ?></td>
                    <td><?= htmlspecialchars($row['event_time']) ?></td>
                    <td><?= htmlspecialchars($row['venue']) ?></td>
                    <td><?= htmlspecialchars($row['organizer']) ?></td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No events found.</td></tr>
            <?php endif; ?>
        </table>
    </section>

    <!-- Add New Event Section -->
    <section class="new-event">
        <h3>Add New Event</h3>
        <?php if(isset($success_msg)): ?>
            <p class="success"><?= $success_msg ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" action="add_event.php">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4"></textarea>

            <label for="department">Department:</label>
            <input type="text" name="department" id="department">

            <label for="event_date">Date:</label>
            <input type="date" name="event_date" id="event_date" required>

            <label for="event_time">Time:</label>
            <input type="time" name="event_time" id="event_time" required>

            <label for="venue">Venue:</label>
            <input type="text" name="venue" id="venue" required>

            <label for="organizer">Organizer:</label>
            <input type="text" name="organizer" id="organizer" value="<?= htmlspecialchars($faculty_name) ?>" readonly>

            <label for="banner_image">Banner Image:</label>
            <input type="file" name="banner_image" id="banner_image">

            <label for="qr_code">QR Code Link:</label>
            <input type="text" name="qr_code" id="qr_code">

            <label for="feedback_form_link">Feedback Form Link:</label>
            <input type="text" name="feedback_form_link" id="feedback_form_link">

            <button type="submit" name="submit_event">Add Event</button>
        </form>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
