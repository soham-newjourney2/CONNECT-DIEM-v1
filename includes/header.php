<?php
if(session_status() == PHP_SESSION_NONE) session_start();

// Set safe role for dashboard link
$role = $_SESSION['role'] ?? '';
if(!in_array($role, ['student','faculty','admin'])) $role = '';

// Root path of your project
define('ROOT_PATH', '/connect/'); // adjust if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connect - Institute of Engineering & Management</title>
    <link rel="stylesheet" href="<?php echo ROOT_PATH; ?>assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="<?php echo ROOT_PATH; ?>assets/js/main.js" defer></script>
    <link rel="icon" href="assets/css/jil.i.jpg">
</head>

<body class="light-mode">
<header>
    <div class="container header-container">
        <div class="logo">
            <a href="<?php echo ROOT_PATH; ?>index.php">Connect-Diem</a>
        </div>

        <nav class="nav">
            <ul class="nav-links">
                <li><a href="<?php echo ROOT_PATH; ?>index.php">Home</a></li>
            
                
                

                <?php if(isset($_SESSION['user_id']) && !empty($role)): ?>
                    <li><a href="<?php echo ROOT_PATH; ?>dashboards/<?php echo $role; ?>_dashboard.php">Dashboard</a></li>
                    <li><a href="<?php echo ROOT_PATH; ?>logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="dropdown">
                        <a href="#">Login ▼</a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo ROOT_PATH; ?>student_login.php">Student</a></li>
                            <li><a href="<?php echo ROOT_PATH; ?>faculty_login.php">Faculty</a></li>
                            <li><a href="<?php echo ROOT_PATH; ?>admin_login.php">Admin</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="header-right">
            <div class="dark-mode-toggle">
                <button id="modeToggle">🌙</button>
            </div>
            <div class="mobile-menu-toggle" id="mobileMenuToggle">
                ☰
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <ul>
            <li><a href="<?php echo ROOT_PATH; ?>index.php">Home</a></li>
            <li><a href="<?php echo ROOT_PATH; ?>about.php">About</a></li>
            <li><a href="<?php echo ROOT_PATH; ?>events.php">Events</a></li>
            <li><a href="<?php echo ROOT_PATH; ?>resources.php">Resources</a></li>
            <?php if(isset($_SESSION['user_id']) && !empty($role)): ?>
                <li><a href="<?php echo ROOT_PATH; ?>dashboards/<?php echo $role; ?>_dashboard.php">Dashboard</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="<?php echo ROOT_PATH; ?>student_login.php">Student Login</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>faculty_login.php">Faculty Login</a></li>
                <li><a href="<?php echo ROOT_PATH; ?>admin_login.php">Admin Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>
