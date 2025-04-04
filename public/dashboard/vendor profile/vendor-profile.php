<?php
$current_user = wp_get_current_user();
function get_meta($key) {
    return get_user_meta(get_current_user_id(), $key, true);
}

// Handle updated form from pop up
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $upload_dir = wp_upload_dir();

    if (!empty($_FILES['portfolio_upload']['name'])) {
        $file = $_FILES['portfolio_upload'];
        $uploaded = wp_handle_upload($file, ['test_form' => false]);

        if (!isset($uploaded['error'])) {
            update_user_meta(get_current_user_id(), 'portfolio_upload', esc_url_raw($uploaded['url']));
        }
    }

    // changeable items
    $fields = ['nickname', 'gender', 'country', 'phone', 'website', 'social_links', 'service_location', 'service_radius', 'business_name', 'business_category', 'emergency_contact'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_user_meta(get_current_user_id(), $field, sanitize_text_field($_POST[$field]));
        }
    }

    // HANDLING PASSWORD UPDATE
    if (!empty($_POST['current_password']) || !empty($_POST['new_password']) || !empty($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password     = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo "<script>alert('Please fill all password fields.');</script>";
        } elseif ($new_password !== $confirm_password) {
            echo "<script>alert('New passwords do not match.');</script>";
        } elseif (!wp_check_password($current_password, $current_user->user_pass, $current_user->ID)) {
            echo "<script>alert('Current password is incorrect.');</script>";
        }  elseif (wp_check_password($new_password, $current_user->user_pass, $current_user->ID)) {
            echo "<script>alert('New password cannot be the same as the current password.');</script>";
        }else {
            wp_set_password($new_password, $current_user->ID);
            echo "<script>alert('Password updated successfully. Please log in again.'); window.location.href = '" . wp_logout_url() . "';</script>";
            exit;
        }
    }

    echo '<script>window.location.href = window.location.href;</script>';
    exit;
}

$full_name = get_meta('full_name') ?: $current_user->user_login;
$email = $current_user->user_email;
$profile_picture = get_meta('profile_picture') ?: 'https://via.placeholder.com/120x120?text=No+Image';

$nickname           = get_meta('nickname');
$gender             = get_meta('gender');
$country            = get_meta('service_location');
$phone              = get_meta('phone');
$website            = get_meta('website');
$social_links        = get_meta('social_links');
$service_location   = get_meta('service_location');
$service_radius     = get_meta('service_radius');
$business_name      = get_meta('business_name');
$business_category  = get_meta('business_category');
$emergency_contact  = get_meta('emergency_contact');
$portfolio_file     = get_meta('portfolio_upload');
$national_id        = get_meta('national_id');
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
    }
    .form-label {
        font-weight: 500;
    }
    .img-preview {
        max-width: 100%;
        height: auto;
        display: block;
    }
</style>

<div class="container mt-5">
    <div class="card profile-card p-4" id="profile-dashboard">
        <div class="d-flex align-items-center mb-4">
            <img src="<?= esc_url($profile_picture) ?>" class="profile-avatar me-3">
            <div>
                <h4 class="mb-1"><?= esc_html($full_name) ?></h4>
                <small class="text-muted">Email: <?= esc_html($email) ?></small>
            </div>
            <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#editModal">Edit Profile</button>
        </div>

        <div class="row">
            <?php
            $fields_display = [
                'Nickname' => $nickname,
                'Gender' => $gender,
                'Country' => $country,
                'Phone' => $phone,
                'Website' => $website,
                'Social Links' => $social_links,
                'Service Location' => $service_location,
                'Service Radius' => $service_radius,
                'Business Name' => $business_name,
                'Business Category' => $business_category,
                'Emergency Contact' => $emergency_contact,
                'Portfolio File' => $portfolio_file,
                'National ID' => $national_id
            ];

            foreach ($fields_display as $label => $value):
                $is_image = ($label === 'Portfolio File') && filter_var($value, FILTER_VALIDATE_URL);
                $is_file  = ($label === 'National ID') && filter_var($value, FILTER_VALIDATE_URL);
            ?>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><?= $label ?></label>
                    <div class="form-control bg-light">
                        <?php if ($is_image): ?>
                            <img src="<?= esc_url($value) ?>" alt="Portfolio" class="img-preview">
                        <?php elseif ($is_file): ?>
                            <a href="<?= esc_url($value) ?>" target="_blank">View <?= $label ?></a>
                        <?php else: ?>
                            <?= $value ? esc_html($value) : '—' ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Nickname</label><input type="text" name="nickname" class="form-control" value="<?= esc_attr($nickname) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Gender</label><input type="text" name="gender" class="form-control" value="<?= esc_attr($gender) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="<?= esc_attr($country) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= esc_attr($phone) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Website</label><input type="text" name="website" class="form-control" value="<?= esc_attr($website) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Social Links</label><input type="text" name="social_links" class="form-control" value="<?= esc_attr($social_links) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Service Location</label><input type="text" name="service_location" class="form-control" value="<?= esc_attr($service_location) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Service Radius</label><input type="text" name="service_radius" class="form-control" value="<?= esc_attr($service_radius) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Business Name</label><input type="text" name="business_name" class="form-control" value="<?= esc_attr($business_name) ?>"></div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Business Category</label>
                            <select name="business_category" class="form-select">
                                <option value="">Select</option>
                                <option value="Plumbing" <?= $business_category === 'Plumbing' ? 'selected' : '' ?>>Plumbing</option>
                                <option value="Electrical" <?= $business_category === 'Electrical' ? 'selected' : '' ?>>Electrical</option>
                                <option value="Cleaning" <?= $business_category === 'Cleaning' ? 'selected' : '' ?>>Cleaning</option>
                                <option value="Other" <?= $business_category === 'Other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3"><label class="form-label">Emergency Contact</label><input type="text" name="emergency_contact" class="form-control" value="<?= esc_attr($emergency_contact) ?>"></div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Portfolio File (Upload Image)</label>
                            <input type="file" name="portfolio_upload" class="form-control" accept="image/*">
                        </div>
                        <hr>
                        <h6>Change Password</h6>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_profile" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
