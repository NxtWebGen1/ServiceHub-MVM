<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$address = get_user_meta($user_id, 'street_address', true);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_nonce']) && wp_verify_nonce($_POST['update_profile_nonce'], 'update_customer_profile')) {
    $display_name = sanitize_text_field($_POST['display_name']);
    $phone = sanitize_text_field($_POST['phone']);
    $address = sanitize_textarea_field($_POST['street_address']);

    wp_update_user([
        'ID' => $user_id,
        'display_name' => $display_name
    ]);

    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'street_address', $address);

    echo '<div class="alert alert-success">✅ Profile updated successfully!</div>';
}

// Handle password update
if (isset($_POST['change_password_nonce']) && wp_verify_nonce($_POST['change_password_nonce'], 'change_customer_password')) {
    $current_pass = $_POST['current_password'];
    $new_pass     = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (!empty($new_pass) && $new_pass === $confirm_pass) {
        if (wp_check_password($current_pass, $current_user->user_pass, $user_id)) {
            wp_set_password($new_pass, $user_id);
            echo '<div class="alert alert-success">🔒 Password updated. Please log in again.</div>';
            wp_logout();
            exit;
        } else {
            echo '<div class="alert alert-danger">❌ Incorrect current password.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">❌ New passwords do not match.</div>';
    }
}

$display_name = $current_user->display_name;
$phone = get_user_meta($user_id, 'phone', true);
?>

<style>
    .profile-form-wrapper {
        background: #ffffff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }
    .form-label {
        font-weight: 600;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 5px rgba(13, 110, 253, 0.2);
    }
</style>

<div class="profile-form-wrapper">
    <h4 class="fw-bold mb-4">🧾 Profile Information</h4>
    <form method="post" class="row g-3">
        <?php wp_nonce_field('update_customer_profile', 'update_profile_nonce'); ?>

        <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="display_name" value="<?php echo esc_attr($display_name); ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Email (cannot be changed)</label>
            <input type="email" class="form-control" value="<?php echo esc_attr($current_user->user_email); ?>" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone" value="<?php echo esc_attr($phone); ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Address</label>
            <textarea class="form-control" name="street_address" rows="2"><?php echo esc_textarea($address); ?></textarea>
        </div>

        <div class="col-12 d-flex justify-content-between align-items-center mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i> Save Changes
            </button>
            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#passwordModal">
                <i class="fas fa-lock me-2"></i> Change Password
            </button>
        </div>
    </form>
</div>

<!-- Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <form method="post">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">🔐 Change Password</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <?php wp_nonce_field('change_customer_password', 'change_password_nonce'); ?>

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-sync-alt me-2"></i> Update Password
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
