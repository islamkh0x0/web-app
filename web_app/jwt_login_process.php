<?php
require_once 'config.php';
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Secret Key
$secret_key = 'my-super-secret-key-123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['message' => 'Email and password are required']);
        exit;
    }

    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT id, username, email, password, is_admin FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $payload = [
            'user_id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'is_admin' => $user['is_admin'] ?? 0,
            'exp' => time() + 3600
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        // ✅ Set token as cookie (secure in production)
        setcookie('jwt', $jwt, time() + 3600, '/', '', false, true); // HttpOnly

        // Redirect to profile
        header('Location: jwt_profile.php');
        exit;
    } else {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid email or password']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
