<?php
session_start();
include('../database/connection.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

$stmt = $conn->prepare("SELECT * FROM notifications 
    WHERE recipient_email = ? 
    ORDER BY created_at DESC 
    LIMIT 3");
$stmt->bind_param("s", $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

echo json_encode($notifications);
?>