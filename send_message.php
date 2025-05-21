<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bupops_db");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in with email and role
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

// Check if recipient and message are set
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['recipient']) || empty($_POST['message'])) {
        echo "Recipient or message not set.";
        exit();
    }

    // Sanitize inputs
    $sender = $_SESSION['email'];
    $recipient = $conn->real_escape_string(trim($_POST['recipient']));
    $message = $conn->real_escape_string(trim($_POST['message']));

    // Insert the notification
    $insert = "INSERT INTO notifications (message, sender_email, recipient_email, created_at, pinned) 
               VALUES ('$message', '$sender', '$recipient', NOW(), 0)";

    if (!$conn->query($insert)) {
        echo "Error: " . $conn->error;
        exit();
    }

    // Redirect based on user role
    if ($_SESSION['role'] === 'admin') {
        header("Location: /bupops/admin/admin_notifications.php");
    } elseif ($_SESSION['role'] === 'user') {
        header("Location: /bupops/pages/dashboard.php");
    } else {
        // Unknown role - fallback redirect
        header("Location: /bupops/index.php");
    }
    exit();
} else {
    // Invalid request method
    header("Location: /bupops/index.php");
    exit();
}
?>
