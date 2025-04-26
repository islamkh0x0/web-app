<?php
session_start();
require_once 'config.php';

// Check if user is admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Get user_id
$user_id = $_GET['user_id'];

// Start transaction to ensure data consistency
mysqli_begin_transaction($conn);

try {
    // Delete related records from followers 
    $query = "DELETE FROM followers WHERE follower_id = $user_id OR followed_id = $user_id";
    if (!mysqli_query($conn, $query)) {
        throw new Exception("Error deleting followers: " . mysqli_error($conn));
    }

    // Delete related records from posts 
    $query = "DELETE FROM posts WHERE user_id = $user_id";
    if (!mysqli_query($conn, $query)) {
        throw new Exception("Error deleting posts: " . mysqli_error($conn));
    }

    // Delete user from users 
    $query = "DELETE FROM users WHERE id = $user_id";
    if (!mysqli_query($conn, $query)) {
        throw new Exception("Error deleting user: " . mysqli_error($conn));
    }

    // Commit transaction
    mysqli_commit($conn);
    $_SESSION['success'] = "User deleted successfully.";
} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    $_SESSION['error'] = $e->getMessage();
}

header("Location: admin_panel.php");
exit();
?>