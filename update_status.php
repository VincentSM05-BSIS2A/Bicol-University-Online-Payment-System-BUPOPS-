<?php
// Make sure to include database connection
$conn = new mysqli("localhost", "root", "", "bupops_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    // Validate inputs
    if ($paymentId > 0 && in_array($status, ['pending', 'completed', 'failed'])) {
        $stmt = $conn->prepare("UPDATE payments SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $paymentId);

        if ($stmt->execute()) {
            echo "Payment status updated successfully.";
        } else {
            echo "Error updating status.";
        }

        $stmt->close();
    } else {
        echo "Invalid input data.";
    }

    $conn->close();
}
?>
