<?php include('config.php'); ?>
<!doctype html>
<html class="no-js" lang="zxx">
<?php include('inc/head.php'); ?>
<body>
    <!-- ? Preloader Start -->
    <?php include('inc/loader.php'); ?>
    <!-- Preloader Start -->
    <header>
        <!-- Header Start -->
        <?php include('inc/header.php'); ?>
        <!-- Header End -->
    </header>
    <main>
        <!-- Slider Area Start-->
        <?php include('inc/slider.php'); ?>
        <!-- Slider Area End -->
        <!-- Domain-search start -->
        <?php include('inc/search.php'); ?>
    <!-- Domain-search End -->
    <!--? Team -->
    <section class="team-area section-padding40 section-bg1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-12">
                    <div class="section-tittle text-center mb-105">
                        <h2>Most amazing features</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6"">
                    <div class="single-cat">
                        <div class="cat-icon">
                            <img src="assets/img/icon/services1.svg" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#">Employee Owned</a></h5>
                            <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="single-cat">
                        <div class="cat-icon">
                            <img src="assets/img/icon/services2.svg" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#">Commitment to Security</a></h5>
                            <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="single-cat">
                        <div class="cat-icon">
                            <img src="assets/img/icon/services3.svg" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#">Passion for Privacy</a></h5>
                            <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="single-cat">
                        <div class="cat-icon">
                            <img src="assets/img/icon/services4.svg" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#">Employee Owned</a></h5>
                            <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="single-cat">
                        <div class="cat-icon">
                            <img src="assets/img/icon/services5.svg" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#">24/7 Support</a></h5>
                            <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="single-cat">
                        <div class="cat-icon">
                            <img src="assets/img/icon/services6.svg" alt="">
                        </div>
                        <div class="cat-cap">
                            <h5><a href="#">100% Uptime Guaranteed</a></h5>
                            <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Services End -->
    <!--? Pricing Card Start -->
    <section class="pricing-card-area fix">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8">
                    <div class="section-tittle text-center mb-90">
                        <h2>Choose plan which fit for you</h2>
                        <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools. Our experts are just part of the reason Bluehost is the ideal home for your WordPress website. We're here to help you succeed!</p>
                    </div>
                </div>
            </div>
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
    <?php include('inc/about-2.php'); ?>
    <!-- About-2 Area End -->
    <!-- ask questions -->
    <section class="ask-questions section-bg1 section-padding30 fix">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-9 col-md-10 ">
                    <!-- Section Tittle -->
                    <div class="section-tittle text-center mb-90">
                        <h2>Frequently ask questions</h2>
                        <p>Supercharge your WordPress hosting with detailed website analytics, marketing tools. Our experts are just part of the reason Bluehost is the ideal home for your WordPress website. We're here to help you succeed!</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="single-question d-flex mb-50">
                        <span> Q.</span>
                        <div class="pera">
                            <h2>Why can't people connect to the web server on my PC?</h2>
                            <p>We operate one of the most advanced 100 Gbit networks in the world, complete with Anycast support and extensive DDoS protection.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="single-question d-flex mb-50">
                        <span> Q.</span>
                        <div class="pera">
                            <h2>What domain name should I choose for my site?</h2>
                            <p>We operate one of the most advanced 100 Gbit networks in the world, complete with Anycast support and extensive DDoS protection.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="single-question d-flex mb-50">
                        <span> Q.</span>
                        <div class="pera">
                            <h2>How can I make my website work without www. in front?</h2>
                            <p>We operate one of the most advanced 100 Gbit networks in the world, complete with Anycast support and extensive DDoS protection.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="single-question d-flex mb-50">
                        <span> Q.</span>
                        <div class="pera">
                            <h2>Why does Internet Information Server want a password?</h2>
                            <p>We operate one of the most advanced 100 Gbit networks in the world, complete with Anycast support and extensive DDoS protection.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 ">
                    <div class="more-btn text-center mt-20">
                        <a href="#" class="btn">Go to Support</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End ask questions -->
    <!--? Testimonial Area Start -->
    <?php include('inc/test.php'); ?>
    <!--? Testimonial Area End -->
</main>

  <!-- Scroll Up -->
  <div id="back-top" >
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

<?php include('inc/footer.php'); ?>

<!-- JS here -->
<?php include('inc/script.php'); ?>

</body>
</html>