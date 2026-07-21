<?php
include('config.php');

// Redirect user if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
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
        $page_title = "Welcome Back";
        $page_subtitle = "Sign in to manage your hosting account & active subscriptions";
        include('inc/hero.php'); 
        ?>
        <!-- Hero Area End -->

        <!-- Login Form Section -->
        <section class="contact-section section-padding" style="background: #f4f7f6;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-8">
                        <div class="card shadow-lg p-4 p-md-5" style="border-radius: 16px; border: none; background: #ffffff;">
                            
                            <div class="text-center mb-4">
                                <div class="mb-3 d-inline-block p-3 rounded-circle" style="background: rgba(255, 94, 19, 0.1);">
                                    <i class="fas fa-user-lock fa-2x" style="color: #ff5e13;"></i>
                                </div>
                                <h3 style="color: #2c234d; font-weight: 700;">Account Login</h3>
                                <p class="text-muted small">Enter your credentials to access your client portal</p>
                            </div>

                            <?php
                            if (isset($_SESSION['error'])) {
                                echo '<div class="alert alert-danger alert-dismissible fade show p-3 mb-4" role="alert" style="border-radius: 8px; font-weight: 600;">
                                        <i class="fas fa-exclamation-circle mr-2"></i>' . $_SESSION['error'] . '
                                      </div>';
                                unset($_SESSION['error']);
                            }
                            if (isset($_SESSION['success'])) {
                                echo '<div class="alert alert-success alert-dismissible fade show p-3 mb-4" role="alert" style="border-radius: 8px; font-weight: 600;">
                                        <i class="fas fa-check-circle mr-2"></i>' . $_SESSION['success'] . '
                                      </div>';
                                unset($_SESSION['success']);
                            }
                            ?>

                            <form action="login_process.php" method="POST">
                                <div class="form-group mb-3">
                                    <label for="email" class="font-weight-bold" style="color: #2c234d;">Email Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px; width: 45px; justify-content: center;"><i class="fas fa-envelope text-muted"></i></span>
                                        </div>
                                        <input type="email" name="email" id="email" class="form-control border-left-0" placeholder="name@example.com" required style="border-radius: 0 8px 8px 0; height: 50px; font-size: 15px;">
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label for="password" class="font-weight-bold mb-0" style="color: #2c234d;">Password <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px; width: 45px; justify-content: center;"><i class="fas fa-lock text-muted"></i></span>
                                        </div>
                                        <input type="password" name="password" id="password" class="form-control border-left-0" placeholder="••••••••" required style="border-radius: 0 8px 8px 0; height: 50px; font-size: 15px;">
                                    </div>
                                </div>

                                <button type="submit" name="submit" class="btn text-white w-100 py-3 mb-4" style="background: #ff5e13; border: none; border-radius: 8px; font-weight: 700; font-size: 16px; box-shadow: 0 4px 15px rgba(255, 94, 19, 0.3);">
                                    Sign In To Portal
                                </button>

                                <div class="text-center pt-3 border-top">
                                    <p class="mb-0 text-muted">Don't have an account? <a href="register.php" class="font-weight-bold" style="color: #ff5e13;">Create New Account</a></p>
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