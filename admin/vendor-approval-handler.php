<?php
// Add Approve/Reject buttons in WP Users Panel
function servicehub_mvm_vendor_approval_column($columns) {
    $columns['vendor_approval'] = 'Vendor Approval';
    return $columns;
}
add_filter('manage_users_columns', 'servicehub_mvm_vendor_approval_column');

function servicehub_mvm_vendor_approval_column_content($value, $column_name, $user_id) {
    if ($column_name === 'vendor_approval') {
        $status = get_user_meta($user_id, '_vendor_approval_status', true);

        // Handle cases where _vendor_approval_status is missing
        if (!$status) {
            // Check if user has the 'vendor' role (assume approved if vendor)
            $user = get_userdata($user_id);
            if (in_array('vendor', $user->roles)) {
                update_user_meta($user_id, '_vendor_approval_status', 'approved'); // Set default to approved
                return '✅ Approved';
            } else {
                update_user_meta($user_id, '_vendor_approval_status', 'pending'); // Default new users to pending
                return '⏳ Pending';
            }
        }

        if ($status === 'pending') {
            return '<a href="' . esc_url(admin_url("users.php?action=approve_vendor&user_id=$user_id")) . '">Approve</a> | 
                    <a href="' . esc_url(admin_url("users.php?action=reject_vendor&user_id=$user_id")) . '">Reject</a>';
        } elseif ($status === 'approved') {
            return '✅ Approved';
        } else {
            return '❌ Rejected';
        }
    }
    return $value;
}

add_filter('manage_users_custom_column', 'servicehub_mvm_vendor_approval_column_content', 10, 3);

// Handle Approval/Rejection Actions
function servicehub_mvm_vendor_approval_actions() {
    if (isset($_GET['action']) && isset($_GET['user_id']) && current_user_can('manage_options')) {
        $user_id = intval($_GET['user_id']);

        if ($_GET['action'] === 'approve_vendor') {
            update_user_meta($user_id, '_vendor_approval_status', 'approved');
            wp_update_user(['ID' => $user_id, 'role' => 'vendor']);
            wp_mail(get_userdata($user_id)->user_email, "Your Vendor Account is Approved", "Your account is now active. You can log in and start adding services!");
        }

        if ($_GET['action'] === 'reject_vendor') {
            update_user_meta($user_id, '_vendor_approval_status', 'rejected');
            wp_mail(get_userdata($user_id)->user_email, "Your Vendor Application is Rejected", "Unfortunately, your application was not approved.");
        }

        wp_redirect(admin_url('users.php'));
        exit;
    }
}
add_action('admin_init', 'servicehub_mvm_vendor_approval_actions');
