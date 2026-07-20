<?php
// Start PHP session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database configuration (Auto-detect Local vs Production)
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '127.0.0.1' || $_SERVER['SERVER_ADDR'] == '::1') {
    // Default XAMPP credentials
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'ecohosting_db');
} else {
    // InfinityFree Production credentials
    define('DB_HOST', 'sql200.infinityfree.com');
    define('DB_USER', 'if0_42455722');
    define('DB_PASS', 'Q6f1L6fzIwSo1');
    define('DB_NAME', 'if0_42455722_ecohosting_db');
}

// Database connection helper
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Input sanitization helper
function sanitize($data, $conn = null) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    if ($conn) {
        $data = $conn->real_escape_string($data);
    }
    return $data;
}
?>
