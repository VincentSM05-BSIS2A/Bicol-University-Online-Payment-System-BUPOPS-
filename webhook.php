<?php
// webhook.php

// Connect to your database
require_once('database/connection.php');

// Read the raw POST body
$payload = file_get_contents('php://input');

// Decode JSON into PHP array
$data = json_decode($payload, true);

// Log raw payload (optional for debugging)
// file_put_contents('webhook_log.txt', $payload, FILE_APPEND);

// Check if the webhook payload is valid
if (isset($data['data']['attributes']['status']) && isset($data['data']['id'])) {
    $status = $data['data']['attributes']['status']; // Example: 'paid' or 'expired'
    $payment_id = $data['data']['id']; // This is PayMongo's payment link ID

    // Default: no matching internal payment ID yet
    $internal_payment_id = null;

    // Optional: if you stored the PayMongo link ID into your database's `payment_reference` field
    $query = "SELECT id FROM payments WHERE payment_reference = '$payment_id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        $internal_payment_id = $row['id'];
    }

    if ($internal_payment_id) {
        // Determine new status
        if ($status === 'paid') {
            $new_status = 'completed';
        } elseif ($status === 'expired') {
            $new_status = 'failed';
        } else {
            // Unknown status, do nothing
            http_response_code(400);
            exit('Unknown status');
        }

        // Update payment status in the database
        $update = "UPDATE payments SET status = '$new_status' WHERE id = '$internal_payment_id'";
        mysqli_query($conn, $update);

        // Respond with success
        http_response_code(200);
        echo "Payment updated successfully.";
    } else {
        // Payment link not found
        http_response_code(404);
        echo "Payment reference not found.";
    }
} else {
    // Invalid payload
    http_response_code(400);
    echo "Invalid webhook payload.";
}
?>
