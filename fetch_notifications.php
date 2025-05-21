<?php
$conn = new mysqli("localhost", "root", "", "bupops_db");
$result = $conn->query("SELECT COUNT(*) AS notifications FROM notifications WHERE status = 'unread'");
$row = $result->fetch_assoc();
echo $row['notifications'];
?>
