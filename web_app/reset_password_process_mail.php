<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    if (!$token || !$newPassword) {
        $_SESSION['error'] = "Token and password are required.";
        header("Location: forgot_password.php");
        exit();
    }

    // ابحث عن التوكن في جدول password_resets
    $token = mysqli_real_escape_string($conn, $token);
    $query = "SELECT * FROM password_resets WHERE token = '$token'";
    $result = mysqli_query($conn, $query);
    $resetRequest = mysqli_fetch_assoc($result);

    if (!$resetRequest) {
        $_SESSION['error'] = "Invalid or expired token.";
        header("Location: forgot_password.php");
        exit();
    }

    $email = mysqli_real_escape_string($conn, $resetRequest['email']);
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // حدّث كلمة السر للمستخدم في جدول users
    $updateQuery = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";
    mysqli_query($conn, $updateQuery);

    // امسح التوكن من جدول password_resets بعد الاستخدام
    $deleteQuery = "DELETE FROM password_resets WHERE token = '$token'";
    mysqli_query($conn, $deleteQuery);

    $_SESSION['success'] = "Password has been reset successfully. You can now log in.";
    header("Location: login.php");
    exit();
} else {
    header("Location: forgot_password.php");
    exit();
}
