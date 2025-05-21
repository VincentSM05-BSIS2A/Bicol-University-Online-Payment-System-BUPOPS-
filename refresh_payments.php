<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "bupops_db");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// PayMongo secret key
$secretKey = 'sk_test_dQKSExLScqz2tUedtp5pChvx'; // <-- replace with your real secret key

// Fetch all payments with PayMongo IDs
$result = $conn->query("SELECT id, paymongo_payment_id FROM payments WHERE paymongo_payment_id IS NOT NULL");

if (!$result) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch payments"]);
    exit();
}

while ($row = $result->fetch_assoc()) {
    $paymentId = $row['paymongo_payment_id'];
    $localId = $row['id'];

    // Call PayMongo API to get the latest payment status
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/payments/{$paymentId}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $secretKey . ":");

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch) || $httpcode !== 200) {
        continue; // skip updating if error
    }

    $data = json_decode($response, true);

    if (isset($data['data']['attributes']['status'])) {
        $status = $data['data']['attributes']['status'];

        // Update the local database
        $stmt = $conn->prepare("UPDATE payments SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $localId);
        $stmt->execute();
    }

    curl_close($ch);
}

$conn->close();

echo json_encode(["success" => true]);
?>
