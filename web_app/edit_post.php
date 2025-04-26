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

// Get post info 
$query = "SELECT content FROM posts WHERE id = $post_id";
$result = mysqli_query($conn, $query);
$post = mysqli_fetch_assoc($result);
if (!$post) {
    $_SESSION['error'] = "Post not found.";
    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Post</h2>
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
        <form action="edit_post_process.php" method="POST">
            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
            <div class="form-group">
                <label for="content">Post Content</label>
                <textarea id="content" name="content" rows="4" style="width: 100%;" required><?php echo $post['content']; ?></textarea>
            </div>
            <button type="submit">Update Post</button>
            <div class="links">
                <a href="profile.php">Back to Profile</a>
            </div>
        </form>
    </div>
</body>
</html>