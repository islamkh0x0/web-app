<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    $_SESSION['error'] = "Access denied. Admins only.";
    header("Location: profile.php");
    exit();
}

// Check if user_id is provided
if (!isset($_GET['user_id'])) {
    $_SESSION['error'] = "No user specified.";
    header("Location: admin_panel.php");
    exit();
}

$user_id = $_GET['user_id'];

// Get current admin status
$query = "SELECT is_admin FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: admin_panel.php");
    exit();
}

// Toggle admin status
$new_status = $user['is_admin'] ? 0 : 1;
$query = "UPDATE users SET is_admin = $new_status WHERE id = $user_id";
if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = "User admin status updated.";
} else {
    $_SESSION['error'] = "Failed to update admin status.";
}

header("Location: admin_panel.php");
exit();
?>