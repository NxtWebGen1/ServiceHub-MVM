<?php
// Ensure user is logged in
if (!is_user_logged_in()) {
    echo '<p>Please log in to edit a service.</p>';
    return;
}

// Get the service ID from the URL
$service_id = isset($_GET['edit_service']) ? intval($_GET['edit_service']) : 0;

if (!$service_id || get_post_type($service_id) !== 'service') {
    echo '<p>Invalid service.</p>';
    return;
}

// Ensure the current user is the author of the service
$current_vendor_id = get_current_user_id();
$service_post = get_post($service_id);

if (!$service_post || $service_post->post_author != $current_vendor_id) {
    echo '<p>You do not have permission to edit this service.</p>';
    return;
}

// Fetch existing values
$service_title = esc_attr($service_post->post_title);
$service_description = esc_textarea($service_post->post_content);
$service_price = esc_attr(get_post_meta($service_id, '_service_price', true));
$service_price_unit = esc_attr(get_post_meta($service_id, '_service_price_unit', true));
$service_discount_price = esc_attr(get_post_meta($service_id, '_service_discount_price', true));
$service_duration = esc_attr(get_post_meta($service_id, '_service_duration', true));
$service_availability = esc_attr(get_post_meta($service_id, '_service_schedule', true));
$service_payment_type = esc_attr(get_post_meta($service_id, '_service_payment_type', true));
$service_availability_type = esc_attr(get_post_meta($service_id, '_service_availability_type', true));
$service_max_bookings = esc_attr(get_post_meta($service_id, '_service_max_bookings', true));
$service_status = esc_attr(get_post_meta($service_id, '_service_status', true));
$service_location = wp_get_post_terms($service_id, 'service_location', array('fields' => 'ids'));
$service_type = wp_get_post_terms($service_id, 'service_type', array('fields' => 'ids'));
$featured_image_id = get_post_thumbnail_id($service_id);
$featured_image_url = $featured_image_id ? wp_get_attachment_url($featured_image_id) : '';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

<div class="container mt-4">
    <h2 class="mb-4">Edit Service</h2>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="service_id" value="<?php echo esc_attr($service_id); ?>">
        
        <div class="mb-3">
            <label for="service_title" class="form-label">Service Title</label>
            <input type="text" class="form-control" id="service_title" name="service_title" value="<?php echo $service_title; ?>" required>
        </div>

        <div class="mb-3">
            <label for="service_description" class="form-label">Description</label>
            <textarea class="form-control" id="service_description" name="service_description" rows="4" required><?php echo $service_description; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="service_price" class="form-label">Price</label>
            <input type="text" class="form-control" id="service_price" name="service_price" value="<?php echo $service_price; ?>">
        </div>

        <div class="mb-3">
            <label for="service_price_unit" class="form-label">Price Unit</label>
            <select class="form-control" id="service_price_unit" name="service_price_unit">
                <option value="Fixed Price" <?php selected($service_price_unit, 'Fixed Price'); ?>>Fixed Price</option>
                <option value="Per Hour" <?php selected($service_price_unit, 'Per Hour'); ?>>Per Hour</option>
                <option value="Per Day" <?php selected($service_price_unit, 'Per Day'); ?>>Per Day</option>
                <option value="Per Session" <?php selected($service_price_unit, 'Per Session'); ?>>Per Session</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_discount_price" class="form-label">Discount Price</label>
            <input type="number" class="form-control" id="service_discount_price" name="service_discount_price" value="<?php echo $service_discount_price; ?>" min="0">
        </div>

        <div class="mb-3">
            <label for="service_duration" class="form-label">Service Duration</label>
            <input type="text" class="form-control" id="service_duration" name="service_duration" value="<?php echo $service_duration; ?>" required>
        </div>

        <div class="mb-3">
            <label for="service_availability" class="form-label">Service Availability</label>
            <input type="text" class="form-control" id="service_availability" name="service_availability" value="<?php echo $service_availability; ?>">
        </div>

        <div class="mb-3">
            <label for="service_availability_type" class="form-label">Availability Type</label>
            <select class="form-control" id="service_availability_type" name="service_availability_type">
                <?php
                $availability_options = ['Online Service', 'In-Person Service', 'Both'];
                foreach ($availability_options as $option) {
                    echo '<option value="' . esc_attr($option) . '" ' . selected($service_availability_type, $option, false) . '>' . esc_html($option) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_location" class="form-label">Service Location</label>
            <?php
            $locations = get_terms(['taxonomy' => 'service_location', 'hide_empty' => false]);
            ?>
            <select class="form-control" id="service_location" name="service_location">
                <?php foreach ($locations as $location) { ?>
                    <option value="<?php echo $location->term_id; ?>" <?php selected(in_array($location->term_id, $service_location), true); ?>>
                        <?php echo $location->name; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_type" class="form-label">Service Type</label>
            <?php
            $service_types = get_terms(['taxonomy' => 'service_type', 'hide_empty' => false]);
            ?>
            <select class="form-control" id="service_type" name="service_type">
                <?php foreach ($service_types as $type) { ?>
                    <option value="<?php echo $type->term_id; ?>" <?php selected(in_array($type->term_id, $service_type), true); ?>>
                        <?php echo $type->name; ?>
                    </option>
                <?php } ?>
            </select>
        </div>


        <div class="mb-3">
            <label for="service_max_bookings" class="form-label">Max Number of Bookings per Day</label>
            <input type="number" class="form-control" id="service_max_bookings" name="service_max_bookings" value="<?php echo $service_max_bookings; ?>" min="1">
        </div>

        <div class="mb-3">
            <label for="service_payment_type" class="form-label">Payment Type</label>
            <select class="form-control" id="service_payment_type" name="service_payment_type">
                <?php
                $payment_options = ['One-Time Payment', 'Recurring (Monthly)', 'Recurring (Weekly)'];
                foreach ($payment_options as $option) {
                    echo '<option value="' . esc_attr($option) . '" ' . selected($service_payment_type, $option, false) . '>' . esc_html($option) . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_status" class="form-label">Service Status</label>
            <select class="form-control" id="service_status" name="service_status">
                <?php
                $status_options = ['Active', 'Inactive'];
                foreach ($status_options as $option) {
                    echo '<option value="' . esc_attr($option) . '" ' . selected($service_status, $option, false) . '>' . esc_html($option) . '</option>';
                }
                ?>
            </select>
        </div>


        
        <div class="mb-3">
            <label for="service_image" class="form-label">Featured Image</label>
            <input type="file" class="form-control" id="service_image" name="service_image">
        </div>

        <div class="mb-3">
            <label for="service_gallery" class="form-label">Gallery Images</label>
            <input type="file" class="form-control" id="service_gallery" name="service_gallery[]" multiple>
        </div>

        <?php wp_nonce_field('edit_service_action', 'edit_service_nonce'); ?>

        <button type="submit" class="btn btn-primary">Update Service</button>
        <a href="?page=vendor-dashboard&tab=services" class="btn btn-secondary">Cancel</a>
    </form>
</div>
