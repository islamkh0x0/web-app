<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user is admin
$user_id = $_SESSION['user_id'];
$query = "SELECT is_admin FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user || $user['is_admin'] != 1) {
    $_SESSION['error'] = "Access denied. Admins only.";
    header("Location: profile.php");
    exit();
}

// Get all users
$query = "SELECT id, username, email, is_admin FROM users";
$users_result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p style="color: green;">' . $_SESSION['success'] . '</p>';
            unset($_SESSION['success']);
        }
        ?>
        <h3>Manage Users</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Admin Status</th>
                <th>Actions</th>
            </tr>
            <?php
            while ($user = mysqli_fetch_assoc($users_result)) {
                echo "<tr>";
                echo "<td>" . $user['id'] . "</td>";
                echo "<td>" . $user['username'] . "</td>";
                echo "<td>" . $user['email'] . "</td>";
                echo "<td>" . ($user['is_admin'] ? 'Admin' : 'User') . "</td>";
                echo "<td>";
                echo "<a href='toggle_admin.php?user_id=" . $user['id'] . "'>" . ($user['is_admin'] ? 'Remove Admin' : 'Make Admin') . "</a> | ";
                echo "<a href='delete_user.php?user_id=" . $user['id'] . "'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <div class="links">
            <a href="profile.php">Back to Profile</a> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>