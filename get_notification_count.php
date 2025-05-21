<?php
session_start();
include('../database/connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

$stmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE recipient_email = ? AND is_read = 0");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode(['count' => $result['count'] ?? 0]);