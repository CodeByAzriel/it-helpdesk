<?php 
include 'config.php';

$current_page = basename($_SERVER['PHP_SELF']);
$is_logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>IT Helpdesk System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="main-header">
    <div class="nav-container">

        <div class="logo">
            <span class="logo-accent">IT</span> Helpdesk
        </div>

        <div class="nav-actions">
            <?php if($is_logged_in): ?>
                <a href="logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="btn-primary">Get Started</a>
            <?php endif; ?>
        </div>

    </div>
</header>

<div class="page-wrapper">