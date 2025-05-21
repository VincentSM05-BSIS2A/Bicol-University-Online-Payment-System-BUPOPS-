<?php
session_start();
include('../database/connection.php');

// Validate session
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

$user_id = $_SESSION['user_id'];

// Check pending payments
$pending_payments = $conn->query("SELECT * FROM payments WHERE status = 'pending' AND user_id = $user_id");
while($payment = $pending_payments->fetch_assoc()) {
    $payment_age = time() - strtotime($payment['created_at']);
    
    if ($payment_age > 3600) {
        // Update payment status
        $conn->query("UPDATE payments SET status = 'failed' WHERE id = {$payment['id']}");
        
        // Create notification
        $message = "Payment ID: {$payment['id']} failed (timeout)";
        $stmt = $conn->prepare("INSERT INTO notifications (message, recipient_email, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $message, $_SESSION['email']);
        $stmt->execute();
    }
}

// Fetch transactions
$stmt = $conn->prepare("SELECT p.id, p.amount, p.status, p.created_at, r.id AS receipt_id, pc.category_name
    FROM payments p
    LEFT JOIN receipts r ON p.id = r.payment_id
    LEFT JOIN payment_categories pc ON p.category_id = pc.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

echo json_encode($transactions);
?>