```php
<?php
require_once 'config.php';

// Fetch all users
$query = "SELECT id, password FROM users";
$result = mysqli_query($conn, $query);

while ($user = mysqli_fetch_assoc($result)) {
    // Check if password is not hashed (assuming hashed passwords are longer than 20 chars)
    if (strlen($user['password']) < 60) {
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = {$user['id']}";
        mysqli_query($conn, $update_query);
    }
}

echo "Passwords updated successfully!";
?>
```