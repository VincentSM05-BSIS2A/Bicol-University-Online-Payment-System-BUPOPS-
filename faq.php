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
    <title>FAQ - BUPOPS</title>
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
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            font-family: 'Inter', sans-serif;
            color: #fff;
        }

        .faq-container {
            background: linear-gradient(rgba(25, 135, 84, 0.1), rgba(21, 115, 71, 0.2)),
                        url('../assets/green2.jpeg') center/cover;
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 850px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin: 2rem auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            color: var(--accent-color);
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .accordion-item {
            background-color: rgba(25, 135, 84, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .accordion-button {
            background-color: rgba(25, 135, 84, 0.2);
            color: #ffffff;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(25, 135, 84, 0.3);
            color: #ffffff;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.1);
        }

        .accordion-button::after {
            filter: brightness(0) invert(1);
        }

        .accordion-body {
            background-color: rgba(25, 135, 84, 0.1);
            border-radius: 0 0 10px 10px;
            color: rgba(255, 255, 255, 0.9);
        }

        strong {
            color: var(--accent-color);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .faq-container {
                padding: 25px;
                margin: 1rem;
            }
            
            h2 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
<div class="faq-container">
    <h2>Frequently Asked Questions</h2>
    <div class="accordion" id="faqAccordion">

        <!-- FAQ 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq1-heading">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                    How do I pay my fees on BUPOPS?
                </button>
            </h2>
            <div id="faq1" class="accordion-collapse collapse show" aria-labelledby="faq1-heading" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Go to your dashboard, scroll to the list of payment categories, and click the <strong>"Pay Now"</strong> button beside the due you want to settle. You’ll be redirected to the link created by our API Paymongo interface to complete the payment.
                </div>
            </div>
        </div>

        <!-- FAQ 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq2-heading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                    How do I change my password?
                </button>
            </h2>
            <div id="faq2" class="accordion-collapse collapse" aria-labelledby="faq2-heading" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Navigate to <strong>"Change Password"</strong> under your profile menu. Enter your current password and your new password, then click <strong>"Update Password"</strong> to save changes.
                </div>
            </div>
        </div>

        <!-- FAQ 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq3-heading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                    How can I edit my profile information?
                </button>
            </h2>
            <div id="faq3" class="accordion-collapse collapse" aria-labelledby="faq3-heading" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Click on your name or profile picture to access your account settings. Then go to <strong>"Edit Profile"</strong>, update your details such as name, email, year, course, or section, and click <strong>"Save Changes"</strong>.
                </div>
            </div>
        </div>

        <!-- FAQ 4 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq4-heading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                    How do I message the admin?
                </button>
            </h2>
            <div id="faq4" class="accordion-collapse collapse" aria-labelledby="faq4-heading" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Go to the <strong>"Notifications"</strong> or <strong>"Message"</strong> button. You can send your concern or question there, and the admin will respond via the notification panel or contact details you've provided.
                </div>
            </div>
        </div>

        <!-- FAQ 5 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq5-heading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5">
                    How can I download my transaction history?
                </button>
            </h2>
            <div id="faq5" class="accordion-collapse collapse" aria-labelledby="faq5-heading" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    On your dashboard or reports page, locate the <strong>"Download my transactions"</strong> button. Click it to generate and download a report of your completed payments and transactions.
                </div>
            </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
