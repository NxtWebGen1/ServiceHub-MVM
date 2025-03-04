<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Handle the service booking form submission
function servicehub_mvm_handle_booking_submission() {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_booking_nonce'])) {
        
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['service_booking_nonce'], 'service_booking_action')) {
            wp_send_json_error(['message' => 'Security verification failed.']);
        }

        // Sanitize form inputs
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_textarea_field($_POST['address']);
        $date = sanitize_text_field($_POST['date']);
        $message = sanitize_textarea_field($_POST['message']);
        $service_id = intval($_POST['service_id']);

        if (empty($name) || empty($email) || empty($phone) || empty($date)) {
            wp_send_json_error(['message' => 'Please fill in all required fields.']);
        }

        // Create a new order post
        $order_id = wp_insert_post([
            'post_title'  => 'Booking: ' . $name,
            'post_type'   => 'service_orders',
            'post_status' => 'pending',
            'meta_input'  => [
                '_service_id'  => $service_id,
                '_customer_name' => $name,
                '_customer_email' => $email,
                '_customer_phone' => $phone,
                '_customer_address' => $address,
                '_preferred_date' => $date,
                '_additional_message' => $message,
                '_order_status' => 'pending',
            ],
        ]);

        if ($order_id) {
            // Send confirmation email
            $admin_email = get_option('admin_email');
            $subject = "New Service Booking Request";
            $body = "A new booking has been submitted.\n\n".
                    "Service ID: $service_id\n".
                    "Name: $name\n".
                    "Email: $email\n".
                    "Phone: $phone\n".
                    "Address: $address\n".
                    "Preferred Date: $date\n".
                    "Message: $message\n\n".
                    "Please check the dashboard for details.";

            wp_mail($admin_email, $subject, $body);

            wp_send_json_success(['message' => 'Booking request submitted successfully!']);
        } else {
            wp_send_json_error(['message' => 'Failed to submit booking.']);
        }
    }
}

// Hook into AJAX (for logged-in and non-logged-in users)
add_action('wp_ajax_servicehub_mvm_book_service', 'servicehub_mvm_handle_booking_submission');
add_action('wp_ajax_nopriv_servicehub_mvm_book_service', 'servicehub_mvm_handle_booking_submission');

