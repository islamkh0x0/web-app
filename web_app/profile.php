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

// Get user posts
$query = "SELECT id, content, created_at FROM posts WHERE user_id = $user_id ORDER BY created_at DESC";
$posts_result = mysqli_query($conn, $query);

// Get followers count
$query = "SELECT COUNT(*) as follower_count FROM followers WHERE followed_id = $user_id";
$follower_result = mysqli_query($conn, $query);
$follower_count = mysqli_fetch_assoc($follower_result)['follower_count'];

// Get following count
$query = "SELECT COUNT(*) as following_count FROM followers WHERE follower_id = $user_id";
$following_result = mysqli_query($conn, $query);
$following_count = mysqli_fetch_assoc($following_result)['following_count'];

// Check if current user is following this user
$is_following = false;
if ($user_id != $_SESSION['user_id']) {
    $query = "SELECT * FROM followers WHERE follower_id = {$_SESSION['user_id']} AND followed_id = $user_id";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $is_following = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><?php echo $user['username']; ?>'s Profile</h2>
        <p>Followers: <?php echo $follower_count; ?> | Following: <?php echo $following_count; ?></p>
        
        <!-- Follow/Unfollow button -->
        <?php if ($user_id != $_SESSION['user_id']) { ?>
            <p>
                <?php if ($is_following) { ?>
                    <a href="follow_process.php?user_id=<?php echo $user_id; ?>&action=unfollow">Unfollow</a>
                <?php } else { ?>
                    <a href="follow_process.php?user_id=<?php echo $user_id; ?>&action=follow">Follow</a>
                <?php } ?>
            </p>
        <?php } ?>

        <!-- Form to add a new post -->
        <?php if ($user_id == $_SESSION['user_id']) { ?>
            <form action="post_process.php" method="POST">
                <div class="form-group">
                    <label for="content">New Post</label>
                    <textarea id="content" name="content" rows="4" style="width: 100%;" required></textarea>
                </div>
                <button type="submit">Post</button>
            </form>
        <?php } ?>

        <!-- Display posts -->
        <h3>Posts</h3>
        <?php
        while ($post = mysqli_fetch_assoc($posts_result)) {
            echo "<div>";
            echo "<p>" . $post['content'] . "<br><small>" . $post['created_at'] . "</small></p>";
            if ($user_id == $_SESSION['user_id']) {
                echo "<a href='edit_post.php?post_id=" . $post['id'] . "'>Edit</a> | ";
                echo "<a href='delete_post.php?post_id=" . $post['id'] . "'>Delete</a>";
            }
            echo "</div>";
        }
        ?>
        <div class="links">
            <a href="blog.php">Blog</a> | 
            <a href="followers.php?user_id=<?php echo $user_id; ?>">View Followers</a> | 
            <a href="profile.php">My Profile</a> | 
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) { ?>
                <a href="admin_panel.php">Admin Panel</a> | 
            <?php } ?>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>