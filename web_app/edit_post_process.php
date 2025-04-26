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
    $post_id = $_POST['post_id'];
    $content = $_POST['content'];

    // No input validation
    if (!$post_id || !$content) {
        $_SESSION['error'] = "Post ID and content are required.";
        header("Location: edit_post.php?post_id=$post_id");
        exit();
    }

    // Vulnerable to SQL Injection
    $query = "UPDATE posts SET content = '$content' WHERE id = $post_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Post updated successfully.";
        header("Location: profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update post.";
        header("Location: edit_post.php?post_id=$post_id");
        exit();
    }
} else {
    header("Location: profile.php");
    exit();
}
?>