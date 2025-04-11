<?php
if (!is_user_logged_in()) {
    echo '<div class="alert alert-warning">Please <a href="' . get_permalink(get_page_by_path('customer-login')) . '">log in</a> to access your dashboard.</div>';
    return;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

if (!in_array('customer', (array) $current_user->roles)) {
    echo '<div class="alert alert-danger">Access denied. Only customers can view this dashboard.</div>';
    return;
}

$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'profile';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
    .customer-dashboard-container {
        margin-top: 40px;
        padding: 20px;
        background: #f8f9fc;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
    }

    .dashboard-header {
        background: linear-gradient(to right, #0d6efd, #66b2ff);
        color: #fff;
        padding: 25px 30px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
    }

    .dashboard-header h2 {
        font-size: 1.8rem;
        font-weight: 600;
        color : white;
        margin-bottom: 5px;
    }

    .dashboard-header .avatar {
        background: rgba(255,255,255,0.2);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 24px;
        font-weight: bold;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dashboard-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 1rem;
    }

    .dashboard-tabs {
        display: flex;
        gap: 15px;
    }

    .dashboard-tabs .nav-link {
        padding: 10px 20px;
        background: #fff;
        border-radius: 10px;
        font-weight: 500;
        color: #333;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: 0.3s;
    }

    .dashboard-tabs .nav-link.active {
        background-color: #0d6efd;
        color: #fff;
    }

    .dashboard-tabs .nav-link:hover {
        background-color: #e2e6ea;
    }

    .logout-btn {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .logout-btn:hover {
        background-color: #bb2d3b;
    }

    .dashboard-content {
        background: #ffffff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="container">
    <div class="customer-dashboard-container">
        <div class="dashboard-header">
            <div>
                <h2>Hello, <?php echo esc_html($current_user->display_name); ?> 👋</h2>
            </div>
            <div class="avatar">
                <?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?>
            </div>
        </div>

        <div class="dashboard-nav">
            <div class="dashboard-tabs">
                <a href="?tab=profile" class="nav-link <?php echo ($tab === 'profile') ? 'active' : ''; ?>">Profile</a>
                <a href="?tab=orders" class="nav-link <?php echo ($tab === 'orders') ? 'active' : ''; ?>">My Orders</a>
            </div>
            <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-btn">Logout</a>
        </div>

        <div class="dashboard-content">
            <?php
            switch ($tab) {
                case 'orders':
                    include plugin_dir_path(__FILE__) . 'orders-customer.php';
                    break;
                case 'profile':
                    include plugin_dir_path(__FILE__) . 'profile-customer.php';
                    break;
                default:
                    echo '<p>This is your customer dashboard. Use the tabs to view your orders or update your profile.</p>';
                    break;
            }
            ?>
        </div>
    </div>
</div>
