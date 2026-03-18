<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Ensure admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../admin_login.php');
    exit;
}

$success_msg = $error_msg = "";

// Handle Add User
if(isset($_POST['add_user'])){
    $role = $_POST['role'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        if($role === 'student'){
            $stmt = $conn->prepare("INSERT INTO students (name, email, password) VALUES (?, ?, ?)");
        } elseif($role === 'faculty'){
            $stmt = $conn->prepare("INSERT INTO faculty (name, email, password) VALUES (?, ?, ?)");
        } else {
            $stmt = $conn->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
        }
        $stmt->bind_param("sss", $name, $email, $password);
        $stmt->execute();
        $success_msg = ucfirst($role)." added successfully!";
    } catch (Exception $e) {
        $error_msg = "Error adding user: " . $e->getMessage();
    }
}

// Handle Delete User
if(isset($_GET['delete']) && isset($_GET['role'])){
    $id = $_GET['delete'];
    $role = $_GET['role'];

    if($role === 'student'){
        $conn->query("DELETE FROM students WHERE student_id = $id");
    } elseif($role === 'faculty'){
        $conn->query("DELETE FROM faculty WHERE faculty_id = $id");
    } else {
        $conn->query("DELETE FROM admins WHERE admin_id = $id");
    }

    $success_msg = ucfirst($role) . " deleted successfully!";
}

// Handle Edit Request
$edit_data = null;
if(isset($_GET['edit']) && isset($_GET['role'])){
    $id = $_GET['edit'];
    $role = $_GET['role'];

    if($role === 'student'){
        $edit_data = $conn->query("SELECT student_id AS id, name, email, 'student' AS role FROM students WHERE student_id=$id")->fetch_assoc();
    } elseif($role === 'faculty'){
        $edit_data = $conn->query("SELECT faculty_id AS id, name, email, 'faculty' AS role FROM faculty WHERE faculty_id=$id")->fetch_assoc();
    } else {
        $edit_data = $conn->query("SELECT admin_id AS id, username AS name, email, 'admin' AS role FROM admins WHERE admin_id=$id")->fetch_assoc();
    }
}

// Handle Update User
if(isset($_POST['update_user'])){
    $id = $_POST['user_id'];
    $role = $_POST['role'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    if($role === 'student'){
        $stmt = $conn->prepare("UPDATE students SET name=?, email=? WHERE student_id=?");
    } elseif($role === 'faculty'){
        $stmt = $conn->prepare("UPDATE faculty SET name=?, email=? WHERE faculty_id=?");
    } else {
        $stmt = $conn->prepare("UPDATE admins SET username=?, email=? WHERE admin_id=?");
    }
    $stmt->bind_param("ssi", $name, $email, $id);
    $stmt->execute();

    $success_msg = ucfirst($role)." updated successfully!";
}

// Fetch all users
$students = $conn->query("SELECT student_id AS id, name, email, 'student' AS role FROM students ORDER BY join_date DESC");
$faculty = $conn->query("SELECT faculty_id AS id, name, email, 'faculty' AS role FROM faculty ORDER BY join_date DESC");
$admins = $conn->query("SELECT admin_id AS id, username AS name, email, 'admin' AS role FROM admins ORDER BY created_at DESC");
?>

<main class="dashboard-main">
    <h2 style="text-align:center;">User Management</h2>
    <p style="text-align:center;">Add, edit, or remove users (Students, Faculty, Admins).</p>

    <?php if($success_msg): ?>
        <p class="success"><?= htmlspecialchars($success_msg) ?></p>
    <?php elseif($error_msg): ?>
        <p class="error"><?= htmlspecialchars($error_msg) ?></p>
    <?php endif; ?>

    <!-- Add or Edit User Form -->
    <section class="add-user-section">
        <h3><?= $edit_data ? 'Edit User' : 'Add New User' ?></h3>
        <form method="POST" class="user-form">
            <input type="hidden" name="user_id" value="<?= $edit_data['id'] ?? '' ?>">
            
            <label for="role">Select Role:</label>
            <select name="role" id="role" required <?= $edit_data ? 'readonly' : '' ?>>
                <option value="">-- Choose Role --</option>
                <option value="student" <?= (isset($edit_data) && $edit_data['role'] === 'student') ? 'selected' : '' ?>>Student</option>
                <option value="faculty" <?= (isset($edit_data) && $edit_data['role'] === 'faculty') ? 'selected' : '' ?>>Faculty</option>
                <option value="admin" <?= (isset($edit_data) && $edit_data['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
            </select>

            <label for="name">Name / Username:</label>
            <input type="text" name="name" id="name" required value="<?= htmlspecialchars($edit_data['name'] ?? '') ?>">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required value="<?= htmlspecialchars($edit_data['email'] ?? '') ?>">

            <?php if(!$edit_data): ?>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required placeholder="Enter password">
            <?php endif; ?>

            <button type="submit" name="<?= $edit_data ? 'update_user' : 'add_user' ?>">
                <?= $edit_data ? 'Update User' : 'Add User' ?>
            </button>

            <?php if($edit_data): ?>
                <a href="user_management.php" class="btn-cancel">Cancel Edit</a>
            <?php endif; ?>
        </form>
    </section>

    <!-- User Lists -->
    <section class="user-lists">
        <h3>All Users</h3>
        <table>
            <thead>
                <tr>
                    <th>Name / Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= ucfirst($row['role']) ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>&role=student" class="btn-edit">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>&role=student" class="btn-danger" onclick="return confirm('Delete this student?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php while($row = $faculty->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= ucfirst($row['role']) ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>&role=faculty" class="btn-edit">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>&role=faculty" class="btn-danger" onclick="return confirm('Delete this faculty?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php while($row = $admins->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= ucfirst($row['role']) ?></td>
                        <td>
                            <a href="?edit=<?= $row['id'] ?>&role=admin" class="btn-edit">Edit</a>
                            <a href="?delete=<?= $row['id'] ?>&role=admin" class="btn-danger" onclick="return confirm('Delete this admin?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include '../includes/footer.php'; ?>
