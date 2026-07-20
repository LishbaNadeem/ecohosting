<?php
if (!isset($page_title)) {
    $filename = basename($_SERVER['PHP_SELF'], '.php');
    $page_title = ucwords(str_replace('_', ' ', $filename));
}
if (!isset($page_subtitle)) {
    $page_subtitle = "EcoHosting Services";
}
?>
<!-- Hero Area Start-->
<div style="background: linear-gradient(135deg, #2c234d 0%, #1a1535 100%); padding: 140px 0 60px; margin-top: 0;">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="text-center">
                    <h2 style="color: #fff; font-size: 42px; font-weight: 700; margin-bottom: 10px;"><?php echo htmlspecialchars($page_title); ?></h2>
                    <p style="color: rgba(255,255,255,0.75); font-size: 16px; margin: 0;"><?php echo htmlspecialchars($page_subtitle); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Hero Area End -->
