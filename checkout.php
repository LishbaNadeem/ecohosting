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
                                    <label for="domain_name" class="font-weight-bold" style="color: #2c234d;">Domain Name <span class="text-danger">*</span></label>
                                    <input type="text" name="domain_name" id="domain_name" class="form-control" value="<?php echo isset($_GET['domain']) ? htmlspecialchars($_GET['domain']) : ''; ?>" placeholder="e.g. mynewwebsite.com" required style="border-radius: 8px; padding: 12px;">
                                    <small class="text-muted">Enter the domain name you want to link with this hosting package.</small>
                                </div>

                                <!-- Payment Method Selection -->
                                <hr class="my-4">
                                <h4 class="mb-3" style="color: #2c234d; font-weight: 700;"><i class="fas fa-lock text-success mr-2"></i>Select Payment Method</h4>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-2">
                                        <div class="card p-3 payment-opt-card border primary-opt text-center" id="opt-card" style="cursor: pointer; border-radius: 8px; border: 2px solid #ff5e13 !important; background: #ffffff;">
                                            <input type="radio" name="payment_method" value="Card" id="pm_card" checked style="display:none;">
                                            <label for="pm_card" class="mb-0 font-weight-bold" style="cursor: pointer;">
                                                <i class="fas fa-credit-card fa-lg text-primary mb-2 display-block"></i><br>
                                                Credit / Debit Card (Stripe)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="card p-3 payment-opt-card border text-center" id="opt-bank" style="cursor: pointer; border-radius: 8px; border: 1px solid #ced4da; background: #ffffff;">
                                            <input type="radio" name="payment_method" value="Bank" id="pm_bank" style="display:none;">
                                            <label for="pm_bank" class="mb-0 font-weight-bold" style="cursor: pointer;">
                                                <i class="fas fa-university fa-lg text-success mb-2 display-block"></i><br>
                                                Bank / JazzCash / EasyPaisa
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Payment Details Box -->
                                <div id="card-details-box" class="p-3 bg-white border rounded mb-4" style="border-radius: 8px;">
                                    <h5 class="font-weight-bold mb-3" style="color: #ff5e13;"><i class="fas fa-shield-alt mr-2"></i>Credit or Debit Card</h5>
                                    
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold small text-muted">Cardholder Name</label>
                                        <input type="text" name="card_name" id="card_name" class="form-control" placeholder="Name on card" style="border-radius: 6px;">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="font-weight-bold small text-muted">Card Number</label>
                                        <div class="input-group">
                                            <input type="text" name="card_number" id="card_number" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19" style="border-radius: 6px 0 0 6px;">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-white" style="border-radius: 0 6px 6px 0;"><i class="fab fa-cc-visa text-primary mr-1"></i><i class="fab fa-cc-mastercard text-danger"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="font-weight-bold small text-muted">Expiry Date</label>
                                            <input type="text" name="card_expiry" id="card_expiry" class="form-control" placeholder="MM/YY" maxlength="5" style="border-radius: 6px;">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="font-weight-bold small text-muted">CVV / CVC</label>
                                            <input type="password" name="card_cvc" id="card_cvc" class="form-control" placeholder="123" maxlength="4" style="border-radius: 6px;">
                                        </div>
                                    </div>
                                    <small class="text-muted d-block"><i class="fas fa-lock text-success mr-1"></i> Encrypted with 256-bit SSL connection.</small>
                                </div>

                                <!-- Bank / Wallet Details Box -->
                                <div id="bank-details-box" class="p-3 bg-white border rounded mb-4" style="border-radius: 8px; display: none;">
                                    <h5 class="font-weight-bold mb-3" style="color: #28a745;"><i class="fas fa-university mr-2"></i>Bank & Mobile Wallet Transfer</h5>
                                    <p class="small text-muted mb-2">Transfer <strong>$<?php echo number_format($package['price'], 2); ?></strong> to any of the accounts below and enter your Transaction Reference ID:</p>
                                    
                                    <div class="alert alert-light border mb-3 p-2 small">
                                        <strong>Bank Account:</strong> <?php echo BANK_NAME; ?><br>
                                        <strong>Account Title:</strong> <?php echo BANK_ACCOUNT_TITLE; ?><br>
                                        <strong>IBAN:</strong> <?php echo BANK_ACCOUNT_IBAN; ?><br>
                                        <strong>JazzCash / EasyPaisa:</strong> <?php echo JAZZCASH_EASYPAISA_NO; ?>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-weight-bold small">Transaction Reference / Sender Number <span class="text-danger">*</span></label>
                                        <input type="text" name="txn_ref" id="txn_ref" class="form-control" placeholder="e.g. TRX123456789 or 03001234567" style="border-radius: 6px;">
                                    </div>
                                </div>

                                <div class="form-group mt-4 text-center">
                                    <button type="submit" name="place_order" class="btn text-white w-100 py-3" style="font-size: 16px; border-radius: 8px; font-weight: bold; background: #ff5e13; border: none;">
                                        Complete & Pay $<?php echo number_format($package['price'], 2); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Payment Toggle Script -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardOpt = document.getElementById('opt-card');
            const bankOpt = document.getElementById('opt-bank');
            const pmCard = document.getElementById('pm_card');
            const pmBank = document.getElementById('pm_bank');
            const cardBox = document.getElementById('card-details-box');
            const bankBox = document.getElementById('bank-details-box');

            cardOpt.addEventListener('click', function() {
                pmCard.checked = true;
                cardOpt.style.border = '2px solid #ff5e13';
                bankOpt.style.border = '1px solid #ced4da';
                cardBox.style.display = 'block';
                bankBox.style.display = 'none';
            });

            bankOpt.addEventListener('click', function() {
                pmBank.checked = true;
                bankOpt.style.border = '2px solid #28a745';
                cardOpt.style.border = '1px solid #ced4da';
                bankBox.style.display = 'block';
                cardBox.style.display = 'none';
            });

            // Card Number Auto Formatting
            const cardNumInput = document.getElementById('card_number');
            if (cardNumInput) {
                cardNumInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    value = value.match(/.{1,4}/g)?.join(' ') || value;
                    e.target.value = value.substring(0, 19);
                });
            }

            // Expiry Formatting
            const expInput = document.getElementById('card_expiry');
            if (expInput) {
                expInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0,2) + '/' + value.substring(2,4);
                    }
                    e.target.value = value.substring(0,5);
                });
            }
        });
        </script>
    </main>

    <?php include('inc/footer.php'); ?>
    <?php include('inc/script.php'); ?>
</body>
</html>
