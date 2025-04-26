<?php
session_start();
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Reset Your Password</h2>
        <?php if (isset($_SESSION['success'])) { ?>
            <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
            <?php if (isset($_SESSION['debug_message'])) { ?>
                <p style="color: blue;">Debug Message: <?php echo htmlspecialchars($_SESSION['debug_message']); unset($_SESSION['debug_message']); ?></p>
            <?php } ?>
        <?php } ?>
        <?php if (isset($_SESSION['error'])) { ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php } ?>
        <form action="forgot_password_process.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Send Reset Link</button>
            <div class="links">
                <a href="login.php">Back to Login</a>
            </div>
        </form>
    </div>
</body>
</html>