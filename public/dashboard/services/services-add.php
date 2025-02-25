<?php
// Ensure the user is logged in before allowing access
if (!is_user_logged_in()) {
    echo '<p>Please log in to add a service.</p>';
    return;
}

$current_vendor_id = get_current_user_id(); // Get the logged-in vendor's ID
$errors = []; // Initialize an empty array to store errors

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify nonce for security
    if (!isset($_POST['add_service_nonce']) || !wp_verify_nonce($_POST['add_service_nonce'], 'add_service_action')) {
        wp_die('Security check failed.');
    }    

    // Sanitize and retrieve input values
    $title = sanitize_text_field($_POST['service_title']);
    $description = sanitize_textarea_field($_POST['service_description']);
    $price = isset($_POST['service_price']) ? sanitize_text_field($_POST['service_price']) : '';
    $availability = sanitize_text_field($_POST['service_availability']);
    $location = sanitize_text_field($_POST['service_location']);

    // Validate required fields
    if (empty($title) || empty($description) || empty($availability) || empty($location)) {
        $errors[] = 'Please fill in all required fields.';
    }

    // If no errors, proceed with inserting the service
    if (empty($errors)) {
        $new_service = array(
            'post_title'   => $title,
            'post_content' => $description,
            'post_status'  => 'publish', // Publish the service immediately
            'post_type'    => 'service', // Custom post type 'service'
            'post_author'  => $current_vendor_id, // Assign the service to the vendor
        );

        $post_id = wp_insert_post($new_service); // Insert the service into the database

        if ($post_id) {
            // Save custom meta fields for price, availability, and location
            update_post_meta($post_id, '_service_price', $price);
            update_post_meta($post_id, '_service_schedule', $availability);
            update_post_meta($post_id, '_service_location', $location);

            // Handle the featured image upload
            if (!empty($_FILES['service_image']['name'])) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';

                $attachment_id = media_handle_upload('service_image', $post_id); // Upload image and attach to the post

                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id); // Set as featured image
                }
            }

            // Redirect to avoid duplicate form submission and show success message
            wp_redirect(add_query_arg('success', '1', $_SERVER['REQUEST_URI']));
            exit;
        } else {
            $errors[] = 'Failed to add service. Please try again.';
        }
    }
}

// Load the existing HTML template
include SERVICEHUB_MVM_PLUGIN_PATH . 'public/templates/vendor/add-service.php';

// Display success message if the service was added successfully
if (isset($_GET['success']) && $_GET['success'] == '1') {
    echo '<p class="alert alert-success">Service added successfully!</p>';
}
// Display any errors if they exist
elseif (!empty($errors)) {
    foreach ($errors as $error) {
        echo '<p class="alert alert-danger">' . esc_html($error) . '</p>';
    }
}
?>
