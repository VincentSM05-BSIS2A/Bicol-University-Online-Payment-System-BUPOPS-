<?php
session_start();
include('database/connection.php'); // now correctly points to bupops/database/connection.php

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all transactions for this user
$sql = "
    SELECT 
        p.id,
        p.amount,
        p.status,
        p.created_at,
        r.id AS receipt_id
    FROM payments p
    LEFT JOIN receipts r 
      ON p.id = r.payment_id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Transaction History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      body { background: #f8f9fa; }
      .badge { font-size: .9em; }
      /* Green theme colors */
      .table-success {
        --bs-table-bg: #198754;
        --bs-table-striped-bg: #157347;
        --bs-table-striped-color: #fff;
        --bs-table-active-bg: #146c43;
        --bs-table-active-color: #fff;
        --bs-table-hover-bg: #157347;
        --bs-table-hover-color: #fff;
        color: #fff;
        border-color: #146c43;
      }
      .btn-outline-success {
        border-color: #198754;
        color: #198754;
      }
      .btn-outline-success:hover {
        background-color: #198754;
        color: white;
      }
    </style>
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="text-success">All My Transactions</h3>
      <a href="./pages/dashboard.php" class="btn btn-outline-success">
        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-success">
          <tr>
            <th scope="col">Transaction ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Status</th>
            <th scope="col">Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): 
            switch ($row['status']) {
              case 'completed': $badge = 'success'; break;
              case 'failed':    $badge = 'danger';  break;
              case 'pending':   $badge = 'warning'; break;
              default:          $badge = 'secondary';
            }
          ?>
          <tr>
            <td>
              <?= $row['receipt_id']
                  ? 'R-' . htmlspecialchars($row['receipt_id'])
                  : 'P-' . htmlspecialchars($row['id']); ?>
            </td>
            <td>₱<?= number_format($row['amount'], 2) ?></td>
            <td>
              <span class="badge bg-<?= $badge ?>">
                <?= ucfirst(htmlspecialchars($row['status'])) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>
</html>
</html>
