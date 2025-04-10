<?php
function vendor_dashboard_menu() {
    ?>
    <style>
        /* Container & Title */
        .vendor-dashboard-container {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }

        .vendor-dashboard-container h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
        }

        .vendor-dashboard-container h1 i {
            color: #0d6efd;
            margin-right: 10px;
        }

        /* Tabs */
        .vendor-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        .vendor-tab {
            background-color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-weight: 500;
            text-decoration: none;
            color: #333;
            transition: all 0.25s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .vendor-tab:hover {
            background-color: #e9ecef;
        }

        .vendor-tab.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }

        .vendor-tab i {
            font-size: 1rem;
        }

        /* Content Area */
        .vendor-dashboard-content {
            background: #ffffff;
            border-radius: 10px;
            padding: 30px;
            border: 1px solid #dee2e6;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        }
    </style>

    <div class="wrap">
        <div class="vendor-dashboard-container">
            <h1><i class="fas fa-store"></i> Vendor Dashboard</h1>

            <!-- Modern Tab Navigation -->
            <div class="vendor-tabs">
                <a href="?page=vendor-dashboard&tab=profile" class="vendor-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'profile') ? 'active' : ''; ?>">
                    <i class="fas fa-user-circle"></i> Profile
                </a>
                <a href="?page=vendor-dashboard&tab=services" class="vendor-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'services') ? 'active' : ''; ?>">
                    <i class="fas fa-briefcase"></i> Services
                </a>
                <a href="?page=vendor-dashboard&tab=orders" class="vendor-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'orders') ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
                <a href="?page=vendor-dashboard&tab=transactions" class="vendor-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'transactions') ? 'active' : ''; ?>">
                    <i class="fas fa-money-check-alt"></i> Transactions
                </a>
            </div>

            <div class="vendor-dashboard-content">
                <?php
                $tab = $_GET['tab'] ?? 'profile';

                switch ($tab) {
                    case 'profile':
                        include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/vendor profile/vendor-profile.php';
                        break;

                    case 'services':
                        include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/services/services-main.php';
                        break;

                    case 'add-service':
                        include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/services/services-add.php';
                        break;

                    case 'edit-service':
                        if (isset($_GET['edit_service'])) {
                            include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/services/services-edit.php';
                        }
                        break;

                    case 'orders':
                        include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/orders/order-main.php';
                        break;

                    case 'transactions':
                        include SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/transactions/transactions-main.php';
                        break;
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <?php
}
