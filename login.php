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
    <!-- ? Preloader Start -->
    <?php include('inc/loader.php'); ?>
    <!-- Preloader Start-->


<main class="login-body" data-vide-bg="assets/img/login-bg.mp4">
    <!-- Login Admin -->
    <form class="form-default" action="login_process.php" method="POST">
        
        <div class="login-form">
            <!-- logo-login -->
            <div class="logo-login">
                <a href="index.php"><img src="assets/img/logo/loder.png" alt=""></a>
            </div>
            <h2>Login Here</h2>

            <?php
            if (isset($_SESSION['error'])) {
                echo '<div style="color: #ff3333; margin-bottom: 15px; font-weight: bold; text-align: center;">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div style="color: #33cc33; margin-bottom: 15px; font-weight: bold; text-align: center;">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <div class="form-input">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-input">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-input pt-30">
                <input type="submit" name="submit" value="login">
            </div>
            
            <!-- Forget Password -->
            <a href="#" class="forget">Forget Password</a>
            <!-- Registration -->
            <a href="register.php" class="registration">Registration</a>
        </div>
    </form>
    <!-- /end login form -->
</main>


<?php include('inc/script.php'); ?>
    
    </body>
</html>