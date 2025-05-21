<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
 header('Location: ../../index.php');
 exit();
}

$email = $_SESSION['email'];
$conn = new mysqli("localhost", "root", "", "bupops_db");

// Count users in the system
$userCountResult = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$userCountRow = $userCountResult->fetch_assoc();
$totalUsers = $userCountRow['total_users'];

// Total payments collected
$totalPaymentsResult = $conn->query("SELECT SUM(amount) AS total_collected FROM payments WHERE status = 'completed'");
$totalCollected = $totalPaymentsResult->fetch_assoc()['total_collected'] ?? 0;

// Count of pending fees
$pendingFeesResult = $conn->query("SELECT COUNT(*) AS pending_fees FROM payments WHERE status = 'pending'");
$pendingFees = $pendingFeesResult->fetch_assoc()['pending_fees'];

// Recent notifications count and unread count
$notifResult = $conn->query("SELECT COUNT(*) AS total_notifs, SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) AS unread FROM notifications");
$notifData = $notifResult->fetch_assoc();
$totalNotifs = $notifData['total_notifs'];
$unreadNotifs = $notifData['unread'];

// Monthly payment data
$sql = "SELECT 
 DATE_FORMAT(created_at, '%M') AS month,
 SUM(amount) AS total
 FROM payments
 WHERE status = 'completed'
 GROUP BY MONTH(created_at)
 ORDER BY MONTH(created_at)";
$result = $conn->query($sql);
$months = [];
$totals = [];
while ($row = $result->fetch_assoc()) {
 $months[] = $row['month'];
 $totals[] = $row['total'];
}

// Payment status counts
$sql = "SELECT status, COUNT(*) AS count FROM payments GROUP BY status";
$result = $conn->query($sql);
$statuses = [];
$counts = [];
while ($row = $result->fetch_assoc()) {
 $statuses[] = ucfirst($row['status']);
 $counts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <title>BUPOPS - Admin</title>
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
 <style>
  :root {
    --p: #4361ee;
    --s: #3f37c9;
    --sc: #4cc9f0;
    --d: #f72585;
    --w: #f8961e;
    --l: #f8f9fa;
    --dk: #212529;
    --g: #6c757d;
  }
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
  }
  body {
    background: #f5f7fb;
    color: var(--dk);
  }
  .dashboard {
    display: flex;
    min-height: 100vh;
  }
  
  /* Improved Sidebar */
  .sidebar {
    width: 250px;
    background: #fff;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    padding: 20px 0;
    transition: all 0.3s ease;
    position: relative;
    z-index: 100;
  }
  .sidebar-header {
    padding: 0 20px 20px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    margin-bottom: 15px;
  }
  .sidebar-header h2 {
    color: var(--p);
    display: flex;
    align-items: center;
    white-space: nowrap;
  }
  .sidebar-header i {
    margin-right: 10px;
    color: var(--sc);
    font-size: 1.5rem;
  }
  .sidebar-menu {
    list-style: none;
  }
  .sidebar-menu a {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: var(--g);
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    white-space: nowrap;
  }
  .sidebar-menu a:hover, .sidebar-menu a.active {
    background: rgba(67, 97, 238, 0.1);
    color: var(--p);
    border-left: 3px solid var(--p);
  }
  .sidebar-menu i {
    font-size: 1.1rem;
    min-width: 24px;
    text-align: center;
    margin-right: 12px;
    transition: all 0.3s ease;
  }
  
  /* Collapsed Sidebar */
  .sidebar.collapsed {
    width: 80px;
  }
  .sidebar.collapsed .sidebar-header h2,
  .sidebar.collapsed .sidebar-menu a span {
    display: none;
  }
  .sidebar.collapsed .sidebar-menu a {
    justify-content: center;
    padding: 15px 0;
  }
  .sidebar.collapsed .sidebar-menu i {
    margin-right: 0;
    font-size: 1.3rem;
  }
  
  .main-content {
    flex: 1;
    padding: 20px;
    transition: margin-left 0.3s ease;
  }
  .main-content.collapsed {
    margin-left: 80px;
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    padding: 15px 25px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }
  .user-info {
    display: flex;
    align-items: center;
  }
  .user-info img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
    object-fit: cover;
  }
  .cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
  }
  .card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    transition: .3s;
  }
  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }
  
  /* Graph Container Styles */
  .graphs-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }
  .graph-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }
  .graph-container:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  }
  .graph-container h3 {
    margin-bottom: 15px;
    font-size: 1.1rem;
    color: var(--p);
    font-weight: 600;
  }
  .graph-container canvas {
    width: 100% !important;
    height: 250px !important;
  }
  
  /* Toggle Button */
  #sidebarToggle {
    background: var(--p);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  #sidebarToggle:hover {
    background: var(--s);
    transform: scale(1.05);
  }
  
  @media (max-width: 768px) {
    .dashboard {
      flex-direction: column;
    }
    .sidebar {
      width: 100%;
      height: auto;
    }
    .sidebar.collapsed {
      width: 100%;
      height: 60px;
      overflow: hidden;
    }
    .sidebar.collapsed .sidebar-menu {
      display: none;
    }
    .main-content.collapsed {
      margin-left: 0;
    }
    .cards {
      grid-template-columns: 1fr;
    }
  }
 </style>
