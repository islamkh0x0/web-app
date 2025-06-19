<?php
session_start();
require_once 'config.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    $_SESSION['error'] = "Invalid reset link.";
    header("Location: forgot_password.php");
    exit();
}

// تحقق إن التوكن موجود في الداتا بيز
$query = "SELECT * FROM password_resets WHERE token = '$token'";
$result = mysqli_query($conn, $query);
$resetRequest = mysqli_fetch_assoc($result);

if (!$resetRequest) {
    $_SESSION['error'] = "Reset token not found or expired.";
    header("Location: forgot_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Reset Password</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form action="reset_password_process_mail.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Reset Password</button>
        <div class="links">
            <a href="login.php">Back to Login</a>
        </div>
    </form>
</div>
</body>
</html>
