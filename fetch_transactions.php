<?php
session_start();
include('../database/connection.php'); 

$user_id = $_SESSION['user_id'];

// Fetch recent transactions with updated status
$transactions_query = "SELECT p.id, p.amount, p.status, p.created_at, r.id AS receipt_id 
                       FROM payments p
                       LEFT JOIN receipts r ON p.id = r.payment_id
                       WHERE p.user_id = '$user_id'
                       ORDER BY p.created_at DESC
                       LIMIT 5";
$transactions_result = mysqli_query($conn, $transactions_query);

// Generate the updated rows
while ($row = mysqli_fetch_assoc($transactions_result)) {
    echo '<tr>';
    echo '<td>' . ($row['receipt_id'] ? "R-" . $row['receipt_id'] : "P-" . $row['id']) . '</td>';
    echo '<td>₱' . number_format($row['amount'], 2) . '</td>';
    echo '<td>' . ucfirst($row['status']) . '</td>';
    echo '<td>' . $row['created_at'] . '</td>';
    echo '</tr>';
}
?>
