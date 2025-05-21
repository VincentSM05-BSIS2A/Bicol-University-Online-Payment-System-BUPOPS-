<?php
$conn = new mysqli("localhost", "root", "", "bupops_db");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['category_name'];
    $amount = $_POST['amount'];

    if ($conn->query("INSERT INTO payment_categories (category_name, amount) VALUES ('$name', '$amount')")) {
        // Notify all users
        $users = $conn->query("SELECT bu_email FROM users");
        while ($user = $users->fetch_assoc()) {
            $email = $user['bu_email'];
            $message = "📌 New required fee: $name - ₱$amount.";
            $conn->query("INSERT INTO notifications (email, message) VALUES ('$email', '$message')");
        }

        // Redirect after success
        header("Location: admin_dashboard.php?category_added=1");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
