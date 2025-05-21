<?php
session_start();
include __DIR__ . '/database/connection.php';  // adjust path as needed

// only students
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];

// 1. Required Payment Fees
$dues_sql = "
    SELECT pc.id, pc.category_name, pc.amount, pc.due_date
    FROM payment_categories pc
    WHERE NOT EXISTS (
      SELECT 1 FROM payments p
      WHERE p.user_id = ? 
        AND p.category_id = pc.id 
        AND p.status = 'completed'
    )
    ORDER BY pc.due_date ASC
";
$dues_stmt = $conn->prepare($dues_sql);
$dues_stmt->bind_param('i', $user_id);
$dues_stmt->execute();
$dues = $dues_stmt->get_result();

// 2. Processed Payments
$paid_sql = "
    SELECT pc.category_name, p.amount, p.status, p.created_at
    FROM payments p
    JOIN payment_categories pc ON p.category_id = pc.id
    WHERE p.user_id = ? AND p.status = 'completed'
    ORDER BY p.created_at DESC
";
$paid_stmt = $conn->prepare($paid_sql);
$paid_stmt->bind_param('i', $user_id);
$paid_stmt->execute();
$paid = $paid_stmt->get_result();

// 3. User Notifications
$notifs = $conn->query("SELECT * FROM notifications WHERE recipient_email = '$email' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>My Fees & Payments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
  <div class="container">
    <h2 class="mb-4">Your Required Payment Fees</h2>
    <?php if ($dues->num_rows): ?>
      <table class="table table-bordered mb-5">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Amount (₱)</th>
            <th>Due Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $dues->fetch_assoc()): ?>
            <tr>
              <td>D-<?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['category_name']) ?></td>
              <td>₱<?= number_format($row['amount'],2) ?></td>
              <td><?= date('M d, Y', strtotime($row['due_date'])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-success">No outstanding fees—good job!</div>
    <?php endif; ?>

    <h2 class="mb-4">Your Processed Payments</h2>
    <?php if ($paid->num_rows): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Category</th>
            <th>Amount (₱)</th>
            <th>Date Paid</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $paid->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['category_name']) ?></td>
              <td>₱<?= number_format($row['amount'],2) ?></td>
              <td><?= date('M d, Y h:i A', strtotime($row['created_at'])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-info">You haven’t completed any payments yet.</div>
    <?php endif; ?>

    <a href="pages/dashboard.php" class="btn btn-secondary mt-3">← Back to Dashboard</a>

    <!-- Messaging Section -->
    <hr class="my-5">
    <h2>Your Messages</h2>
    <?php if ($notifs->num_rows): ?>
      <?php while ($row = $notifs->fetch_assoc()): ?>
        <div style="background:#f1f1f1; padding:10px; margin:10px 0; border-left: 5px solid #0d6efd;">
          <strong>From:</strong> <?= htmlspecialchars($row['sender_email']) ?><br>
          <strong>Message:</strong> <?= htmlspecialchars($row['message']) ?><br>
          <small class="text-muted"><?= date('M d, Y h:i A', strtotime($row['created_at'])) ?></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-info">You have no messages yet.</div>
    <?php endif; ?>

    <!-- Send message to admin -->
    <button class="btn btn-primary mt-3" onclick="document.getElementById('msgModal').style.display='flex'">
        Message Admin
    </button>

    <!-- Message Modal -->
    <div class="modal" id="msgModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#000000aa; justify-content:center; align-items:center;">
        <div class="modal-content bg-white p-4 rounded shadow" style="max-width:500px; width:90%;">
            <h5 class="mb-3">Send a Message to Admin</h5>
            <form method="POST" action="send_message.php">
                <textarea name="message" class="form-control mb-3" placeholder="Enter your message" required></textarea>
                <input type="hidden" name="recipient" value="admin@bupops.edu"> <!-- update as needed -->
                <div class="text-end">
                    <button class="btn btn-success" type="submit">Send</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('msgModal').style.display='none'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
  </div>

  <script>
    // close modal if clicked outside
    window.onclick = function(e) {
      const modal = document.getElementById('msgModal');
      if (e.target === modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>
</html>
