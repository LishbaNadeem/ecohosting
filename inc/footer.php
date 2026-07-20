<?php include_once(dirname(__DIR__) . '/config.php'); ?>
<footer>
    <div class="footer-wrappr " data-background="assets/img/gallery/footer-bg.png">
        <div class="footer-area footer-padding ">
            <div class="container">
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <!-- logo -->
                            <div class="footer-logo mb-25">
                                <a href="index.php"><img src="assets/img/logo/logo2_footer.png" alt="EcoHosting"></a>
                            </div>
                            <div class="footer-tittle mb-50">
                                <p>Subscribe our newsletter to get updates about our services</p>
                            </div>
                            <!-- Form -->
                            <div class="footer-form">
                                <div id="mc_embed_signup">
                                    <form action="contact_process.php" method="POST" class="subscribe_form relative mail_part">
                                        <input type="hidden" name="form_type" value="newsletter">
                                        <input type="email" name="EMAIL" id="newsletter-form-email" placeholder=" Email Address " class="placeholder hide-on-focus" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Your email address'">
                                        <div class="form-icon">
                                            <button type="submit" name="submit" id="newsletter-submit" class="email_icon newsletter-submit button-contactForm">
                                                Subscribe
                                            </button>
                                        </div>
                                        <div class="mt-10 info"></div>
                                    </form>
                                </div>
                            </div>
                            <!-- social -->
                            <!-- <div class="footer-social mt-50">
                                <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                                <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" title="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                            </div> -->
                        </div>
                    </div>
                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-1"></div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Company</h4>
                                <ul>
                                    <li><a href="about.php">About Us</a></li>
                                    <li><a href="packages.php">Packages</a></li>
                                    <li><a href="blog.php">Blog</a></li>
                                    <li><a href="contact.php">Contact</a></li>
                                    <li><a href="help.php">Help Center</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Products</h4>
                                <ul>
                                    <li><a href="packages.php?type=shared">Shared Hosting</a></li>
                                    <li><a href="packages.php?type=dedicated">Dedicated Hosting</a></li>
                                    <li><a href="packages.php?type=cloud">Cloud Hosting</a></li>
                                    <li><a href="domain_search.php">Domain Search</a></li>
                                    <li><a href="services.php">All Services</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-5">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Support</h4>
                                <ul>
                                    <li><a href="help.php">FAQ</a></li>
                                    <li><a href="contact.php">Support Ticket</a></li>
                                    <li><a href="contact.php">Live Chat</a></li>
                                    <?php if(isset($_SESSION['user_id'])): ?>
                                    <li><a href="dashboard.php">My Dashboard</a></li>
                                    <?php else: ?>
                                    <li><a href="login.php">Sign In</a></li>
                                    <?php endif; ?>
                                    <li><a href="register.php">Register</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer-bottom area -->
        <div class="footer-bottom-area">
            <div class="container">
                <div class="footer-border">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="footer-copy-right text-center">
                                <p>
                                  Copyright &copy;<script>document.write(new Date().getFullYear());</script> EcoHosting. All rights reserved.
                                  | Built with <i class="fa fa-heart" aria-hidden="true" style="color: #ff5e13;"></i> for a greener web.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>