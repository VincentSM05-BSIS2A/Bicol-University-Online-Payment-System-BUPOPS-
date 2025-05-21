<?php
require_once __DIR__ . '/config/db.php';  // if one folder up
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access!");
}


if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // sanitize input, convert to integer (important!)

    $query = "DELETE FROM payments WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo "success"; // delete success
    } else {
        echo "error: " . mysqli_error($conn); // show error
    }
} else {
    echo "No ID received.";
}
?>
