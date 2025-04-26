<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if request is valid
if (isset($_GET['user_id']) && isset($_GET['action'])) {
    $follower_id = $_SESSION['user_id'];
    $followed_id = $_GET['user_id'];
    $action = $_GET['action'];
    
    if ($action == 'follow') {
        $query = "INSERT INTO followers (follower_id, followed_id) VALUES ($follower_id, $followed_id)";
        mysqli_query($conn, $query);
        $_SESSION['success'] = "You are now following this user.";
    } elseif ($action == 'unfollow') {
        $query = "DELETE FROM followers WHERE follower_id = $follower_id AND followed_id = $followed_id";
        mysqli_query($conn, $query);
        $_SESSION['success'] = "You have unfollowed this user.";
    }

    header("Location: profile.php?user_id=$followed_id");
    exit();
} else {
    header("Location: profile.php");
    exit();
}
?>