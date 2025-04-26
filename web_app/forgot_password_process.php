<?php
session_start();
require_once 'config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    
    if (!$email) {
        $_SESSION['error'] = "Email is required.";
        header("Location: forgot_password.php");
        exit();
    }

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        
        $token = md5($email . time()); 
        $query = "INSERT INTO password_resets (email, token, created_at) VALUES ('$email', '$token', NOW())";
        mysqli_query($conn, $query);

        
        $_SESSION['success'] = "Your reset token is: $token. Use it to reset your password.";
        header("Location: forgot_password.php");
        exit();
    } else {
        $_SESSION['error'] = "Email not found.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    header("Location: reset_password.php");
    exit();
}
?>