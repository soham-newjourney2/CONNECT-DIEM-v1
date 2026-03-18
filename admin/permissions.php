<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Ensure admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header('Location: ../admin_login.php');
    exit;
}

// --- Handle Create / Edit / Delete actions ---

// --- CREATE STUDENT ---
if(isset($_POST['create_student'])){
    $roll_no = $_POST['roll_no'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $department = $_POST['department'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO students (roll_no, name, email, password, department, year, section, contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiss", $roll_no, $name, $email, $password, $department, $year, $section, $contact);
    if($stmt->execute()){
        $success_msg = "Student created successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

// --- EDIT STUDENT ---
if(isset($_POST['edit_student'])){
    $student_id = $_POST['student_id'];
    $roll_no = $_POST['roll_no'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $section = $_POST['section'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    if(!empty($password)){
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE students SET roll_no=?, name=?, email=?, department=?, year=?, section=?, contact=?, password=? WHERE student_id=?");
        $stmt->bind_param("ssssisisii", $roll_no, $name, $email, $department, $year, $section, $contact, $password_hash, $student_id);
    } else {
        $stmt = $conn->prepare("UPDATE students SET roll_no=?, name=?, email=?, department=?, year=?, section=?, contact=? WHERE student_id=?");
        $stmt->bind_param("ssssissi", $roll_no, $name, $email, $department, $year, $section, $contact, $student_id);
    }
    if($stmt->execute()){
        $success_msg = "Student updated successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

// --- DELETE STUDENT ---
if(isset($_POST['delete_student'])){
    $student_id = $_POST['student_id'];
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id=?");
    $stmt->bind_param("i", $student_id);
    if($stmt->execute()){
        $success_msg = "Student deleted successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

// --- Similar handling for Faculty ---
if(isset($_POST['create_faculty'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO faculty (name, email, password, department, designation, contact) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $password, $department, $designation, $contact);
    if($stmt->execute()){
        $success_msg = "Faculty created successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

if(isset($_POST['edit_faculty'])){
    $faculty_id = $_POST['faculty_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $department = $_POST['department'];
    $designation = $_POST['designation'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    if(!empty($password)){
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE faculty SET name=?, email=?, department=?, designation=?, contact=?, password=? WHERE faculty_id=?");
        $stmt->bind_param("ssssssi", $name, $email, $department, $designation, $contact, $password_hash, $faculty_id);
    } else {
        $stmt = $conn->prepare("UPDATE faculty SET name=?, email=?, department=?, designation=?, contact=? WHERE faculty_id=?");
        $stmt->bind_param("sssssi", $name, $email, $department, $designation, $contact, $faculty_id);
    }
    if($stmt->execute()){
        $success_msg = "Faculty updated successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

if(isset($_POST['delete_faculty'])){
    $faculty_id = $_POST['faculty_id'];
    $stmt = $conn->prepare("DELETE FROM faculty WHERE faculty_id=?");
    $stmt->bind_param("i", $faculty_id);
    if($stmt->execute()){
        $success_msg = "Faculty deleted successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

// --- Admins ---
if(isset($_POST['create_admin'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO admins (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $role);
    if($stmt->execute()){
        $success_msg = "Admin created successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

if(isset($_POST['edit_admin'])){
    $admin_id = $_POST['admin_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    if(!empty($password)){
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE admins SET username=?, email=?, role=?, password=? WHERE admin_id=?");
        $stmt->bind_param("ssssi", $username, $email, $role, $password_hash, $admin_id);
    } else {
        $stmt = $conn->prepare("UPDATE admins SET username=?, email=?, role=? WHERE admin_id=?");
        $stmt->bind_param("sssi", $username, $email, $role, $admin_id);
    }
    if($stmt->execute()){
        $success_msg = "Admin updated successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

if(isset($_POST['delete_admin'])){
    $admin_id = $_POST['admin_id'];
    $stmt = $conn->prepare("DELETE FROM admins WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);
    if($stmt->execute()){
        $success_msg = "Admin deleted successfully!";
    } else {
        $error_msg = "Error: ".$stmt->error;
    }
}

// --- Fetch all data ---
$students = $conn->query("SELECT * FROM students ORDER BY name ASC");
$faculty = $conn->query("SELECT * FROM faculty ORDER BY name ASC");
$admins = $conn->query("SELECT * FROM admins ORDER BY username ASC");
?>

<main class="dashboard-main">
    <h2 style="text-align:center;">User & Permissions Management</h2>

    <?php if(isset($success_msg)) echo "<p class='success'>$success_msg</p>"; ?>
    <?php if(isset($error_msg)) echo "<p class='error'>$error_msg</p>"; ?>

    <div class="tabs">
        <button class="tab-button active" onclick="showTab('students')">Students</button>
        <button class="tab-button" onclick="showTab('faculty')">Faculty</button>
        <button class="tab-button" onclick="showTab('admins')">Admins</button>
    </div>

    <!-- Students Tab -->
    <section id="students" class="tab-content">
        <h3>Create New Student</h3>
        <form method="POST" class="form-inline">
            <input type="text" name="roll_no" placeholder="Roll No" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="department" placeholder="Department">
            <input type="number" name="year" placeholder="Year">
            <input type="text" name="section" placeholder="Section">
            <input type="text" name="contact" placeholder="Contact">
            <button type="submit" name="create_student">Create</button>
        </form>

        <h3>Existing Students</h3>
        <table>
            <tr>
                <th>Roll No</th><th>Name</th><th>Email</th><th>Dept</th><th>Year</th><th>Section</th><th>Contact</th><th>Actions</th>
            </tr>
            <?php while($s = $students->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><input type="text" name="roll_no" value="<?= htmlspecialchars($s['roll_no']) ?>"></td>
                    <td><input type="text" name="name" value="<?= htmlspecialchars($s['name']) ?>"></td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($s['email']) ?>"></td>
                    <td><input type="text" name="department" value="<?= htmlspecialchars($s['department']) ?>"></td>
                    <td><input type="number" name="year" value="<?= htmlspecialchars($s['year']) ?>"></td>
                    <td><input type="text" name="section" value="<?= htmlspecialchars($s['section']) ?>"></td>
                    <td><input type="text" name="contact" value="<?= htmlspecialchars($s['contact']) ?>"></td>
                    <td>
                        <input type="hidden" name="student_id" value="<?= $s['student_id'] ?>">
                        <input type="password" name="password" placeholder="Password">
                        <button type="submit" name="edit_student">Save</button>
                        <button type="submit" name="delete_student" onclick="return confirm('Delete this student?')">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Faculty Tab -->
    <section id="faculty" class="tab-content" style="display:none;">
        <h3>Create New Faculty</h3>
        <form method="POST" class="form-inline">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="department" placeholder="Department">
            <input type="text" name="designation" placeholder="Designation">
            <input type="text" name="contact" placeholder="Contact">
            <button type="submit" name="create_faculty">Create</button>
        </form>

        <h3>Existing Faculty</h3>
        <table>
            <tr>
                <th>Name</th><th>Email</th><th>Dept</th><th>Designation</th><th>Contact</th><th>Actions</th>
            </tr>
            <?php while($f = $faculty->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><input type="text" name="name" value="<?= htmlspecialchars($f['name']) ?>"></td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($f['email']) ?>"></td>
                    <td><input type="text" name="department" value="<?= htmlspecialchars($f['department']) ?>"></td>
                    <td><input type="text" name="designation" value="<?= htmlspecialchars($f['designation']) ?>"></td>
                    <td><input type="text" name="contact" value="<?= htmlspecialchars($f['contact']) ?>"></td>
                    <td>
                        <input type="hidden" name="faculty_id" value="<?= $f['faculty_id'] ?>">
                        <input type="password" name="password" placeholder="Password">
                        <button type="submit" name="edit_faculty">Save</button>
                        <button type="submit" name="delete_faculty" onclick="return confirm('Delete this faculty?')">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Admin Tab -->
    <section id="admins" class="tab-content" style="display:none;">
        <h3>Create New Admin</h3>
        <form method="POST" class="form-inline">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="superadmin">Super Admin</option>
                <option value="moderator">Moderator</option>
                <option value="staff" selected>Staff</option>
            </select>
            <button type="submit" name="create_admin">Create</button>
        </form>

        <h3>Existing Admins</h3>
        <table>
            <tr>
                <th>Username</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
            <?php while($a = $admins->fetch_assoc()): ?>
            <tr>
                <form method="POST">
                    <td><input type="text" name="username" value="<?= htmlspecialchars($a['username']) ?>"></td>
                    <td><input type="email" name="email" value="<?= htmlspecialchars($a['email']) ?>"></td>
                    <td>
                        <select name="role">
                            <option value="superadmin" <?= $a['role']=='superadmin'?'selected':'' ?>>Super Admin</option>
                            <option value="moderator" <?= $a['role']=='moderator'?'selected':'' ?>>Moderator</option>
                            <option value="staff" <?= $a['role']=='staff'?'selected':'' ?>>Staff</option>
                        </select>
                    </td>
                    <td>
                        <input type="hidden" name="admin_id" value="<?= $a['admin_id'] ?>">
                        <input type="password" name="password" placeholder="Password">
                        <button type="submit" name="edit_admin">Save</button>
                        <button type="submit" name="delete_admin" onclick="return confirm('Delete this admin?')">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</main>

<script>
function showTab(tabId){
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(t => t.style.display='none');
    document.getElementById(tabId).style.display='block';

    const btns = document.querySelectorAll('.tab-button');
    btns.forEach(b => b.classList.remove('active'));
    event.currentTarget.classList.add('active');
}
</script>

<?php include '../includes/footer.php'; ?>
