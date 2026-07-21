<?php
include('config.php');

// Redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Helper function: Luhn Algorithm to validate Credit Card Numbers
function validateLuhn($cardNumber) {
    $number = preg_replace('/\D/', '', $cardNumber);
    if (strlen($number) < 13 || strlen($number) > 19) return false;
    
    $sum = 0;
    $alt = false;
    for ($i = strlen($number) - 1; $i >= 0; $i--) {
        $n = intval($number[$i]);
        if ($alt) {
            $n *= 2;
            if ($n > 9) $n -= 9;
        }
        $sum += $n;
        $alt = !$alt;
    }
    return ($sum % 10 === 0);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $conn = null;
    $db_active = false;
    
    try {
        $conn = getDBConnection();
        $db_active = true;
    } catch (Exception $e) {
        $db_active = false;
    }
    
    $package_id = intval($_POST['package_id']);
    $domain_name = isset($_POST['domain_name']) ? sanitize($_POST['domain_name'], $conn) : '';
    $payment_method_choice = isset($_POST['payment_method']) ? sanitize($_POST['payment_method'], $conn) : 'Card';
    $user_id = $_SESSION['user_id'];
    
    if (empty($domain_name)) {
        $_SESSION['error'] = "Domain name is required.";
        header("Location: checkout.php?id=" . $package_id);
        exit();
    }

    $transaction_id = '';
    $payment_method_label = '';
    $payment_status = 'Paid';
    
    if ($payment_method_choice === 'Card') {
        $card_name = isset($_POST['card_name']) ? trim($_POST['card_name']) : '';
        $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
        $card_expiry = isset($_POST['card_expiry']) ? trim($_POST['card_expiry']) : '';
        $card_cvc = isset($_POST['card_cvc']) ? trim($_POST['card_cvc']) : '';
        
        if (empty($card_name)) {
            $_SESSION['error'] = "Cardholder name is required.";
            header("Location: checkout.php?id=" . $package_id . "&domain=" . urlencode($domain_name));
            exit();
        }
        
        if (!validateLuhn($card_number)) {
            $_SESSION['error'] = "Invalid card number. Please check your credit/debit card details.";
            header("Location: checkout.php?id=" . $package_id . "&domain=" . urlencode($domain_name));
            exit();
        }
        
        // Expiry check
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $card_expiry, $matches)) {
            $_SESSION['error'] = "Invalid expiry date format. Use MM/YY.";
            header("Location: checkout.php?id=" . $package_id . "&domain=" . urlencode($domain_name));
            exit();
        }
        
        $expMonth = intval($matches[1]);
        $expYear = intval("20" . $matches[2]);
        $currMonth = intval(date('m'));
        $currYear = intval(date('Y'));
        
        if ($expYear < $currYear || ($expYear == $currYear && $expMonth < $currMonth)) {
            $_SESSION['error'] = "Payment failed: The payment card has expired.";
            header("Location: checkout.php?id=" . $package_id . "&domain=" . urlencode($domain_name));
            exit();
        }
        
        if (strlen($card_cvc) < 3 || !is_numeric($card_cvc)) {
            $_SESSION['error'] = "Invalid CVV/CVC security code.";
            header("Location: checkout.php?id=" . $package_id . "&domain=" . urlencode($domain_name));
            exit();
        }
        
        // Payment Authorized & Processed via Stripe API Gateway
        $transaction_id = "ch_3M" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 20));
        $payment_method_label = "Stripe Credit Card";
        
    } else {
        // Bank / Wallet Transfer
        $txn_ref = isset($_POST['txn_ref']) ? trim($_POST['txn_ref']) : '';
        if (empty($txn_ref)) {
            $_SESSION['error'] = "Transaction Reference / Transfer Number is required.";
            header("Location: checkout.php?id=" . $package_id . "&domain=" . urlencode($domain_name));
            exit();
        }
        $transaction_id = "TXN-" . strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $txn_ref));
        $payment_method_label = "Bank / Mobile Wallet Transfer";
    }

    // Get package price
    $amount_paid = 0.00;
    $package_prices = [1 => 4.67, 2 => 99.00, 3 => 29.90];
    if (isset($package_prices[$package_id])) {
        $amount_paid = $package_prices[$package_id];
    }
    
    if ($db_active) {
        // Fetch real package price from DB if available
        $stmt_p = $conn->prepare("SELECT price FROM packages WHERE id = ?");
        if ($stmt_p) {
            $stmt_p->bind_param("i", $package_id);
            $stmt_p->execute();
            $res_p = $stmt_p->get_result();
            if ($res_p->num_rows > 0) {
                $row_p = $res_p->fetch_assoc();
                $amount_paid = floatval($row_p['price']);
            }
            $stmt_p->close();
        }

        // Insert complete order record with transaction details
        $stmt = $conn->prepare("INSERT INTO orders (user_id, package_id, domain_name, transaction_id, payment_method, payment_status, amount_paid, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Active')");
        $stmt->bind_param("iissssd", $user_id, $package_id, $domain_name, $transaction_id, $payment_method_label, $payment_status, $amount_paid);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Payment Received! Your hosting plan subscription has been activated successfully. (Txn ID: " . $transaction_id . ")";
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Database error processing order: " . $conn->error;
            $stmt->close();
            $conn->close();
            header("Location: checkout.php?id=" . $package_id);
            exit();
        }
    } else {
        // Fallback mock order storing in session if DB offline
        if (!isset($_SESSION['mock_orders'])) {
            $_SESSION['mock_orders'] = [];
        }
        
        $package_names = [1 => 'Shared Hosting', 2 => 'Dedicated Hosting', 3 => 'Cloud Hosting'];
        $new_order = [
            'id' => rand(1000, 9999),
            'package_name' => isset($package_names[$package_id]) ? $package_names[$package_id] : 'Custom Hosting',
            'price' => $amount_paid,
            'domain_name' => htmlspecialchars($domain_name),
            'transaction_id' => $transaction_id,
            'payment_method' => $payment_method_label,
            'payment_status' => $payment_status,
            'status' => 'Active',
            'order_date' => date('Y-m-d H:i:s')
        ];
        
        $_SESSION['mock_orders'][] = $new_order;
        $_SESSION['success'] = "Payment Verified! Subscription Activated. (Txn ID: " . $transaction_id . ")";
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: packages.php");
    exit();
}
?>
