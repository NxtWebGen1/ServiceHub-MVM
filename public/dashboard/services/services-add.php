<?php
// Start output buffering to prevent "headers already sent" issues
ob_start();

// Ensure the user is logged in before allowing access
if (!is_user_logged_in()) {
    echo '<p>Please log in to add a service.</p>';
    return;
}

$current_vendor_id = get_current_user_id();
$errors = [];

// Default values (empty for new service)
$service_data = [
    'title'        => '',
    'description'  => '',
    'price'        => '',
    'availability' => '',
    'location'     => '',
    'service_type' => '',
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_title'])) {
    if (!isset($_POST['add_service_nonce']) || !wp_verify_nonce($_POST['add_service_nonce'], 'add_service_action')) {
        wp_die('Security check failed.');
    }

    // Sanitize inputs
    $title = sanitize_text_field($_POST['service_title']);
    $description = sanitize_textarea_field($_POST['service_description']);
    $price = isset($_POST['service_price']) ? sanitize_text_field($_POST['service_price']) : '';
    $availability = sanitize_text_field($_POST['service_availability']);
    $location = isset($_POST['service_location']) ? intval($_POST['service_location']) : 0;
    $service_type = isset($_POST['service_type']) ? intval($_POST['service_type']) : 0;

    if (empty($title) || empty($description) || empty($availability) || empty($location) || empty($service_type)) {
        $errors[] = 'Please fill in all required fields.';
    }

    if (empty($errors)) {
        $new_service = [
            'post_title'   => $title,
            'post_content' => $description,
            'post_status'  => 'publish',
            'post_type'    => 'service',
            'post_author'  => $current_vendor_id,
        ];

        $post_id = wp_insert_post($new_service);

        if ($post_id) {
            update_post_meta($post_id, '_service_price', $price);
            update_post_meta($post_id, '_service_schedule', $availability);

            if ($location) {
                wp_set_post_terms($post_id, [$location], 'service_location');
            }
            if ($service_type) {
                wp_set_post_terms($post_id, [$service_type], 'service_type');
            }

            if (!empty($_FILES['service_image']['name'])) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';

                $attachment_id = media_handle_upload('service_image', $post_id);

                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }
            
            // Handle Gallery Uploads
            if (!empty($_FILES['service_gallery']['name'][0])) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';

                $gallery_ids = [];

                foreach ($_FILES['service_gallery']['name'] as $key => $value) {
                    if ($_FILES['service_gallery']['name'][$key]) {
                        $file = [
                            'name'     => $_FILES['service_gallery']['name'][$key],
                            'type'     => $_FILES['service_gallery']['type'][$key],
                            'tmp_name' => $_FILES['service_gallery']['tmp_name'][$key],
                            'error'    => $_FILES['service_gallery']['error'][$key],
                            'size'     => $_FILES['service_gallery']['size'][$key]
                        ];

                        $_FILES['gallery_temp'] = $file;
                        $attachment_id = media_handle_upload('gallery_temp', $post_id);

                        if (!is_wp_error($attachment_id)) {
                            $gallery_ids[] = $attachment_id;
                        }
                    }
                }

                if (!empty($gallery_ids)) {
                    update_post_meta($post_id, '_service_gallery', $gallery_ids);
                }
            }

            // 🚀 Send Notification to Admin
                $admin_email = get_option('admin_email');
                $vendor_info = get_userdata($current_vendor_id);

                $subject = "New Service Added: " . get_the_title($post_id);
                $message = "Hello Admin,\n\n";
                $message .= "A new service has been added by Vendor: " . $vendor_info->display_name . " (" . $vendor_info->user_email . ").\n\n";
                $message .= "Service Details:\n";
                $message .= "Title: " . get_the_title($post_id) . "\n";
                $message .= "Description: " . get_the_excerpt($post_id) . "\n";
                $message .= "Price: " . get_post_meta($post_id, '_service_price', true) . "\n";
                $message .= "View Service: " . get_permalink($post_id) . "\n\n";
                $message .= "Regards,\nYour Website Team";
                
                wp_mail($admin_email, $subject, $message);


            ob_end_clean();
            // wp_safe_redirect(admin_url('admin.php?page=vendor-dashboard&tab=services&success=1'));
            echo '<script>window.location.href = "' . admin_url('admin.php?page=vendor-dashboard&tab=services') . '";</script>';            
            exit;
        } else {
            $errors[] = 'Failed to add service. Please try again.';
        }
    }
}

// Stop output buffering
ob_end_flush();

// Load form template (used for both add & edit)
include SERVICEHUB_MVM_PLUGIN_PATH . 'public/templates/vendor/add-service.php';

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<p class="alert alert-danger">' . esc_html($error) . '</p>';
    }
}
?>
