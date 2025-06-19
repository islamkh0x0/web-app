<?php session_start(); ?>
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

        <?php if (isset($_SESSION['success'])) { ?>
            <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php } ?>

        <?php if (isset($_SESSION['info'])) { ?>
            <p style="color: blue;"><?php echo $_SESSION['info']; unset($_SESSION['info']); ?></p>
        <?php } ?>

        <?php if (isset($_SESSION['error'])) { ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php } ?>

        <form action="reset_password_process.php" method="POST">
            <div class="form-group">
                <label for="token">Enter your reset token</label>
                <input type="text" id="token" name="token" required>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
