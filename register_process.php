<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();

    // Sanitize inputs
    $fullname = sanitize($_POST['fullname'], $conn);
    $email = sanitize($_POST['email'], $conn);
    $password = $_POST['password']; // Don't sanitize password to preserve special characters
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($fullname) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please enter a valid email address.";
        header("Location: register.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters long.";
        header("Location: register.php");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email address is already registered.";
        $stmt->close();
        $conn->close();
        header("Location: register.php");
        exit();
    }
    $stmt->close();

    // Hash password and insert user
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        $stmt->close();
        $conn->close();
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        $stmt->close();
        $conn->close();
        header("Location: register.php");
        exit();
    }
} else {
    // If accessed directly without POST
    header("Location: register.php");
    exit();
}
?>
