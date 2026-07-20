<?php include('config.php'); ?>
<!doctype html>
<html class="no-js" lang="zxx">
<?php include('inc/head.php'); ?>
<body>
    <!-- ? Preloader Start -->
    <?php include('inc/loader.php'); ?>
    <!-- Preloader Start -->
    <header>
        <?php include('inc/header.php'); ?>
    </header>
    <main>
        <!-- Slider Area Start-->
        <?php include('inc/hero.php'); ?>
        <!-- Slider Area End -->
        <!--? Pricing Card Start -->
        <section class="pricing-card-area pricing-card-area2 fix">
            <div class="container">
                <div class="row">
                    <?php
                    $packages = [];
                    try {
                        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                        if (!$conn->connect_error) {
                            $sql = "SELECT * FROM packages ORDER BY id ASC";
                            $result = $conn->query($sql);
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $packages[] = $row;
                                }
                            }
                            $conn->close();
                        }
                    } catch (Exception $e) {
                        // Fall back
                    }

                    if (empty($packages)) {
                        $packages = [
                            [
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
                            [
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
                            [
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
                    }

                    foreach ($packages as $package):
                    ?>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-10">
                            <div class="single-card text-center mb-30">
                                <div class="card-top">
                                    <img src="assets/img/icon/<?php echo htmlspecialchars($package['icon']); ?>" alt="">
                                    <h4><?php echo htmlspecialchars($package['name']); ?></h4>
                                    <p>Starting at</p>
                                </div>
                                <div class="card-mid">
                                    <h4>$<?php echo htmlspecialchars(number_format($package['price'], 2)); ?> <span>/ month</span></h4>
                                </div>
                                <div class="card-bottom">
                                    <ul>
                                        <li><?php echo htmlspecialchars($package['space']); ?></li>
                                        <li><?php echo htmlspecialchars($package['bandwidth']); ?></li>
                                        <li><?php echo htmlspecialchars($package['backup']); ?></li>
                                        <li><?php echo htmlspecialchars($package['domain']); ?></li>
                                        <li><?php echo htmlspecialchars($package['databases_qty']); ?></li>
                                    </ul>
                                    <a href="checkout.php?id=<?php echo $package['id']; ?><?php echo isset($_GET['domain']) ? '&domain=' . urlencode($_GET['domain']) : ''; ?>" class="borders-btn">Get Started</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- Pricing Card End -->
        <!--? About-1 Area Start -->
        <?php include('inc/about-co.php'); ?>
        <!-- About-1 Area End -->
        <!--? About-2 Area Start -->
        <?php include('inc/about-co.php'); ?>
        <!-- About-2 Area End -->
    </main>
    <?php include('inc/footer.php'); ?>
      <!-- Scroll Up -->
      <div id="back-top" >
        <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
    </div>

    <!-- JS here -->

    <?php include('inc/script.php'); ?>
    
</body>
</html>