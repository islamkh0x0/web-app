<?php
session_start();
require_once 'config.php';

// Get username for logged-in user 
$username = isset($_SESSION['user_id']) ? $_SESSION['username'] : '';
$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Hamburger Menu Styles */
        .nav-container {
            position: relative;
        }
        .hamburger {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            z-index: 1000;
        }
        .hamburger div {
            width: 30px;
            height: 3px;
            background-color: #333;
            margin: 5px 0;
        }
        .nav-menu {
            display: none;
            position: absolute;
            top: 50px;
            right: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 999;
        }
        .nav-menu.active {
            display: block;
        }
        .nav-menu a {
            display: block;
            padding: 5px;
            text-decoration: none;
            color: #333;
        }
        .nav-menu a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="nav-container">
        <!-- Hamburger Icon -->
        <div class="hamburger" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <!-- Navigation Menu -->
        <div class="nav-menu" id="navMenu">
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="blog.php">Blog</a>
                <a href="followers.php">Followers</a>
                <?php if ($is_admin) { ?>
                    <a href="admin_panel.php">Admin Panel</a>
                <?php } ?>
                <a href="logout.php">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <a href="blog.php">Blog</a>
            <?php } ?>
        </div>
    </div>

    <script>
        
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');

            
            const username = '<?php echo $username; ?>';
            if (username && menu.classList.contains('active')) {
                menu.innerHTML = `<a href='profile.php'>Profile (${username})</a>` + menu.innerHTML;
            }
        }
    </script>
</body>
</html>