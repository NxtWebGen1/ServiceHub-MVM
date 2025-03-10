<?php

function vendor_dashboard_menu() {
    ?>
    <div class="wrap">
        <h1>Vendor Dashboard</h1>

        <!-- Navigation Tabs -->
        <h2 class="nav-tab-wrapper">
            <a href="?page=vendor-dashboard&tab=profile" class="nav-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] == 'profile') ? 'nav-tab-active' : ''; ?>">Profile</a>
            <a href="?page=vendor-dashboard&tab=services" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'services') ? 'nav-tab-active' : ''; ?>">Services</a>
            <a href="?page=vendor-dashboard&tab=orders" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'Orders') ? 'nav-tab-active' : ''; ?>">Orders</a>

        </h2>

        <div class="vendor-dashboard-content">
            <?php
            $tab = isset($_GET['tab']) ? $_GET['tab'] : 'profile';
            
            if ($tab == 'profile') {
                include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/vendor profile/vendor-profile.php'; // Profile Page
            } elseif ($tab == 'services') {
                include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/services/services-main.php'; // Services Page
            } elseif ($tab == 'add-service') {
                include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/services/services-add.php'; // Add Service Page
            } elseif ($tab == 'edit-service' && isset($_GET['edit_service'])) {
                include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/services/services-edit.php'; // Edit Service Page
            }
                
            elseif ($tab == 'orders') {
                include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/Orders/order-main.php'; // order Page
            } 
            ?>
        </div>
    </div>
    <?php
}
