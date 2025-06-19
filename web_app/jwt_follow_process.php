<?php
require_once 'config.php';
require 'jwt_middleware.php';

// Verify JWT
$user = verifyJWT();

// Get parameters
$user_id = $_GET['user_id'] ?? '';
$action = $_GET['action'] ?? '';

if (!$user_id || !in_array($action, ['follow', 'unfollow'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid request']);
    exit;
}

// Sanitize user_id
$user_id = mysqli_real_escape_string($conn, $user_id);
$current_user_id = mysqli_real_escape_string($conn, $user['user_id']);

// Prevent self-follow
if ($user_id == $current_user_id) {
    http_response_code(400);
    echo json_encode(['message' => 'Cannot follow yourself']);
    exit;
}

// Check if user exists
$query = "SELECT id FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    http_response_code(404);
    echo json_encode(['message' => 'User not found']);
    exit;
}

if ($action == 'follow') {
    // Check if already following
    $query = "SELECT * FROM followers WHERE follower_id = '$current_user_id' AND followed_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0) {
        $query = "INSERT INTO followers (follower_id, followed_id) VALUES ('$current_user_id', '$user_id')";
        mysqli_query($conn, $query);
    }
} elseif ($action == 'unfollow') {
    // Delete follow
    $query = "DELETE FROM followers WHERE follower_id = '$current_user_id' AND followed_id = '$user_id'";
    mysqli_query($conn, $query);
}

// Redirect back to profile
header("Location: jwt_profile.php?user_id=$user_id");
exit;
?>