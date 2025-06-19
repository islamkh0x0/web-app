<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';

    if (!$email) {
        $_SESSION['error'] = "Email is required.";
        header("Location: forgot_password.php");
        exit();
    }

    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $token = md5($email . time());

        $delete_old = "DELETE FROM password_resets WHERE email = '$email'";
        mysqli_query($conn, $delete_old);

        $query = "INSERT INTO password_resets (email, token, created_at) VALUES ('$email', '$token', NOW())";
        mysqli_query($conn, $query);

        $_SESSION['success'] = "A password reset link has been sent to your email.";
        $_SESSION['info'] = "Use the token from your email in the reset form.";

        header("Location: reset_password.php");
        exit();
    } else {
        $_SESSION['error'] = "Email not found.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
