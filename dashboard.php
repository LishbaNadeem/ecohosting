<?php
include('config.php');

// Redirect user if not logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please sign in to access your dashboard.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];
$email = $_SESSION['email'];

$orders = [];
$db_active = false;

try {
    $conn = getDBConnection();
    $db_active = true;
    
    $stmt = $conn->prepare("SELECT o.id, o.domain_name, o.status, o.order_date, p.name as package_name, p.price 
                            FROM orders o 
                            JOIN packages p ON o.package_id = p.id 
                            WHERE o.user_id = ? 
                            ORDER BY o.id DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    $db_active = false;
}

// Fallback to mock session orders if database is offline/not ready
if (!$db_active && isset($_SESSION['mock_orders'])) {
    $orders = $_SESSION['mock_orders'];
}
?>
<!doctype html>
<html class="no-js" lang="zxx">
<?php include('inc/head.php'); ?>
<body>
    <!-- Preloader Start -->
    <?php include('inc/loader.php'); ?>
    <!-- Preloader End -->
    
    <?php include('inc/header.php'); ?>

    <main>
        <!-- Hero Area Start-->
        <?php
        $page_title = "Client Dashboard";
        $page_subtitle = "Manage your account and active hosting subscriptions";
        include('inc/hero.php'); 
        ?>
        <!-- Hero Area End -->

        <!-- Dashboard Content Section -->
        <section class="contact-section section-padding">
            <div class="container">
                <div class="row">
                    <!-- Profile Sidebar -->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <div class="card shadow p-4 text-center" style="border-radius: 12px; border: none; background: #fbf9ff;">
                            <div class="mb-3">
                                <img src="assets/img/icon/services3.svg" alt="User Icon" style="width: 80px; height: 80px; border-radius: 50%; background: #e3ebf6; padding: 15px;">
                            </div>
                            <h3 class="mb-1" style="color: #2c234d; font-weight: 700;"><?php echo htmlspecialchars($fullname); ?></h3>
                            <p class="text-muted"><?php echo htmlspecialchars($email); ?></p>
                            <hr>
                            <div class="text-left mt-3">
                                <p class="mb-2"><strong>Member Since:</strong> <?php echo date('F Y'); ?></p>
                                <p class="mb-2"><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                                <?php if (!$db_active): ?>
                                    <p class="mb-0 text-warning"><i class="fas fa-exclamation-triangle"></i> Running in Offline Sandbox</p>
                                <?php endif; ?>
                            </div>
                            <div class="mt-4">
                                <a href="logout.php" class="btn text-white w-100 py-2" style="background: #ff5e13; border: none; border-radius: 8px;">Logout</a>
                            </div>
                        </div>
                    </div>

                    <!-- Orders/Services List -->
                    <div class="col-lg-8">
                        <div class="card shadow p-4" style="border-radius: 12px; border: none; background: #fff;">
                            <h3 class="mb-4" style="color: #2c234d; font-weight: 700;">My Active Subscriptions</h3>
                            
                            <?php
                            if (isset($_SESSION['success'])) {
                                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                                unset($_SESSION['success']);
                            }
                            ?>

                            <?php if (empty($orders)): ?>
                                <div class="text-center py-5">
                                    <img src="assets/img/icon/services1.svg" alt="" style="width: 60px; opacity: 0.5; margin-bottom: 15px;">
                                    <h4 class="text-muted">You do not have any active subscriptions.</h4>
                                    <p class="text-muted">Explore our hosting plans and get started today.</p>
                                    <a href="packages.php" class="btn text-white mt-3" style="background: #ff5e13; border: none; border-radius: 8px;">View Packages</a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover border">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Subscription ID</th>
                                                <th>Plan Type</th>
                                                <th>Domain</th>
                                                <th>Cost</th>
                                                <th>Purchase Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td><strong>#<?php echo htmlspecialchars($order['id']); ?></strong></td>
                                                    <td><?php echo htmlspecialchars($order['package_name']); ?></td>
                                                    <td><code><?php echo htmlspecialchars($order['domain_name']); ?></code></td>
                                                    <td>$<?php echo htmlspecialchars(number_format($order['price'], 2)); ?>/mo</td>
                                                    <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                                                    <td><span class="badge badge-success" style="padding: 6px 12px; border-radius: 4px;"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include('inc/footer.php'); ?>
    <?php include('inc/script.php'); ?>
</body>
</html>
