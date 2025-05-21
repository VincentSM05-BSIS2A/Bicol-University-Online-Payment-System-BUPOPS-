<?php
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'month'; // Default to 'month'

$startDate = '';
$endDate = '';

switch ($filter) {
    case 'day':
        $startDate = date('Y-m-d') . ' 00:00:00';
        $endDate = date('Y-m-d') . ' 23:59:59';
        break;
    case 'week':
        $startDate = date('Y-m-d', strtotime('monday this week')) . ' 00:00:00';
        $endDate = date('Y-m-d', strtotime('sunday this week')) . ' 23:59:59';
        break;
    case 'month':
        $startDate = date('Y-m-01') . ' 00:00:00';
        $endDate = date('Y-m-t') . ' 23:59:59';
        break;
    case 'year':
        $startDate = date('Y-01-01') . ' 00:00:00';
        $endDate = date('Y-12-31') . ' 23:59:59';
        break;
    default:
        $startDate = date('Y-m-01') . ' 00:00:00';
        $endDate = date('Y-m-t') . ' 23:59:59';
        break;
}

$conn = new mysqli("localhost", "root", "", "bupops_db");
$sql = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, SUM(amount) AS total
        FROM payments
        WHERE status = 'completed' AND created_at BETWEEN '$startDate' AND '$endDate'
        GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d')
        ORDER BY created_at";

$result = $conn->query($sql);

$data = [];
$labels = [];
$values = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['date'];
    $values[] = $row['total'];
}

echo json_encode(['labels' => $labels, 'values' => $values]);
?>
