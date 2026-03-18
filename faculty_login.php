<?php
include 'includes/header.php';
if(session_status() == PHP_SESSION_NONE) session_start();
include 'includes/db.php';

$error = '';
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM faculty WHERE email=? AND password=? LIMIT 1");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['faculty_id'];
        $_SESSION['role'] = 'faculty';
        $_SESSION['name'] = $user['name'];
        header("Location: dashboards/faculty_dashboard.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<main>
    <section class="login-section">
        <div class="login-container">
            <i class="fa-solid fa-chalkboard-teacher fa-5x login-icon"></i>
            <h2>Faculty Login</h2>
            <?php if($error): ?>
                <p class="error-msg"><?php echo $error; ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
            <p class="forgot-pass"><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
