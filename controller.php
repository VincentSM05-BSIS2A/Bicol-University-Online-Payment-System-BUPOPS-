<?php
session_start();
require_once('vendor/autoload.php');
use GuzzleHttp\Client;

// Database credentials
$host = 'localhost';
$dbname = 'bupops_db';
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['notif_id']) && isset($_POST['pin_notif'])) {
    $id = $_POST['notif_id'];
    $query = "UPDATE notifications SET is_pinned = NOT is_pinned WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

// 🔽 Handle notification delete
if (isset($_POST['notif_id']) && isset($_POST['delete_notif'])) {
    $id = $_POST['notif_id'];
    $query = "DELETE FROM notifications WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'], $_POST['description'], $_POST['category_id'])) {
    $amount = (int) $_POST['amount'];
    $description = $_POST['description'] ?? 'No description';
    $remarks = $_POST['remarks'] ?? '';
    $category_id = (int) $_POST['category_id'];

    $amountInCents = $amount * 100;

    $client = new Client();

    try {
        $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
            'body' => json_encode([
                'data' => [
                    'attributes' => [
                        'amount' => $amountInCents,
                        'description' => $description,
                        'remarks' => $remarks
                    ]
                ]
            ]),
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic c2tfdGVzdF9kUUtTRXhMU2NxejJ0VWVkdHA1cENodng6',
                'content-type' => 'application/json',
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        $checkoutUrl = $body['data']['attributes']['checkout_url'];

        // Ensure the user_id is set and valid
        $user_id = $_SESSION['user_id'] ?? 0;

        // Validate that the user_id exists in the users table
        if ($user_id <= 0) {
            echo "Error: Invalid or missing user ID.";
            exit();  // Prevent further execution
        }

        $user_check_query = "SELECT id FROM users WHERE id = ? AND role = 'user'";
        $stmt = $conn->prepare($user_check_query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            echo "Error: User not found or not a valid user.";
            exit();  // Prevent further execution
        }

        $name = $_SESSION['name'] ?? '';
        $email = $_SESSION['email'] ?? '';
        $year_course = $_SESSION['year_course'] ?? '';
        $section = $_SESSION['section'] ?? '';
        $method = 'pending';

        $payment_query = "INSERT INTO payments (user_id, category_id, name, email, year_course, section, method, amount, status, description, remarks, checkout_url) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?)";
        $stmt = $conn->prepare($payment_query);
        $stmt->bind_param("iisssssisss", $user_id, $category_id, $name, $email, $year_course, $section, $method, $amount, $description, $remarks, $checkoutUrl);
        
        if ($stmt->execute()) {
            header("Location: " . $checkoutUrl);
            exit();
        } else {
            echo "Error storing payment record: " . $stmt->error;
            exit();
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    // Admin status update
    $paymentId = (int) $_POST['id'];
    $status = $_POST['status'];

    if ($paymentId > 0 && in_array($status, ['pending', 'completed', 'failed'])) {
        $update_query = "UPDATE payments SET status = '$status' WHERE id = '$paymentId'";
        if (mysqli_query($conn, $update_query)) {
            $history_query = "INSERT INTO transaction_history (payment_id, status, changed_at) VALUES ('$paymentId', '$status', NOW())";
            if (mysqli_query($conn, $history_query)) {
                echo json_encode(['success' => true, 'message' => 'Payment status updated and history logged.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to log transaction history.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update payment status.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid payment ID or status.']);
    }

} else {
    // Render form (GET request)
    $categories = [];
    $category_query = "SELECT id, category_name, amount FROM payment_categories";
    $result = mysqli_query($conn, $category_query);

   $selected_category_id = isset($_GET['due_id']) ? (int) $_GET['due_id'] : 0;
$preselected_amount = '';
$preselected_description = '';

// If amount and description are passed via GET, use those instead of DB defaults
if (isset($_GET['amount'])) {
    $preselected_amount = (float) $_GET['amount'];
}

if (isset($_GET['description'])) {
    $preselected_description = $_GET['description'];
}

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
        // Only set description and amount from DB if not overridden by GET params
        if ($row['id'] == $selected_category_id) {
            if ($preselected_amount === '') {
                $preselected_amount = $row['amount'];
            }
            if ($preselected_description === '') {
                $preselected_description = $row['category_name'];
            }
        }
    }
}
 else {
        echo "Error fetching categories: " . mysqli_error($conn);
        exit();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Create Payment Link</title>
        <style>
            body {
    background: url('assets/background_2.jpg') no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    font-family: 'Arial', sans-serif;
    margin: 0;
}
            .container {
    background: rgba(255, 255, 255, 0.90); /* slightly transparent */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    width: 350px;
    text-align: center;
}

            h2 {
                margin-bottom: 20px;
                color: #333;
            }
            label {
                display: block;
                margin: 10px 0 5px;
                text-align: left;
                color: #555;
                font-size: 14px;
            }
            input[type="number"],
            input[type="text"],
            select {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 8px;
                font-size: 14px;
            }
            input:focus,
            select:focus {
                border-color: #007BFF;
                outline: none;
            }
            button {
                background-color:rgb(14, 148, 99);
                color: white;
                padding: 10px 15px;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                cursor: pointer;
                width: 100%;
            }
            button:hover {
                background-color:rgb(6, 93, 63);
            }
        </style>
    </head>
    <body>
    <div class="container">
        <div style="margin-bottom: 20px;">
    <img src="assets/paymongo_logo.png" alt="BUPOPS Logo" style="height: 50px; display: block; margin: 0 auto 10px;">
    <h2 style="margin: 0; font-size: 20px; color: #2c3e50;">Bicol University Polangui</h2>
    <p style="margin: 0; font-size: 14px; color: #555;">Online Payment Portal</p>
</div>

        <form method="POST" action="">
            <label for="category_id">Payment Category</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id']; ?>" <?= $cat['id'] == $selected_category_id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="amount">Amount (₱)</label>
            <input type="number" name="amount" id="amount" class="form-control" value="<?= $preselected_amount ?>" required>

            <label for="description">Method</label>
            <input type="text" name="description" id="description" class="form-control" value="<?= htmlspecialchars($preselected_description) ?>">
            <label for="remarks">Remarks (optional)</label>
            <input type="text" name="remarks" id="remarks" class="form-control">

            <button type="submit" class="btn btn-primary">Continue to Payment</button>
            <p style="font-size: 12px; color: #666; margin-top: 15px; line-height: 1.4;">
    <strong>Privacy Disclaimer:</strong> By proceeding with this payment, you consent to the collection and processing of your personal information in accordance with Bicol University Polangui’s data privacy policies. All information provided will be handled securely and used solely for payment processing purposes via our trusted payment partner, PayMongo. Please ensure that the details you submit are accurate and complete. Transactions are final and non-refundable.
</p>

        </form>
    </div>
    </body>
    </html>

<?php } ?>
