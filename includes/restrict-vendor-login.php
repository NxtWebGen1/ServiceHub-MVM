<?php
// Prevents vendors with "pending" status from logging in (EXCLUDES ADMIN)
function servicehub_mvm_restrict_vendor_login($user, $password) {
    if ($user instanceof WP_User) {
        // Exclude admins from restriction
        if (in_array('administrator', $user->roles)) {
            return $user; // Allow admins to log in
        }

        $vendor_status = get_user_meta($user->ID, '_vendor_approval_status', true);
        
        if ($vendor_status === 'pending') {
            return new WP_Error('vendor_pending', __('Your account is under review. You will be notified once approved.'));
        }
    }
    return $user;
}
add_filter('wp_authenticate_user', 'servicehub_mvm_restrict_vendor_login', 10, 2);




// Redirect vendors from wp-login.php to custom login page
// function servicehub_mvm_redirect_vendor_login() {
//     if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false && !is_admin()) {
//         wp_redirect(home_url('/vendor-login')); // Redirect vendors to custom login page
//         exit;
//     }
// }
// add_action('init', 'servicehub_mvm_redirect_vendor_login');  




