<?php
require_once 'config.php';
require 'jwt_middleware.php';

// Verify JWT
$user = verifyJWT();

// Get user ID to display (own profile or another user's)
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $user['user_id'];

// Sanitize user_id
$user_id = mysqli_real_escape_string($conn, $user_id);

// Get user info
$query = "SELECT username, profile_picture FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);
if (!$user_data) {
    http_response_code(404);
    echo json_encode(['message' => 'User not found']);
    exit;
}

// Get user posts
$query = "SELECT id, content, created_at FROM posts WHERE user_id = '$user_id' ORDER BY created_at DESC";
$posts_result = mysqli_query($conn, $query);

// Get followers count
$query = "SELECT COUNT(*) as follower_count FROM followers WHERE followed_id = '$user_id'";
$follower_result = mysqli_query($conn, $query);
$follower_count = mysqli_fetch_assoc($follower_result)['follower_count'];

// Get following count
$query = "SELECT COUNT(*) as following_count FROM followers WHERE follower_id = '$user_id'";
$following_result = mysqli_query($conn, $query);
$following_count = mysqli_fetch_assoc($following_result)['following_count'];

// Check if current user is following this user
$is_following = false;
if ($user_id != $user['user_id']) {
    $current_user_id = mysqli_real_escape_string($conn, $user['user_id']);
    $query = "SELECT * FROM followers WHERE follower_id = '$current_user_id' AND followed_id = '$user_id'";
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
    <title>JWT Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2><?php echo htmlspecialchars($user_data['username']); ?>'s Profile</h2>
        
        <!-- Display Profile Picture -->
        <?php if ($user_data['profile_picture']) { ?>
            <img src="<?php echo htmlspecialchars($user_data['profile_picture']); ?>" alt="Profile Picture" style="max-width: 200px;">
        <?php } else { ?>
            <p>No profile picture uploaded.</p>
        <?php } ?>
        
        <!-- Upload Profile Picture Form (only for own profile) -->
        <?php if ($user_id == $user['user_id']) { ?>
            <h3>Upload Profile Picture</h3>
            <?php if (isset($_SESSION['upload_error'])) { ?>
                <p style="color: red;"><?php echo $_SESSION['upload_error']; unset($_SESSION['upload_error']); ?></p>
            <?php } ?>
            <?php if (isset($_SESSION['upload_success'])) { ?>
                <p style="color: green;"><?php echo $_SESSION['upload_success']; unset($_SESSION['upload_success']); ?></p>
            <?php } ?>
            <form action="upload_process.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="profile_picture">Choose Image</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                </div>
                <button type="submit">Upload</button>
            </form>
        <?php } ?>
        
        <p>Followers: <?php echo $follower_count; ?> | Following: <?php echo $following_count; ?></p>
        
        <!-- Follow/Unfollow button -->
        <?php if ($user_id != $user['user_id']) { ?>
            <p>
                <?php if ($is_following) { ?>
                    <a href="jwt_follow_process.php?user_id=<?php echo $user_id; ?>&action=unfollow">Unfollow</a>
                <?php } else { ?>
                    <a href="jwt_follow_process.php?user_id=<?php echo $user_id; ?>&action=follow">Follow</a>
                <?php } ?>
            </p>
        <?php } ?>

        <!-- Form to add a new post -->
        <?php if ($user_id == $user['user_id']) { ?>
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
            echo "<p>" . htmlspecialchars($post['content']) . "<br><small>" . $post['created_at'] . "</small></p>";
            if ($user_id == $user['user_id']) {
                echo "<a href='edit_post.php?post_id=" . $post['id'] . "'>Edit</a> | ";
                echo "<a href='delete_post.php?post_id=" . $post['id'] . "'>Delete</a>";
            }
            echo "</div>";
        }
        ?>
        <div class="links">
            <a href="blog.php">Blog</a> | 
            <a href="followers.php?user_id=<?php echo $user_id; ?>">View Followers</a> | 
            <a href="jwt_profile.php">My Profile</a> | 
            <?php if ($user['is_admin'] == 1) { ?>
                <a href="admin_panel.php">Admin Panel</a> | 
            <?php } ?>
            <a href="javascript:logout()">Logout</a>
        </div>
    </div>
    <script>
        // Logout function
        function logout() {
            // Delete JWT cookie
            document.cookie = 'jwt=; Max-Age=0; path=/';
            window.location.href = 'jwt_login.php';
        }
    </script>
</body>
</html>
