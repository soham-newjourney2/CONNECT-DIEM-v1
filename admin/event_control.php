<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../admin_login.php');
    exit;
}

// Handle New Event Submission
if(isset($_POST['create_event'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $department = $_POST['department'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $venue = $_POST['venue'];
    $organizer = $_POST['organizer'];
    $feedback_form_link = $_POST['feedback_form_link'];

    // Handle Banner Upload
    $banner_file = $_FILES['banner_image'];
    $banner_filename = null;
    if($banner_file['name']){
        $banner_filename = time().'_'.$banner_file['name'];
        move_uploaded_file($banner_file['tmp_name'], "../uploads/events/".$banner_filename);
    }

    // Handle QR Code Upload
    $qr_file = $_FILES['qr_code'];
    $qr_filename = null;
    if($qr_file['name']){
        $qr_filename = time().'_'.$qr_file['name'];
        move_uploaded_file($qr_file['tmp_name'], "../uploads/events/".$qr_filename);
    }

    $stmt = $conn->prepare("INSERT INTO events (title, description, department, event_date, event_time, venue, organizer, banner_image, qr_code, feedback_form_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $title, $description, $department, $event_date, $event_time, $venue, $organizer, $banner_filename, $qr_filename, $feedback_form_link);
    $stmt->execute();
    $success_msg = "Event created successfully!";
}

// Handle Edit Event
if(isset($_POST['edit_event'])){
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $department = $_POST['department'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $venue = $_POST['venue'];
    $organizer = $_POST['organizer'];
    $feedback_form_link = $_POST['feedback_form_link'];

    // Existing files
    $banner_filename = $_POST['existing_banner'];
    $qr_filename = $_POST['existing_qr'];

    // Update files if new ones uploaded
    if(!empty($_FILES['banner_image']['name'])){
        $banner_file = $_FILES['banner_image'];
        $banner_filename = time().'_'.$banner_file['name'];
        move_uploaded_file($banner_file['tmp_name'], "../uploads/events/".$banner_filename);
    }

    if(!empty($_FILES['qr_code']['name'])){
        $qr_file = $_FILES['qr_code'];
        $qr_filename = time().'_'.$qr_file['name'];
        move_uploaded_file($qr_file['tmp_name'], "../uploads/events/".$qr_filename);
    }

    $stmt = $conn->prepare("UPDATE events SET title=?, description=?, department=?, event_date=?, event_time=?, venue=?, organizer=?, banner_image=?, qr_code=?, feedback_form_link=? WHERE event_id=?");
    $stmt->bind_param("ssssssssssi", $title, $description, $department, $event_date, $event_time, $venue, $organizer, $banner_filename, $qr_filename, $feedback_form_link, $event_id);
    $stmt->execute();
    $edit_msg = "Event updated successfully!";
}

// Handle Delete Event
if(isset($_POST['delete_event'])){
    $event_id = $_POST['event_id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE event_id=?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $delete_msg = "Event deleted successfully!";
}

// Handle CSV Download
if(isset($_POST['download_csv'])){
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="events.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID','Title','Description','Department','Date','Time','Venue','Organizer','Banner','QR','Feedback Link']);

    $events_csv = $conn->query("SELECT * FROM events ORDER BY event_date DESC");
    while($row = $events_csv->fetch_assoc()){
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

// Fetch All Events
$events_result = $conn->query("SELECT * FROM events ORDER BY event_date DESC, event_time DESC");
?>

<main class="dashboard-main">
    <h2 style="text-align:center;">Event Control</h2>

    <?php if(isset($success_msg)) echo "<p class='success'>$success_msg</p>"; ?>
    <?php if(isset($edit_msg)) echo "<p class='success'>$edit_msg</p>"; ?>
    <?php if(isset($delete_msg)) echo "<p class='success'>$delete_msg</p>"; ?>

    <!-- New Event Form -->
    <section class="new-event">
        <h3>Create New Event</h3>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required>
            <label>Description:</label>
            <textarea name="description" rows="4" required></textarea>
            <label>Department:</label>
            <input type="text" name="department">
            <label>Date:</label>
            <input type="date" name="event_date" required>
            <label>Time:</label>
            <input type="time" name="event_time" required>
            <label>Venue:</label>
            <input type="text" name="venue">
            <label>Organizer:</label>
            <input type="text" name="organizer">
            <label>Banner Image:</label>
            <input type="file" name="banner_image" accept="image/*">
            <label>QR Code:</label>
            <input type="file" name="qr_code" accept="image/*">
            <label>Feedback Form Link:</label>
            <input type="url" name="feedback_form_link">
            <button type="submit" name="create_event">Create Event</button>
        </form>
    </section>

    <!-- Event Table -->
    <section class="events-table">
        <h3>All Events</h3>
        <form method="POST">
            <button type="submit" name="download_csv">Download CSV</button>
        </form>
        <table>
            <tr>
                <th>Title</th>
                <th>Department</th>
                <th>Date</th>
                <th>Time</th>
                <th>Venue</th>
                <th>Organizer</th>
                <th>Banner</th>
                <th>QR</th>
                <th>Feedback</th>
                <th>Actions</th>
            </tr>
            <?php while($event = $events_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($event['title']) ?></td>
                <td><?= htmlspecialchars($event['department']) ?></td>
                <td><?= htmlspecialchars($event['event_date']) ?></td>
                <td><?= htmlspecialchars($event['event_time']) ?></td>
                <td><?= htmlspecialchars($event['venue']) ?></td>
                <td><?= htmlspecialchars($event['organizer']) ?></td>
                <td>
                    <?php if($event['banner_image']): ?>
                        <a href="../uploads/events/<?= $event['banner_image'] ?>" target="_blank">View</a>
                    <?php else: ?> -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($event['qr_code']): ?>
                        <a href="../uploads/events/<?= $event['qr_code'] ?>" target="_blank">View</a>
                    <?php else: ?> -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($event['feedback_form_link']): ?>
                        <a href="<?= $event['feedback_form_link'] ?>" target="_blank">Form</a>
                    <?php else: ?> -
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Edit Form -->
                    <form method="POST" enctype="multipart/form-data" style="display:flex; flex-direction:column;">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                        <input type="hidden" name="existing_banner" value="<?= $event['banner_image'] ?>">
                        <input type="hidden" name="existing_qr" value="<?= $event['qr_code'] ?>">
                        <button type="submit" name="edit_event">Edit</button>
                    </form>

                    <!-- Delete Form -->
                    <form method="POST">
                        <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                        <button type="submit" name="delete_event" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
