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








