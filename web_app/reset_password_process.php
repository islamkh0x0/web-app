<?php
session_start();
require_once 'config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];

    
    if (!$token || !$password) {
        $_SESSION['error'] = "Token and password are required.";
        header("Location: reset_password.php");
        exit();
    }

    
    $query = "SELECT * FROM password_resets WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    $reset = mysqli_fetch_assoc($result);

    if ($reset) {
        
        $email = $reset['email'];
        $query = "UPDATE users SET password = '$password' WHERE email = '$email'";
        if (mysqli_query($conn, $query)) {
            // Delete token after use
            $query = "DELETE FROM password_resets WHERE token = '$token'";
            mysqli_query($conn, $query);
            $_SESSION['success'] = "Password reset successfully.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to reset password.";
            header("Location: reset_password.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: reset_password.php");
        exit();
    }
} else {
    header("Location: reset_password.php");
    exit();
}
?>