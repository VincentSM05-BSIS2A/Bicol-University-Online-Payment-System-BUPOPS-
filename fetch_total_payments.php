<?php
$conn = new mysqli("localhost", "root", "", "bupops_db");
$result = $conn->query("SELECT SUM(amount) AS total_payments FROM payments WHERE status = 'completed'");
$row = $result->fetch_assoc();
echo "₱" . number_format($row['total_payments'], 2);
?>
