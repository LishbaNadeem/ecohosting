<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = null;
    $db_active = false;
    
    try {
        $conn = getDBConnection();
        $db_active = true;
    } catch (Exception $e) {
        $db_active = false;
    }

    // Handle newsletter subscription from footer form
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'newsletter') {
        $email = isset($_POST['EMAIL']) ? trim($_POST['EMAIL']) : '';
        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Save to DB if available
            if ($db_active) {
                $stmt = $conn->prepare("INSERT IGNORE INTO contact_queries (name, email, subject, message) VALUES (?, ?, ?, ?)");
                $newsletter_name = 'Newsletter Subscriber';
                $newsletter_subject = 'Newsletter Subscription';
                $newsletter_msg = 'Subscribed via footer newsletter form.';
                $stmt->bind_param("ssss", $newsletter_name, $email, $newsletter_subject, $newsletter_msg);
                $stmt->execute();
                $stmt->close();
                $conn->close();
            }
            $_SESSION['success'] = "Thank you for subscribing to our newsletter!";
        } else {
            $_SESSION['error'] = "Please provide a valid email address to subscribe.";
        }
        // Redirect to the referring page or home
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("Location: " . $redirect);
        exit();
    }

    // Sanitize inputs
    $name = isset($_POST['name']) ? sanitize($_POST['name'], $conn) : '';
    $email = isset($_POST['email']) ? sanitize($_POST['email'], $conn) : '';
    $subject = isset($_POST['subject']) ? sanitize($_POST['subject'], $conn) : '';
    $message = isset($_POST['message']) ? sanitize($_POST['message'], $conn) : '';

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $_SESSION['error'] = "All contact form fields are required.";
        header("Location: contact.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Please provide a valid email address.";
        header("Location: contact.php");
        exit();
    }

    // 1. Save to Database if active
    $db_saved = false;
    if ($db_active) {
        $stmt = $conn->prepare("INSERT INTO contact_queries (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $db_saved = true;
        }
        $stmt->close();
        $conn->close();
    }

    // 2. Email Notification fallback
    $to = "rockybd1995@gmail.com";
    $headers = "From: " . $email . "\r\n";
    $headers .= "Reply-To: ". $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $body = "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><title>Contact Form Submission</title></head><body>";
    $body .= "<table style='width: 100%; border: none;'>";
    $body .= "<tbody>";
    $body .= "<tr><td><strong>Name:</strong></td><td>{$name}</td></tr>";
    $body .= "<tr><td><strong>Email:</strong></td><td>{$email}</td></tr>";
    $body .= "<tr><td><strong>Subject:</strong></td><td>{$subject}</td></tr>";
    $body .= "<tr><td><strong>Message:</strong></td><td>{$message}</td></tr>";
    $body .= "</tbody></table>";
    $body .= "</body></html>";

    // Attempt to send, but suppress errors in case SMTP isn't configured locally
    @mail($to, "EcoHosting Contact Form: " . $subject, $body, $headers);

    $_SESSION['success'] = "Thank you! Your message has been sent successfully and our team will get back to you shortly.";
    header("Location: contact.php");
    exit();
} else {
    header("Location: contact.php");
    exit();
}
?>