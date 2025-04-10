<?php
// Redirect if user not logged in
if (!is_user_logged_in()) {
    echo '<div class="container py-5"><div class="alert alert-warning">Please log in to edit a service.</div></div>';
    return;
}

$service_id = isset($_GET['edit_service']) ? intval($_GET['edit_service']) : 0;
if (!$service_id || get_post_type($service_id) !== 'service') {
    echo '<div class="container py-5"><div class="alert alert-danger">Invalid service.</div></div>';
    return;
}

$current_vendor_id = get_current_user_id();
$service_post = get_post($service_id);

if (!$service_post || $service_post->post_author != $current_vendor_id) {
    echo '<div class="container py-5"><div class="alert alert-danger">You do not have permission to edit this service.</div></div>';
    return;
}

// Retrieve meta
$service_title            = esc_attr($service_post->post_title);
$service_description      = esc_textarea($service_post->post_content);
$service_price            = esc_attr(get_post_meta($service_id, '_service_price', true));
$service_price_unit       = esc_attr(get_post_meta($service_id, '_service_price_unit', true));
$service_discount_price   = esc_attr(get_post_meta($service_id, '_service_discount_price', true));
$service_duration         = esc_attr(get_post_meta($service_id, '_service_duration', true));
$service_availability     = esc_attr(get_post_meta($service_id, '_service_schedule', true));
$service_payment_type     = esc_attr(get_post_meta($service_id, '_service_payment_type', true));
$service_availability_type= esc_attr(get_post_meta($service_id, '_service_availability_type', true));
$service_max_bookings     = esc_attr(get_post_meta($service_id, '_service_max_bookings', true));
$service_status           = esc_attr(get_post_meta($service_id, '_service_status', true));
$service_location         = wp_get_post_terms($service_id, 'service_location', ['fields' => 'ids']) ?: [];
$service_type             = wp_get_post_terms($service_id, 'service_type', ['fields' => 'ids']) ?: [];

?>

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .form-card {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 2rem;
        background-color: #fff;
        box-shadow: 0 .125rem .25rem rgba(0, 0, 0, 0.05);
    }
    .form-section-title {
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: .5rem;
    }
</style>

<div class="container py-5">
    <div class="form-card mx-auto" style="max-width: 900px;">
        <h3 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Service</h3>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="service_id" value="<?php echo esc_attr($service_id); ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_title" class="form-label">Service Title</label>
                    <input type="text" class="form-control" id="service_title" name="service_title" value="<?php echo $service_title; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_duration" class="form-label">Service Duration</label>
                    <input type="text" class="form-control" id="service_duration" name="service_duration" value="<?php echo $service_duration; ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="service_description" class="form-label">Description</label>
                <textarea class="form-control" id="service_description" name="service_description" rows="4" required><?php echo $service_description; ?></textarea>
            </div>

            <h5 class="form-section-title">💰 Pricing & Payment</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="service_price" class="form-label">Price</label>
                    <input type="text" class="form-control" id="service_price" name="service_price" value="<?php echo $service_price; ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="service_discount_price" class="form-label">Discount Price</label>
                    <input type="number" class="form-control" id="service_discount_price" name="service_discount_price" value="<?php echo $service_discount_price; ?>" min="0">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="service_price_unit" class="form-label">Price Unit</label>
                    <select class="form-select" id="service_price_unit" name="service_price_unit">
                        <?php
                        $price_units = ['Fixed Price', 'Per Hour', 'Per Day', 'Per Session'];
                        foreach ($price_units as $unit) {
                            echo '<option value="' . $unit . '" ' . selected($service_price_unit, $unit, false) . '>' . $unit . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_payment_type" class="form-label">Payment Type</label>
                    <select class="form-select" id="service_payment_type" name="service_payment_type">
                        <?php
                        $payment_options = ['One-Time Payment', 'Recurring (Monthly)', 'Recurring (Weekly)'];
                        foreach ($payment_options as $option) {
                            echo '<option value="' . $option . '" ' . selected($service_payment_type, $option, false) . '>' . $option . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_max_bookings" class="form-label">Max Bookings per Day</label>
                    <input type="number" class="form-control" id="service_max_bookings" name="service_max_bookings" value="<?php echo $service_max_bookings; ?>" min="1">
                </div>
            </div>

            <h5 class="form-section-title">📍 Location & Type</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_location" class="form-label">Service Location</label>
                    <select class="form-select" id="service_location" name="service_location">
                        <?php
                        $locations = get_terms(['taxonomy' => 'service_location', 'hide_empty' => false]);
                        foreach ($locations as $location) {
                            echo '<option value="' . $location->term_id . '" ' . selected(in_array($location->term_id, $service_location), true) . '>' . esc_html($location->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_type" class="form-label">Service Type</label>
                    <select class="form-select" id="service_type" name="service_type">
                        <?php
                        $types = get_terms(['taxonomy' => 'service_type', 'hide_empty' => false]);
                        foreach ($types as $type) {
                            echo '<option value="' . $type->term_id . '" ' . selected(in_array($type->term_id, $service_type), true) . '>' . esc_html($type->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_availability_type" class="form-label">Availability Type</label>
                    <select class="form-select" id="service_availability_type" name="service_availability_type">
                        <?php
                        $availability_options = ['Online Service', 'In-Person Service', 'Both'];
                        foreach ($availability_options as $option) {
                            echo '<option value="' . esc_attr($option) . '" ' . selected($service_availability_type, $option, false) . '>' . esc_html($option) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_status" class="form-label">Service Status</label>
                    <select class="form-select" id="service_status" name="service_status">
                        <option value="Active" <?php selected($service_status, 'Active'); ?>>Active</option>
                        <option value="Inactive" <?php selected($service_status, 'Inactive'); ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="service_availability" class="form-label">Availability Schedule</label>
                <input type="text" class="form-control" id="service_availability" name="service_availability" value="<?php echo $service_availability; ?>">
            </div>

            <h5 class="form-section-title">🖼️ Media</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="service_image" class="form-label">Featured Image</label>
                    <input type="file" class="form-control" id="service_image" name="service_image">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="service_gallery" class="form-label">Gallery Images</label>
                    <input type="file" class="form-control" id="service_gallery" name="service_gallery[]" multiple>
                </div>
            </div>

            <?php wp_nonce_field('edit_service_action', 'edit_service_nonce'); ?>

            <div class="mt-4 d-flex justify-content-between">
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save2 me-1"></i> Update Service
                </button>
                <a href="?page=vendor-dashboard&tab=services" class="btn btn-outline-secondary px-4">Cancel</a>
            </div>
        </form>
    </div>
</div>
