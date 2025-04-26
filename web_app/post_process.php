<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    
    if (!$content) {
        $_SESSION['error'] = "Post content is required.";
        header("Location: profile.php");
        exit();
    }

    
    $query = "INSERT INTO posts (user_id, content) VALUES ($user_id, '$content')";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Post added successfully.";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to add post.";
        header("Location: profile.php");
        exit();
    }
} else {
    header("Location: profile.php");
    exit();
}
?>