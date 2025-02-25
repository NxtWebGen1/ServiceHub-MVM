<?php

echo 'this is john';
function vendor_dashboard_menu() {
    
    ?>
    <div class="wrap">
    <h1>Vendor Dashboard</h1>

    <!-- Navigation Tabs -->
    <h2 class="nav-tab-wrapper">
        <a href="?page=vendor-dashboard&tab=profile" class="nav-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'profile') ? 'nav-tab-active' : ''; ?>">Profile</a>
        <a href="?page=vendor-dashboard&tab=services" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'services') ? 'nav-tab-active' : ''; ?>">Services</a>
    </h2>

    <div class="vendor-dashboard-content">
        <?php
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
        
        if ($tab == 'profile') {
            include 'vendor profile\vendor-profile.php'; // Include Profile Page
        } elseif ($tab == 'services') {
            include 'services\services-main.php'; // Include Services Page
        }
        ?>
    </div>
</div>

<?php

}
