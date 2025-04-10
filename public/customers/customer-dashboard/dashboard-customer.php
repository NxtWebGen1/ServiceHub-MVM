<?php
if (!is_user_logged_in()) {
    echo '<div class="alert alert-warning">Please <a href="' . get_permalink(get_page_by_path('customer-login')) . '">log in</a> to access your dashboard.</div>';
    return;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Optional: Verify this user has the 'customer' role
if (!in_array('customer', (array) $current_user->roles)) {
    echo '<div class="alert alert-danger">Access denied. Only customers can view this dashboard.</div>';
    return;
}

// Handle tab switching
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'profile';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
    /* Container Styling */
    .customer-dashboard-container {
        margin-top: 50px;
        padding: 30px;
        background: #f4f6f9;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }

    /* Greeting Section */
    .dashboard-greeting {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 30px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .dashboard-greeting h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }

    .dashboard-greeting .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #ccc;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 22px;
        font-weight: 700;
        color: white;
    }

    /* Navigation Tabs */
    .dashboard-nav {
        display: flex;
        justify-content: flex-start;
        gap: 20px;
        margin-bottom: 30px;
    }

    .dashboard-nav .nav-link {
        padding: 12px 20px;
        background-color: #fff;
        border-radius: 10px;
        font-weight: 600;
        color: #555;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .dashboard-nav .nav-link:hover {
        background-color: #e2e6ea;
    }

    .dashboard-nav .nav-link.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Logout Button */
    .logout-btn {
        font-weight: 600;
        background-color: #f03e3e;
        color: white;
        padding: 8px 20px;
        border-radius: 6px;
        border: none;
        transition: background-color 0.2s ease;
        margin-left: auto;
    }

    .logout-btn:hover {
        background-color: #d63333;
    }

    /* Content Section */
    .dashboard-content {
        background-color: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="container-fluid mt-4">
    <div class="customer-dashboard-container">
        <!-- Greeting Section -->
        <div class="dashboard-greeting">
            <div>
                <h2>Welcome, <?php echo esc_html($current_user->display_name); ?> 👋</h2>
                <p>Here’s your customer dashboard</p>
            </div>
            <div class="user-avatar">
                <span><?php echo strtoupper(substr($current_user->display_name, 0, 1)); ?></span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="dashboard-nav">
            <a href="?tab=profile" class="nav-link <?php echo ($tab === 'profile') ? 'active' : ''; ?>">Profile</a>
            <a href="?tab=orders" class="nav-link <?php echo ($tab === 'orders') ? 'active' : ''; ?>">My Orders</a>
            <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-btn">Logout</a>
        </div>

        <!-- Dynamic Content -->
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
