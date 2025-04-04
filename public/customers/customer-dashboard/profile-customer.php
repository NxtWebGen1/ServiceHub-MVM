<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$address = get_user_meta($user_id, 'street_address', true);


// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_nonce']) && wp_verify_nonce($_POST['update_profile_nonce'], 'update_customer_profile')) {

    $display_name = sanitize_text_field($_POST['display_name']);
    $phone = sanitize_text_field($_POST['phone']);
    $address = sanitize_textarea_field($_POST['street_address']);


    // Update user fields
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

// Get current values
$display_name = $current_user->display_name;
$phone = get_user_meta($user_id, 'phone', true);
?>

<h4>🧾 Profile Information</h4>
<form method="post" class="mb-5">
    <?php wp_nonce_field('update_customer_profile', 'update_profile_nonce'); ?>

    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" class="form-control" name="display_name" value="<?php echo esc_attr($display_name); ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email (cannot be changed)</label>
        <input type="email" class="form-control" value="<?php echo esc_attr($current_user->user_email); ?>" disabled>
    </div>

    <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" class="form-control" name="phone" value="<?php echo esc_attr($phone); ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea class="form-control" name="street_address" rows="2"><?php echo esc_textarea($address); ?></textarea>
    </div>


    <button type="submit" class="btn btn-primary">💾 Save Changes</button>
</form>

<hr>

<h4>🔐 Change Password</h4>
<form method="post">
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

    <button type="submit" class="btn btn-warning">🔁 Update Password</button>
</form>
