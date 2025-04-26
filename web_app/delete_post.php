<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if post_id is provided
if (!isset($_GET['post_id'])) {
    $_SESSION['error'] = "No post specified.";
    header("Location: profile.php");
    exit();
}

$post_id = $_GET['post_id'];

// Delete post
$query = "DELETE FROM posts WHERE id = $post_id";
if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = "Post deleted successfully.";
    header("Location: profile.php");
    exit();
} else {
    $_SESSION['error'] = "Failed to delete post.";
    header("Location: profile.php");
    exit();
}
?>