<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - BUPOPS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #198754;
            --secondary-green: #157347;
            --gradient-start: #1a936f;
            --gradient-end: #114b5f;
            --accent-color: #e9f5ef;
        }

        body {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            min-height: 100vh;
            margin: 0;
            padding: 40px 0;
            font-family: 'Inter', sans-serif;
            color: #fff;
        }

        .section {
            background: linear-gradient(rgba(25, 135, 84, 0.1), rgba(21, 115, 71, 0.2)),
                       url('../assets/green2.jpeg') center/cover;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .title {
            font-weight: 700;
            font-size: 2rem;
            color: var(--accent-color);
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            margin-bottom: 25px;
        }

        .text {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }

        .image-box img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 12px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }

        .image-box:hover img {
            transform: scale(1.02);
        }

        strong {
            color: var(--accent-color);
            font-weight: 600;
        }

        ul.text {
            list-style-type: none;
            padding-left: 1rem;
        }

        ul.text li {
            padding-left: 1.5rem;
            margin-bottom: 0.8rem;
            position: relative;
        }

        ul.text li::before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--primary-green);
        }

        @media (max-width: 768px) {
            .section {
                padding: 25px;
                margin: 15px;
            }
            
            .title {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
<!-- Add the same navbar from dashboard here -->
<div class="container py-5">
    <!-- About Section -->
    <div class="section">
        <h2 class="title">About BUPOPS</h2>
        <p class="text">
            The <strong>BU Polangui Online Payment System (BUPOPS)</strong> is an innovative platform designed to streamline and modernize student fee transactions at Bicol University Polangui Campus.
            It allows students to conveniently view and pay their dues online, track their payment history, and receive real-time updates on their financial status.
        </p>
        <p class="text">
            Developed with the needs of both students and administrators in mind, BUPOPS ensures secure, efficient, and transparent handling of school payments.
        </p>
        <div class="row mt-4">
            <div class="col-md-6 image-box mb-3 mb-md-0">
                <img src="assets/payment_illustration.avif" alt="Payment System Illustration">
            </div>
            <div class="col-md-6 image-box">
                <img src="assets/bupc_campus.jpg" alt="Bicol University Polangui Campus">
            </div>
        </div>
    </div>

    <!-- Vision Section -->
    <div class="section">
        <h2 class="title">Our Vision</h2>
        <p class="text">
            To be a leading digital platform that empowers students and administrators through seamless, secure, and accessible online payment services — contributing to a fully digitalized university experience.
        </p>
    </div>

    <!-- Mission Section -->
    <div class="section">
        <h2 class="title">Our Mission</h2>
        <ul class="text">
            <li>To provide a reliable and transparent payment system for all students of BU Polangui.</li>
            <li>To minimize manual payment processes and reduce queues and delays.</li>
            <li>To support the university’s initiative for digital transformation and service excellence.</li>
            <li>To ensure data security, accuracy, and efficiency in payment management.</li>
        </ul>
    </div>
</div>
</body>
</html>