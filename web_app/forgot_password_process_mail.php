<?php
session_start();
require_once 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $query = "INSERT INTO password_resets (email, token, created_at) VALUES ('$email', '$token', NOW())";
        mysqli_query($conn, $query);

        // make a Message
        $resetLink = "http://localhost/web_app/reset_password_mail.php?token=$token";

        //Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'mail@gmail.com'; 
            $mail->Password   = 'app_password';   
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('email@gmail.com', 'MyApp'); 
            $mail->addAddress($email); 
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Link';
            $mail->Body    = "Click the link below to reset your password:<br><br>
            <a href='$resetLink'>$resetLink</a>";

            $mail->send();
            $_SESSION['success'] = "Reset link sent to your email.";
            header("Location: forgot_password.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = "Email could not be sent. Error: " . $mail->ErrorInfo;
            header("Location: forgot_password.php");
            exit();
        }

    } else {
        $_SESSION['error'] = "Email not found.";
        header("Location: forgot_password.php");
        exit();
    }
} else {
    header("Location: forgot_password.php");
    exit();
}
