<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if file was uploaded
if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] == UPLOAD_ERR_NO_FILE) {
    $_SESSION['upload_error'] = "No file was uploaded.";
    header("Location: profile.php");
    exit();
}

// Get file info
$file = $_FILES['profile_picture'];
$file_name = $file['name'];
$file_tmp = $file['tmp_name'];
$file_size = $file['size'];

// No validation for file type, size, or name (intentional vulnerabilities)
$upload_dir = 'uploads/';
$file_path = $upload_dir . basename($file_name);

// Move file to uploads directory
if (move_uploaded_file($file_tmp, $file_path)) {
    // Update user's profile picture path (vulnerable to SQL Injection)
    $query = "UPDATE users SET profile_picture = '$file_path' WHERE id = $user_id";
    mysqli_query($conn, $query);
    
    $_SESSION['upload_success'] = "Profile picture uploaded successfully.";
    header("Location: profile.php");
    exit();
} else {
    $_SESSION['upload_error'] = "Failed to upload file.";
    header("Location: profile.php");
    exit();
}
?>