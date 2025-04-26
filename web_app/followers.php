<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID to display (own profile or another user's)
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

// Get user info
$query = "SELECT username FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
if (!$user) {
    $_SESSION['error'] = "User not found.";
    header("Location: profile.php");
    exit();
}

// Get followers
$query = "SELECT u.id, u.username FROM users u JOIN followers f ON u.id = f.follower_id WHERE f.followed_id = $user_id";
$followers_result = mysqli_query($conn, $query);

// Get following
$query = "SELECT u.id, u.username FROM users u JOIN followers f ON u.id = f.followed_id WHERE f.follower_id = $user_id";
$following_result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Followers - <?php echo $user['username']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><?php echo $user['username']; ?>'s Followers</h2>
        
        <!-- Display Followers -->
        <h3>Followers</h3>
        <?php
        if (mysqli_num_rows($followers_result) > 0) {
            while ($follower = mysqli_fetch_assoc($followers_result)) {
                echo "<p><a href='profile.php?user_id=" . $follower['id'] . "'>" . $follower['username'] . "</a></p>";
            }
        } else {
            echo "<p>No followers yet.</p>";
        }
        ?>

        <!-- Display Following -->
        <h3>Following</h3>
        <?php
        if (mysqli_num_rows($following_result) > 0) {
            while ($following = mysqli_fetch_assoc($following_result)) {
                echo "<p><a href='profile.php?user_id=" . $following['id'] . "'>" . $following['username'] . "</a></p>";
            }
        } else {
            echo "<p>Not following anyone yet.</p>";
        }
        ?>

        <div class="links">
            <a href="profile.php?user_id=<?php echo $user_id; ?>">Back to Profile</a> | 
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>