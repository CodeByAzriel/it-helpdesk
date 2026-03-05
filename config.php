<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

// Database configuration
$host = "sql102.infinityfree.com";
$username = "if0_41212854";
$password = "7XwzvYls2gsGJQZ";
$database = "if0_41212854_it_helpdesk";
$port = 3306;

try {
    $conn = new mysqli($host, $username, $password, $database, $port);
    
    if ($conn->connect_error) {
        error_log("Database Connection Failed: " . $conn->connect_error);
        die("Database connection failed. Please try again later.");
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    error_log("Database Error: " . $e->getMessage());
    die("Database error occurred. Please try again later.");
}

// CSRF Token functions
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
}

// Security functions
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header("Location: dashboard.php");
        exit();
    }
}
?>
