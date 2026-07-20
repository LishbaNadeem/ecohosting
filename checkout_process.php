<?php
include('config.php');

// Redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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
    $user_id = $_SESSION['user_id'];
    
    if (empty($domain_name)) {
        $_SESSION['error'] = "Domain name is required.";
        header("Location: checkout.php?id=" . $package_id);
        exit();
    }
    
    if ($db_active) {
        // DB available - insert to orders
        $stmt = $conn->prepare("INSERT INTO orders (user_id, package_id, domain_name, status) VALUES (?, ?, ?, 'Active')");
        $stmt->bind_param("iis", $user_id, $package_id, $domain_name);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Congratulations! Your hosting plan subscription has been activated successfully.";
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Database error: " . $conn->error;
            $stmt->close();
            $conn->close();
            header("Location: checkout.php?id=" . $package_id);
            exit();
        }
    } else {
        // Fallback simulation (e.g. if offline/DB not ready)
        if (!isset($_SESSION['mock_orders'])) {
            $_SESSION['mock_orders'] = [];
        }
        
        $package_names = [
            1 => 'Shared Hosting',
            2 => 'Dedicated Hosting',
            3 => 'Cloud Hosting'
        ];
        $package_prices = [
            1 => 4.67,
            2 => 99.00,
            3 => 29.90
        ];
        
        $new_order = [
            'id' => rand(1000, 9999),
            'package_name' => isset($package_names[$package_id]) ? $package_names[$package_id] : 'Custom Hosting',
            'price' => isset($package_prices[$package_id]) ? $package_prices[$package_id] : 0.00,
            'domain_name' => htmlspecialchars($domain_name),
            'status' => 'Active',
            'order_date' => date('Y-m-d H:i:s')
        ];
        
        $_SESSION['mock_orders'][] = $new_order;
        $_SESSION['success'] = "Hosting plan subscription activated! (Simulated Offline Mode)";
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: packages.php");
    exit();
}
?>
