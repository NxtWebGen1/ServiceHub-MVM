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
$service_availability = esc_attr(get_post_meta($service_id, '_service_schedule', true));
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
            <label for="service_price" class="form-label">Price (Leave empty for quote)</label>
            <input type="text" class="form-control" id="service_price" name="service_price" value="<?php echo $service_price; ?>">
        </div>

        <div class="mb-3">
            <label for="service_location" class="form-label">Location</label>
            <select class="form-control" id="service_location" name="service_location" required>
                <option value="">Select Location</option>
                <?php 
                    $locations = get_terms(array('taxonomy' => 'service_location', 'hide_empty' => false));
                    foreach ($locations as $loc) {
                        $selected = (!empty($service_location) && in_array($loc->term_id, (array) $service_location)) ? 'selected' : '';
                        echo '<option value="' . esc_attr($loc->term_id) . '" ' . $selected . '>' . esc_html($loc->name) . '</option>';
                    }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_type" class="form-label">Service Type</label>
            <select class="form-control" id="service_type" name="service_type" required>
                <option value="">Select Service Type</option>
                <?php 
                    $service_types = get_terms(array('taxonomy' => 'service_type', 'hide_empty' => false));
                    foreach ($service_types as $type) {
                        $selected = (!empty($service_type) && in_array($type->term_id, (array) $service_type)) ? 'selected' : '';
                        echo '<option value="' . esc_attr($type->term_id) . '" ' . $selected . '>' . esc_html($type->name) . '</option>';
                    }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="service_availability" class="form-label">Availability</label>
            <input type="text" class="form-control" id="service_availability" name="service_availability" value="<?php echo $service_availability; ?>" required>
        </div>

        <div class="mb-3">
            <label for="service_image" class="form-label">Featured Image</label>
            <?php if ($featured_image_url) : ?>
                <div>
                    <img src="<?php echo esc_url($featured_image_url); ?>" alt="Featured Image" class="img-thumbnail mb-2" width="150">
                </div>
            <?php endif; ?>
            <input type="file" class="form-control" id="service_image" name="service_image">
        </div>

        
        <div class="mb-3">
            <label for="service_gallery" class="form-label">Gallery Images</label>

            <?php
            $gallery_images = get_post_meta($service_id, '_service_gallery', true);
            if (!empty($gallery_images) && is_array($gallery_images)) :
                echo '<div class="d-flex flex-wrap">';
                foreach ($gallery_images as $image_id) {
                    $image_url = wp_get_attachment_url($image_id);
                    if ($image_url) {
                        echo '<div class="me-2 mb-2"><img src="' . esc_url($image_url) . '" class="img-thumbnail" width="100"></div>';
                    }
                }
                echo '</div>';
            endif;
            ?>

            <input type="file" class="form-control" id="service_gallery" name="service_gallery[]" multiple>
        </div>


        <?php wp_nonce_field('edit_service_action', 'edit_service_nonce'); ?>

        <button type="submit" class="btn btn-primary">Update Service</button>
        <a href="?page=vendor-dashboard&tab=services" class="btn btn-secondary">Cancel</a>
    </form>
</div>
