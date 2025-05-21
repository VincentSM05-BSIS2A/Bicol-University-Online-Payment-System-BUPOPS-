<?php
$conn = new mysqli("localhost", "root", "", "bupops_db");
$result = $conn->query("SELECT COUNT(*) AS pending_fees FROM payments WHERE status = 'pending'");
$row = $result->fetch_assoc();
echo $row['pending_fees'];
?>
