<?php
// Database configuration
$host = 'localhost';
$dbname = 'web_app';
$username = 'root';
$password = '';

// Connect to database using mysqli
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>