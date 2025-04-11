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
        } elseif (wp_check_password($new_password, $current_user->user_pass, $current_user->ID)) {
            echo "<script>alert('New password cannot be the same as the current password.');</script>";
        } else {
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
$social_links       = get_meta('social_links');
$service_location   = get_meta('service_location');
$service_radius     = get_meta('service_radius');
$business_name      = get_meta('business_name');
$business_category  = get_meta('business_category');
$emergency_contact  = get_meta('emergency_contact');
$portfolio_file     = get_meta('portfolio_upload');
$national_id        = get_meta('national_id');
?>


<div class="container py-5">
    <div class="border-0 shadow rounded-4 p-4 mb-4 bg-light">
        <div class="d-flex align-items-center mb-4">
            <img src="<?= esc_url($profile_picture) ?>" class="rounded-circle me-4" style="width: 100px; height: 100px; object-fit: cover;">
            <div>
                <h4 class="fw-bold mb-1">Hello, <?= esc_html($full_name) ?></h4>
                <p class="text-muted mb-0">Email: <?= esc_html($email) ?></p>
            </div>
            <button class="btn btn-primary ms-auto rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="fas fa-edit me-2"></i>Edit Profile
            </button>
        </div>

        <div class="row g-3">
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
            <div class="col-md-6">
                <label class="form-label fw-semibold text-secondary"><?= $label ?></label>
                <div class="bg-white border rounded p-2">
                    <?php if ($is_image): ?>
                        <a href="<?= esc_url($value) ?>" target="_blank">
    <img src="<?= esc_url($value) ?>" alt="Portfolio" class="img-fluid rounded shadow-sm" style="max-height: 120px; object-fit: contain;">
</a>
                    <?php elseif ($is_file): ?>
                        <a href="<?= esc_url($value) ?>" target="_blank" class="text-decoration-underline">View <?= $label ?></a>
                    <?php else: ?>
                        <?= $value ? esc_html($value) : '<span class="text-muted">—</span>' ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>



<!-- Redesigned Edit Profile Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-lg border-0">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header bg-primary text-white rounded-top-4">
          <h5 class="modal-title fw-semibold"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Your Profile</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body bg-light py-4 px-4">
          <div class="row g-4">
            <div class="col-md-6">
              <label class="form-label">Nickname</label>
              <input type="text" name="nickname" class="form-control rounded-3" value="<?= esc_attr($nickname) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <input type="text" name="gender" class="form-control rounded-3" value="<?= esc_attr($gender) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Country</label>
              <input type="text" name="country" class="form-control rounded-3" value="<?= esc_attr($country) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control rounded-3" value="<?= esc_attr($phone) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Website</label>
              <input type="text" name="website" class="form-control rounded-3" value="<?= esc_attr($website) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Social Links</label>
              <input type="text" name="social_links" class="form-control rounded-3" value="<?= esc_attr($social_links) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Service Location</label>
              <input type="text" name="service_location" class="form-control rounded-3" value="<?= esc_attr($service_location) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Service Radius</label>
              <input type="text" name="service_radius" class="form-control rounded-3" value="<?= esc_attr($service_radius) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Business Name</label>
              <input type="text" name="business_name" class="form-control rounded-3" value="<?= esc_attr($business_name) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Business Category</label>
              <select name="business_category" class="form-select rounded-3">
                <option value="">Select</option>
                <option value="Plumbing" <?= $business_category === 'Plumbing' ? 'selected' : '' ?>>Plumbing</option>
                <option value="Electrical" <?= $business_category === 'Electrical' ? 'selected' : '' ?>>Electrical</option>
                <option value="Cleaning" <?= $business_category === 'Cleaning' ? 'selected' : '' ?>>Cleaning</option>
                <option value="Other" <?= $business_category === 'Other' ? 'selected' : '' ?>>Other</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Emergency Contact</label>
              <input type="text" name="emergency_contact" class="form-control rounded-3" value="<?= esc_attr($emergency_contact) ?>">
            </div>
            <div class="col-md-6">
              <label class="form-label">Portfolio File (Upload Image)</label>
              <input type="file" name="portfolio_upload" class="form-control rounded-3" accept="image/*">
            </div>
          </div>

          <hr class="my-4">
          <h6 class="mb-3 fw-bold">Change Password</h6>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Current Password</label>
              <input type="password" name="current_password" class="form-control rounded-3">
            </div>
            <div class="col-md-6">
              <label class="form-label">New Password</label>
              <input type="password" name="new_password" class="form-control rounded-3">
            </div>
            <div class="col-md-6">
              <label class="form-label">Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control rounded-3">
            </div>
          </div>
        </div>

        <div class="modal-footer bg-white rounded-bottom-4">
          <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" name="update_profile" class="btn btn-success rounded-pill px-4">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>