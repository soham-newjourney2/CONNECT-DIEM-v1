<?php
// auth.php - Authentication and session management for Connect

session_start();
include_once 'db.php';

/**
 * Login function for any role
 * @param string $role - 'student', 'faculty', or 'admin'
 * @param string $emailOrUsername
 * @param string $password
 * @return bool
 */
function login($role, $emailOrUsername, $password) {
    global $conn;

    if ($role === 'student') {
        $stmt = $conn->prepare("SELECT * FROM students WHERE email=? LIMIT 1");
    } elseif ($role === 'faculty') {
        $stmt = $conn->prepare("SELECT * FROM faculty WHERE email=? LIMIT 1");
    } elseif ($role === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    } else {
        return false;
    }

    $stmt->bind_param("s", $emailOrUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // For now, plain password check (can replace with password_verify later)
        if ($user['password'] === $password) {
            // Set session variables
            $_SESSION['user_id'] = $user[$role.'_id'] ?? $user['admin_id'];
            $_SESSION['name'] = $user['name'] ?? $user['username'];
            $_SESSION['role'] = $role;
            return true;
        }
    }

    return false;
}

/**
 * Check if user is logged in
 * @param string $role
 * @return bool
 */
function is_logged_in($role = null) {
    if (!isset($_SESSION['user_id'])) return false;
    if ($role && $_SESSION['role'] !== $role) return false;
    return true;
}

/**
 * Logout function
 */
function logout() {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

/**
 * Redirect to login if not logged in
 * @param string|null $role
 */
function ensure_role($role = null) {
    if (!is_logged_in($role)) {
        if ($role === 'student') header("Location: ../student_login.php");
        elseif ($role === 'faculty') header("Location: ../faculty_login.php");
        elseif ($role === 'admin') header("Location: ../admin_login.php");
        else header("Location: ../index.php");
        exit();
    }
}
?>
