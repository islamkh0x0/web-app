<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'] ?? '';
    $new_password = $_POST['password'] ?? '';

    if (!$token || !$new_password) {
        $_SESSION['error'] = "Token and new password are required.";
        header("Location: reset_password.php");
        exit();
    }

    //token check
    $token = mysqli_real_escape_string($conn, $token);
    $query = "SELECT email FROM password_resets WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $email = $row['email'];
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // update pass
        $update_query = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
        mysqli_query($conn, $update_query);

        // delet after use
        $delete_query = "DELETE FROM password_resets WHERE token = '$token'";
        mysqli_query($conn, $delete_query);

        $_SESSION['success'] = "Password has been reset successfully.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: reset_password.php");
        exit();
    }
} else {
    header("Location: reset_password.php");
    exit();
}
