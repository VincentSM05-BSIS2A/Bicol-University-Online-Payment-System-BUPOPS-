<?php
session_start();
session_unset();
session_destroy();
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: pages/dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUP Online Payment System - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        /* Loader styling */
        #loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    z-index: 9999;
}

.logo-container {
    text-align: center;
}

.loader-logo {
    width: 100px;
    animation: pulse 3.5s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.7;
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.loading-text {
    margin-top: 15px;
    font-size: 18px;
    font-weight: bold;
    color: #FF8C00;
    font-family: Arial, sans-serif;
}

        #main-content {
            display: none;
        }

        body {
            background: url('assets/online-payment-systems.webp') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }

        .header {
            background: white;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header img {
            height: 50px;
            margin-right: 10px;
        }
        .header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #FF8C00;
        }
        .header h4 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: rgb(43, 0, 255);
        }

        .login-title {
            text-align: left;
            font-size: 70px;
            font-weight: bold;
            margin-top: 35px;
            margin-left: 30px;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 25);
        }

        .login-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 20px;
            width: 450px;
            text-align: center;
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 5px 5px 15px rgba(0, 0, 0,25);
        }

        .form-group {
            text-align: left;
            margin-bottom: 15px;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
        .form-control {
            border-radius: 20px;
            padding: 10px;
        }

        .footer {
            position: absolute;
            bottom: 2px;
            text-align: center;
            width: 100%;
            color: white;
        }
        .footer a {
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>


<!-- CUSTOM LOADER WITH LOGO -->
<div id="loader">
    <div class="logo-container">
        <img src="assets/bupc_logo-removebg-preview.png" alt="Loading" class="loader-logo">
        <p class="loading-text">Loading...</p>
    </div>
</div>


<!-- MAIN CONTENT -->
<div id="main-content">
    <div class="header">
        <img src="assets/bupc_logo-removebg-preview.png" alt="Bicol University Logo">
        <div>
            <h3>BICOL UNIVERSITY POLANGUI</h3>
            <h4><small>ONLINE PAYMENT SYSTEM</small></h4>
        </div>
    </div>

    <h2 class="login-title">BUP Online Payment System</h2>

    <div class="login-box">
        <p>User access to the system</p>
        <form action="/bupops/auth/process_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="form-group position-relative">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                <i class="fas fa-eye position-absolute end-0 top-50 translate-middle-y me-3 text-muted" id="togglePassword" style="cursor: pointer;"></i>
            </div>
            <button type="submit" class="btn btn-primary">Log In</button>
            <a href="./pages/signup.php" class="btn btn-secondary">Sign Up</a>
        </form>
    </div>

    <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
    <div id="signupAlert" class="alert alert-success text-center" style="position: absolute; top: 10%; left: 50%; transform: translateX(-50%); width: 50%;">
        Account created successfully! Please log in.
    </div>
    <?php endif; ?>

    <div class="footer d-flex justify-content-between align-items-center px-4 py-2 bg-white text-dark">
        <div>
            <p class="mb-0">Stay in Touch</p>
            <p class="mb-0"><a href="https://bupol.edu.ph" class="text-dark">https://bupol.edu.ph</a></p>
            <p class="mb-0"><a href="mailto:bupops@bicol-p.edu.ph" class="text-dark">bupops@bicol-p.edu.ph</a></p>
        </div>
        <div class="d-flex align-items-center">
            <a href="#" class="text-dark me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-dark me-3"><i class="fab fa-twitter"></i></a>
            <button class="btn btn-primary btn-sm">Get the mobile app</button>
        </div>
    </div>
</div>

<script>
    // Hide loader and show main content
    window.addEventListener("load", () => {
        setTimeout(() => {
            document.getElementById("loader").style.display = "none";
            document.getElementById("main-content").style.display = "block";
        }, 2000);
    });

    // Toggle password visibility
    document.getElementById("togglePassword").onclick = function () {
        let pwd = document.getElementById("password");
        pwd.type = pwd.type === "password" ? "text" : "password";
        this.classList.toggle("fa-eye-slash");
    };

    // Auto-hide success alert
    setTimeout(function () {
        let alertBox = document.getElementById("signupAlert");
        if (alertBox) {
            alertBox.style.transition = "opacity 0.5s";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 500);
        }
    }, 3000);
</script>

</body>
</html>
