<?php
// webhook_listener.php

require_once('vendor/autoload.php');

// Read PayMongo Webhook JSON body
$payload = file_get_contents('php://input');
$data = json_decode($payload, true);

// Get the needed info
$payments = $data['data']['attributes']['payments'] ?? [];

if (!empty($payments)) {
    foreach ($payments as $payment) {
        $payment_method = $payment['payment_method_details']['type'] ?? 'unknown'; // e.g., 'gcash', 'card'
        $checkout_id = $payment['attributes']['checkout_id'] ?? null;

        if ($checkout_id && $payment_method) {
            // Connect to the database
            $conn = mysqli_connect('localhost', 'root', '', 'bupops_db');
            
            if ($conn) {
                // Update the 'method' in payments table
                $update_query = "UPDATE payments SET method = '$payment_method', status = 'completed' WHERE id = '$checkout_id'";
                mysqli_query($conn, $update_query);
            }
        }
    }
}

http_response_code(200); // Always respond OK to PayMongo
?>
