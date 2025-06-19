<?php
require_once 'config.php'; // No session_start() needed here, header.php has it
include 'header.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
    exit();
}

// Check for Authorization Header
if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Authentication required.";
    exit();
}

$username = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

// Vulnerable to SQL Injection
$query = "SELECT id, username, is_admin FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['is_admin'] = $user['is_admin'];
    header("Location: profile.php");
    exit();
} else {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Invalid username or password.";
    exit();
}
?>