<?php
include_once(dirname(__DIR__) . '/config.php');
?>
<div class="header-area header-transparent">
            <div class="main-header ">
                <div class="header-bottom  header-sticky">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <!-- Logo -->
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="index.php"><img src="assets/img/logo/logo.png" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10">
                                <div class="menu-wrapper d-flex align-items-center justify-content-end">
                                    <!-- Main-menu -->
                                    <div class="main-menu d-none d-lg-block">
                                        <nav>
                                            <ul id="navigation">                                                                                          
                                                <li><a href="index.php">Home</a></li>
                                                <li><a href="packages.php">Packages</a></li>
                                                <li><a href="help.php">Help</a></li>
                                                <li><a href="#">Blog</a>
                                                    <ul class="submenu">
                                                        <li><a href="blog.php">Blog</a></li>
                                                        <li><a href="blog_details.php">Blog Details</a></li>
                                                        <li><a href="elements.php">Element</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="contact.php">Contact</a></li>
                                                
                                                <?php if (isset($_SESSION['user_id'])): ?>
                                                    <li><a href="dashboard.php" style="color: #ff5e13; font-weight: bold;">My Account</a></li>
                                                    <li class="button-header margin-left "><a href="dashboard.php" class="btn">Dashboard</a></li>
                                                    <li class="button-header"><a href="logout.php" class="btn3">Logout</a></li>
                                                <?php else: ?>
                                                    <!-- Button -->
                                                    <li class="button-header margin-left "><a href="register.php" class="btn">Sign Up</a></li>
                                                    <li class="button-header"><a href="login.php" class="btn3">Sign In</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div> 
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>