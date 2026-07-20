<?php
include('config.php');

// Protect page - must be logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please sign in or register to purchase a hosting package.";
    header("Location: login.php");
    exit();
}

$packageId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($packageId <= 0) {
    header("Location: packages.php");
    exit();
}

// Fetch package details
$package = null;
try {
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->bind_param("i", $packageId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $package = $result->fetch_assoc();
    }
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Database connection or query failed
}

// Fallback to static packages if database query failed or is not seeded
if (!$package) {
    $fallbacks = [
        1 => [
            'id' => 1,
            'name' => 'Shared Hosting',
            'icon' => 'price1.svg',
            'price' => 4.67,
            'space' => '2 TB of space',
            'bandwidth' => 'unlimited bandwidth',
            'backup' => 'full backup systems',
            'domain' => 'free domain',
            'databases_qty' => 'unlimited database'
        ],
        2 => [
            'id' => 2,
            'name' => 'Dedicated Hosting',
            'icon' => 'price2.svg',
            'price' => 99.00,
            'space' => '10 TB of space',
            'bandwidth' => 'unlimited bandwidth',
            'backup' => 'full backup systems',
            'domain' => 'free domain',
            'databases_qty' => 'unlimited database'
        ],
        3 => [
            'id' => 3,
            'name' => 'Cloud Hosting',
            'icon' => 'price3.svg',
            'price' => 29.90,
            'space' => '5 TB of space',
            'bandwidth' => 'unlimited bandwidth',
            'backup' => 'full backup systems',
            'domain' => 'free domain',
            'databases_qty' => 'unlimited database'
        ]
    ];
    if (isset($fallbacks[$packageId])) {
        $package = $fallbacks[$packageId];
    } else {
        header("Location: packages.php");
        exit();
    }
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
        $page_title = "Checkout";
        $page_subtitle = "Complete your hosting subscription order";
        include('inc/hero.php'); 
        ?>
        <!-- Hero Area End -->

        <!-- Checkout Section -->
        <section class="contact-section section-padding">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card shadow p-4" style="border-radius: 12px; border: none; background: #fbf9ff;">
                            <h3 class="mb-4" style="color: #2c234d; font-weight: 700;">Confirm Your Order</h3>
                            
                            <?php
                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                                unset($_SESSION['error']);
                            }
                            ?>
                            
                            <form action="checkout_process.php" method="POST">
                                <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                                
                                <div class="row">
                                    <!-- Package Details -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label font-weight-bold" style="color: #ff5e13;">Selected Package</label>
                                        <div class="p-3 bg-white border rounded" style="border-radius: 8px;">
                                            <h4 class="font-weight-bold mb-1"><?php echo htmlspecialchars($package['name']); ?></h4>
                                            <p class="text-muted mb-2">Starting at</p>
                                            <h3 style="color: #2c234d; font-weight: 700;">$<?php echo htmlspecialchars(number_format($package['price'], 2)); ?> <span style="font-size: 14px; font-weight: normal;">/ month</span></h3>
                                        </div>
                                    </div>
                                    
                                    <!-- Package Inclusions -->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label font-weight-bold" style="color: #ff5e13;">Package Features</label>
                                        <div class="p-3 bg-white border rounded" style="border-radius: 8px;">
                                            <ul class="list-unstyled mb-0" style="font-size: 14px; line-height: 1.8;">
                                                <li><i class="fas fa-check text-success mr-2"></i> <?php echo htmlspecialchars($package['space']); ?></li>
                                                <li><i class="fas fa-check text-success mr-2"></i> <?php echo htmlspecialchars($package['bandwidth']); ?></li>
                                                <li><i class="fas fa-check text-success mr-2"></i> <?php echo htmlspecialchars($package['backup']); ?></li>
                                                <li><i class="fas fa-check text-success mr-2"></i> <?php echo htmlspecialchars($package['domain']); ?></li>
                                                <li><i class="fas fa-check text-success mr-2"></i> <?php echo htmlspecialchars($package['databases_qty']); ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- User Information -->
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Account Name</label>
                                            <input type="text" class="form-control bg-white" value="<?php echo htmlspecialchars($_SESSION['fullname']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Account Email</label>
                                            <input type="email" class="form-control bg-white" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Domain Input -->
                                <div class="form-group mb-4">
                                    <label for="domain_name" class="font-weight-bold">Enter Your Domain Name <span class="text-danger">*</span></label>
                                    <input type="text" name="domain_name" id="domain_name" class="form-control" value="<?php echo isset($_GET['domain']) ? htmlspecialchars($_GET['domain']) : ''; ?>" placeholder="e.g. mynewwebsite.com" required style="border-radius: 8px; padding: 12px;">
                                    <small class="text-muted">Enter the domain name you want to link with this hosting package.</small>
                                </div>

                                <div class="form-group mt-4 text-center">
                                    <button type="submit" name="place_order" class="btn text-white w-100 py-3" style="font-size: 16px; border-radius: 8px; font-weight: bold; background: #ff5e13; border: none;">
                                        Place Secure Subscription Order
                                    </button>
                                </div>
                            </form>
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
