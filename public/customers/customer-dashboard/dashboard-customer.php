<?php
if (!is_user_logged_in()) {
    echo '<div class="alert alert-warning">Please <a href="' . esc_url(wp_login_url()) . '">log in</a> to access your dashboard.</div>';
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

<div class="container-fluid mt-4">
    <h2 class="mb-4">Welcome, <?php echo esc_html($current_user->display_name); ?> 👋</h2>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4">
         <li class="nav-item">
            <a class="nav-link <?php echo ($tab === 'profile') ? 'active' : ''; ?>" href="?tab=profile">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php echo ($tab === 'orders') ? 'active' : ''; ?>" href="?tab=orders">My Orders</a>
        </li>
        <li class="nav-item ms-auto">
    <a class="nav-link text-danger" href="<?php echo wp_logout_url(home_url()); ?>">
        Logout
    </a>
</li>


    </ul>

    <!-- Dynamic Content -->
    <div class="card p-4 shadow-sm">
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
