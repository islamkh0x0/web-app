<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = 'my-super-secret-key-123';

function verifyJWT() {
    global $secret_key;

    // ✅ Read token from cookie
    $jwt = $_COOKIE['jwt'] ?? '';

    if (!$jwt) {
        http_response_code(401);
        echo json_encode(['message' => 'No token provided']);
        exit;
    }

    try {
        $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
        return (array) $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['message' => 'Invalid token']);
        exit;
    }
}
