<?php
session_start();
include('../database/connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
}

// Validate CSRF token
if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    exit('CSRF token validation failed');
}

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit('Invalid email');
}

// Mark notifications as read
$stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE recipient_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

echo json_encode(['success' => true]);