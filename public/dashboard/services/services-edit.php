<?php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_service_nonce'])) {
    if (!wp_verify_nonce($_POST['edit_service_nonce'], 'edit_service_action')) {
        die('Security check failed');
    }

    $service_id = intval($_POST['service_id']);
    if (!$service_id || get_post_type($service_id) !== 'service') {
        die('Invalid service ID');
    }

    // Ensure the user is the owner of the service
    $current_vendor_id = get_current_user_id();
    $service_post = get_post($service_id);
    if ($service_post->post_author != $current_vendor_id) {
        die('You do not have permission to edit this service.');
    }

    // Sanitize & update fields
    $service_title = sanitize_text_field($_POST['service_title']);
    $service_description = sanitize_textarea_field($_POST['service_description']);
    $service_price = sanitize_text_field($_POST['service_price']);
    $service_price_unit = sanitize_text_field($_POST['service_price_unit']);
    $service_discount_price = sanitize_text_field($_POST['service_discount_price']);
    $service_duration = sanitize_text_field($_POST['service_duration']);
    $service_availability = sanitize_text_field($_POST['service_availability']);
    $service_payment_type = sanitize_text_field($_POST['service_payment_type']);
    $service_availability_type = sanitize_text_field($_POST['service_availability_type']);
    $service_max_bookings = sanitize_text_field($_POST['service_max_bookings']);
    $service_status = sanitize_text_field($_POST['service_status']);
    $service_location = intval($_POST['service_location']);
    $service_type = intval($_POST['service_type']);

    wp_update_post(array(
        'ID' => $service_id,
        'post_title' => $service_title,
        'post_content' => $service_description,
    ));

    update_post_meta($service_id, '_service_price', $service_price);
    update_post_meta($service_id, '_service_price_unit', $service_price_unit);
    update_post_meta($service_id, '_service_discount_price', $service_discount_price);
    update_post_meta($service_id, '_service_duration', $service_duration);
    update_post_meta($service_id, '_service_schedule', $service_availability);
    update_post_meta($service_id, '_service_payment_type', $service_payment_type);
    update_post_meta($service_id, '_service_availability_type', $service_availability_type);
    update_post_meta($service_id, '_service_max_bookings', $service_max_bookings);
    update_post_meta($service_id, '_service_status', $service_status);
    wp_set_post_terms($service_id, array($service_location), 'service_location');
    wp_set_post_terms($service_id, array($service_type), 'service_type');

    // Handle featured image upload
    if (!empty($_FILES['service_image']['name'])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_id = media_handle_upload('service_image', $service_id);
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($service_id, $attachment_id);
        }
    }

    // Handle gallery images upload
    if (!empty($_FILES['service_gallery']['name'][0])) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Get existing gallery images and ensure it's an array
        $existing_gallery = get_post_meta($service_id, '_service_gallery', true);
        $existing_gallery = is_array($existing_gallery) ? $existing_gallery : [];

        $new_gallery_images = [];

        foreach ($_FILES['service_gallery']['name'] as $key => $value) {
            if (!empty($_FILES['service_gallery']['name'][$key])) {
                $file = array(
                    'name'     => $_FILES['service_gallery']['name'][$key],
                    'type'     => $_FILES['service_gallery']['type'][$key],
                    'tmp_name' => $_FILES['service_gallery']['tmp_name'][$key],
                    'error'    => $_FILES['service_gallery']['error'][$key],
                    'size'     => $_FILES['service_gallery']['size'][$key],
                );

                // Upload the file and attach it to the service
                $file_id = media_handle_sideload($file, $service_id);
                if (!is_wp_error($file_id)) {
                    $new_gallery_images[] = $file_id;
                }
            }
        }

        // If new images are uploaded, merge them with existing images
        if (!empty($new_gallery_images)) {
            $updated_gallery = array_merge($existing_gallery, $new_gallery_images);
            update_post_meta($service_id, '_service_gallery', $updated_gallery);
        }
    }

    // Redirect after successful update
    echo '<script>window.location.href = "' . admin_url('admin.php?page=vendor-dashboard&tab=services') . '";</script>';            
    exit;
}

// Include the edit service template
require_once SERVICEHUB_MVM_PLUGIN_PATH . 'public/templates/vendor/edit-service.php';
