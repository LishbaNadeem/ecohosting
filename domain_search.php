<?php
include('config.php');

$searchQuery = isset($_GET['domain']) ? trim($_GET['domain']) : '';
$domainClean = strtolower($searchQuery);

// Remove spaces
$domainClean = preg_replace('/\s+/', '', $domainClean);

$resultAvailable = false;
$suggestedDomain = '';
$price = 0.00;
$tld = '';

if (!empty($domainClean)) {
    // If no dot is present, append .com
    if (strpos($domainClean, '.') === false) {
        $domainClean .= '.com';
    }

    // Extract TLD
    $parts = explode('.', $domainClean);
    $tld = '.' . end($parts);
    
    // Check if contains taken keywords
    $prefix = $parts[0];
    $isTaken = false;
    
    $takenKeywords = ['google', 'facebook', 'apple', 'microsoft', 'yahoo', 'amazon', 'netflix', 'github', 'youtube'];
    foreach ($takenKeywords as $keyword) {
        if (strpos($prefix, $keyword) !== false) {
            $isTaken = true;
            break;
        }
    }
    
    // Prefix too short is usually taken
    if (strlen($prefix) < 4) {
        $isTaken = true;
    }
    
    // Realistic Simulation: deterministically mark ~30% of normal queries as taken
    // This way, 'mytestblog123.com' will always return the same availability status
    if (!$isTaken) {
        $hash = md5($domainClean);
        $firstChar = substr($hash, 0, 1);
        if (in_array($firstChar, ['0', '1', '2', '3', '4'])) {
            $isTaken = true; // 5/16 = ~31% chance to be taken
        }
    }
    
    if (!$isTaken) {
        $resultAvailable = true;
        // Determine pricing
        switch ($tld) {
            case '.com':
                $price = 15.99;
                break;
            case '.net':
                $price = 10.99;
                break;
            case '.org':
                $price = 12.99;
                break;
            case '.me':
                $price = 8.99;
                break;
            default:
                $price = 14.99;
                break;
        }
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
        $page_title = "Domain Search Results";
        $page_subtitle = "Find your online identity today";
        include('inc/hero.php'); 
        ?>
        <!-- Hero Area End -->

        <!-- Search Bar Area -->
        <section class="contact-section section-padding">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-10">
                        <div class="card p-4 shadow-sm" style="border-radius: 12px; border: none; background: #fbf9ff;">
                            <form action="domain_search.php" method="GET" class="d-flex align-items-center">
                                <input type="text" name="domain" class="form-control mr-3" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search for another domain (e.g. website.com)" required style="height: 55px; border-radius: 8px; font-size: 16px;">
                                <button type="submit" class="btn text-white" style="background: #ff5e13; border: none; height: 55px; line-height: 55px; padding: 0 35px; font-weight: bold; border-radius: 8px;">Search</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Results Display -->
                <?php if (!empty($searchQuery)): ?>
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="card p-5 text-center shadow" style="border-radius: 12px; border: none;">
                                <?php if ($resultAvailable): ?>
                                    <div class="mb-4">
                                        <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                                    </div>
                                    <h2 class="mb-2" style="font-weight: 700; color: #2c234d;">Good news! <code><?php echo htmlspecialchars($domainClean); ?></code> is available.</h2>
                                    <h3 class="mb-4" style="color: #ff5e13; font-weight: 700;">$<?php echo number_format($price, 2); ?> <span style="font-size: 16px; font-weight: normal; color: #6c757d;">/ first year</span></h3>
                                    <p class="text-muted mb-4">Secure this domain and attach it to one of our premium high-speed green hosting plans to launch your website immediately.</p>
                                    
                                    <div class="d-flex justify-content-center">
                                        <a href="packages.php?domain=<?php echo urlencode($domainClean); ?>" class="btn text-white px-5 py-3" style="background: #ff5e13; border: none; border-radius: 8px; font-weight: bold;">
                                            Proceed to Choose Hosting Plan
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-4">
                                        <i class="fas fa-times-circle text-danger" style="font-size: 80px;"></i>
                                    </div>
                                    <h2 class="mb-2" style="font-weight: 700; color: #2c234d;">Sorry, <code><?php echo htmlspecialchars($domainClean); ?></code> is already taken.</h2>
                                    <p class="text-muted mb-4">Try searching for a different name, or modify spelling and extensions (e.g. trying `.net` or `.org`).</p>
                                    
                                    <h4 class="mt-4 mb-3">Try these alternatives:</h4>
                                    <div class="d-flex flex-wrap justify-content-center gap-2" style="gap: 15px;">
                                        <a href="domain_search.php?domain=<?php echo urlencode($prefix . 'web' . $tld); ?>" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 8px;"><code><?php echo htmlspecialchars($prefix . 'web' . $tld); ?></code></a>
                                        <a href="domain_search.php?domain=<?php echo urlencode($prefix . 'online' . $tld); ?>" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 8px;"><code><?php echo htmlspecialchars($prefix . 'online' . $tld); ?></code></a>
                                        <a href="domain_search.php?domain=<?php echo urlencode($prefix . ($tld == '.com' ? '.net' : '.com')); ?>" class="btn btn-outline-secondary px-3 py-2" style="border-radius: 8px;"><code><?php echo htmlspecialchars($prefix . ($tld == '.com' ? '.net' : '.com')); ?></code></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include('inc/footer.php'); ?>
    <?php include('inc/script.php'); ?>
</body>
</html>
