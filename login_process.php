<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDBConnection();

    // Sanitize email
    $email = sanitize($_POST['email'], $conn);
    $password = $_POST['password']; // Don't sanitize password

    // Validation
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: login.php");
        exit();
    }

    // Retrieve user from database
    $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $fullname, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "Welcome back, " . htmlspecialchars($fullname) . "!";

            $stmt->close();
            $conn->close();
            
            // Redirect to index.php or dashboard.php
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
        }
    } else {
        $_SESSION['error'] = "No user found with that email address.";
    }

    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