</head>
<body>
 <div class="dashboard">
  <div class="sidebar">
    <div class="sidebar-header">
  <h2>
    <img src="assets/logo_1.png" style="height: 80px; vertical-align: middle; margin-right: 9px;">
    BUPOPS
  </h2>
</div>
    <ul class="sidebar-menu">
      <li><a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
      <li><a href="/bupops/admin/fees.php"><i class="fas fa-file-invoice-dollar"></i> <span>Fees</span></a></li>
      <li><a href="/bupops/admin/users.php" id="usersLink"><i class="fas fa-users"></i><span>Users</span></a></li>
      <li><a href="/bupops/admin/admin_notifications.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
      <li><a href="/bupops/admin/reports.php"><i class="fas fa-chart-bar"></i><span>Reports</span></a></li>
      <li><a href="/bupops/admin/settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
      <li><a href="/bupops/auth/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="header">
      <button id="sidebarToggle" class="toggle-btn"><i class="fas fa-bars"></i></button>
      <h1>Dashboard Overview</h1>
      <div class="user-info">
        <img src="https://ui-avatars.com/api/?name=<?= urlencode($email) ?>&background=random" alt="User">
        <div>
          <h4><?= $email ?></h4>
          <small>Administrator</small>
        </div>
      </div>
    </div>

    <div class="cards">
      <?php
      $cards = [
        ["Total Payments", "₱" . number_format($totalCollected, 2), "Completed payments total", "fas fa-wallet", "#28a745", "#fff"],
        ["Pending Fees", $pendingFees, "Payments waiting approval", "fas fa-clock", "#dc3545", "#fff"],
        ["Active Users", $totalUsers, "All registered users", "fas fa-users", "#0d6efd", "#fff"],
        ["Recent Notifications", $totalNotifs, "$unreadNotifs unread", "fas fa-bell", "#ffc107", "#000"]
      ];
      
      foreach ($cards as [$title, $value, $desc, $icon, $bgColor, $textColor]) {
        $linkStart = ($title === "Active Users") ? "<a href='' style='text-decoration: none;'>" : "";
        $linkEnd = ($title === "Active Users") ? "</a>" : "";

        echo $linkStart;
        echo "<div class='card' style='background: $bgColor; color: $textColor'>
          <div class='card-header' style='display: flex; justify-content: space-between; align-items: center;'>
            <h3>$title</h3>
            <i class='$icon'></i>
          </div>
          <div class='card-body'><h2>$value</h2><p>$desc</p></div>
        </div>";
        echo $linkEnd;
      }
      ?>
    </div>

    <div class="graphs-container">
      <!-- Total Payments by Month -->
      <div class="graph-container">
        <h3>Total Payments by Month</h3>
        <canvas id="paymentsMonthChart"></canvas>
      </div>

     
    
    </div>
  </div>
 </div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script>
  // Your existing functions remain exactly the same
  function fetchPayments() {
    fetch('fetch_payments.php')
      .then(res => res.json())
      .then(data => {
        const tbody = document.querySelector("#payments-table");
        tbody.innerHTML = data.map(row => `
          <tr>
            <td>${row.id}</td>
            <td>${row.name} (${row.email})</td>
            <td>₱${parseFloat(row.amount).toFixed(2)}</td>
            <td>${row.description}</td>
            <td>
              <select onchange="updateStatus(${row.id}, this.value)">
                <option value="pending" ${row.status=='pending'?'selected':''}>Pending</option>
                <option value="completed" ${row.status=='completed'?'selected':''}>Completed</option>
                <option value="failed" ${row.status=='failed'?'selected':''}>Failed</option>
              </select>
            </td>
            <td>${row.checkout_url ? `<a href="${row.checkout_url}" target="_blank">Open</a>` : "N/A"}</td>
            <td>${row.created_at}</td>
            <td>
              <button onclick="deletePayment(${row.id})" style="background: var(--d); color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">Delete</button>
            </td>
          </tr>
        `).join('');
      });
  }

  function updateStatus(id, status) {
    fetch('update_status.php', {
      method: 'POST',
      headers: {'Content-Type':'application/x-www-form-urlencoded'},
      body: `id=${id}&status=${status}`
    }).then(res => res.text()).then(alert).then(fetchPayments);
  }

  function deletePayment(id) {
    if (confirm("Delete this payment?")) {
      fetch('delete_payment.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}`
      })
      .then(res => res.text())
      .then(response => {
        alert(response);
        fetchPayments();
      });
    }
  }

  document.getElementById('usersLink').addEventListener('click', async function(e) {
    e.preventDefault();
    const pw = prompt("Enter the admin password to proceed:");
    if (pw === null) return;

    try {
      const res = await fetch('/bupops/auth/verify_admin_password.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'admin_key=' + encodeURIComponent(pw)
      });
      const text = (await res.text()).trim();

      if (text === 'valid') {
        window.location.href = this.href;
      } else {
        alert("Incorrect admin password.");
      }
    } catch (err) {
      console.error(err);
      alert("Error verifying password. Try again later.");
    }
  });

  document.getElementById("sidebarToggle").addEventListener("click", () => {
    document.querySelector(".sidebar").classList.toggle("collapsed");
    document.querySelector(".main-content").classList.toggle("collapsed");
  });

  const months = <?php echo json_encode($months); ?>;
  const totals = <?php echo json_encode($totals); ?>;

  // Enhanced Total Payments by Month Chart
  const ctx1 = document.getElementById('paymentsMonthChart').getContext('2d');
  const paymentsMonthChart = new Chart(ctx1, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Total Payments (₱)',
        data: totals,
        borderColor: '#4361ee',
        backgroundColor: 'rgba(67, 97, 238, 0.1)',
        borderWidth: 3,
        tension: 0.3,
        pointBackgroundColor: '#fff',
        pointBorderColor: '#4361ee',
        pointBorderWidth: 2,
        pointRadius: 5,
        pointHoverRadius: 7,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            font: {
              size: 12,
              family: 'Poppins'
            },
            padding: 20
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.8)',
          titleFont: {
            size: 14,
            family: 'Poppins'
          },
          bodyFont: {
            size: 12,
            family: 'Poppins'
          },
          padding: 12,
          cornerRadius: 8,
          displayColors: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          grid: {
            color: 'rgba(0,0,0,0.05)'
          },
          ticks: {
            callback: function(value) { return '₱' + value.toFixed(2); },
            font: {
              family: 'Poppins'
            }
          }
        },
        x: {
          grid: {
            display: false
          },
          ticks: {
            font: {
              family: 'Poppins'
            }
          }
        }
      }
    }
  });

  // Enhanced Payment Status Breakdown (Doughnut Chart)
  const ctx2 = document.getElementById('paymentStatusChart').getContext('2d');
  const paymentStatusChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: <?php echo json_encode($statuses); ?>,
      datasets: [{
        label: 'Payment Status',
        data: <?php echo json_encode($counts); ?>,
        backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
        borderColor: '#fff',
        borderWidth: 3,
        hoverOffset: 15
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '70%',
      plugins: {
        legend: {
          position: 'right',
          labels: {
            font: {
              size: 12,
              family: 'Poppins'
            },
            padding: 20,
            usePointStyle: true,
            pointStyle: 'circle'
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0,0,0,0.8)',
          titleFont: {
            size: 14,
            family: 'Poppins'
          },
          bodyFont: {
            size: 12,
            family: 'Poppins'
          },
          padding: 12,
          cornerRadius: 8
        }
      }
    }
  });

  
 </script>
</body>
</html>