<?php
$conn = new mysqli("localhost", "root", "", "bupops_db");

$today = date('Y-m-d');
$soon = date('Y-m-d', strtotime('+3 days'));

$sql = "SELECT * FROM payments WHERE due_date <= ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $soon);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $email = $row['email'];
    $message = "📌 Your payment of ₱{$row['amount']} is due soon.";
    $conn->query("INSERT INTO notifications (email, message) VALUES ('$email', '$message')");
}
