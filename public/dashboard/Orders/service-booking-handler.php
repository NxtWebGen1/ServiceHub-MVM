<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Handle the service booking form submission
function servicehub_mvm_handle_booking_submission() {
    ob_clean(); // clean any accidental output
header('Content-Type: application/json'); // ensure JSON response

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
        $vendor_id = !empty($_POST['vendor_id']) ? intval($_POST['vendor_id']) : get_post_field('post_author', $service_id);
        $vendor_email = get_the_author_meta('user_email', $vendor_id); // Get vendor's email

        if (empty($name) || empty($email) || empty($phone) || empty($date)) {
            wp_send_json_error(['message' => 'Please fill in all required fields.']);
        }

        // Create a new order post
        $order_id = wp_insert_post([
            'post_title'  => 'Booking: ' . $name,
            'post_type'   => 'service_orders',
            'post_status' => 'publish',
            'meta_input'  => [
                '_service_id'  => $service_id,
                '_vendor_id' => $vendor_id,
                '_customer_name' => $name,
                '_customer_email' => $email,
                '_customer_phone' => $phone,
                '_customer_address' => $address,
                '_preferred_date' => $date,
                '_order_notes' => $message,
                '_order_status' => 'pending',
            ],
        ]);

        if ($order_id) {
            // Admin Email
            $admin_email = get_option('admin_email');
            $admin_subject = "New Service Booking Request";
            $admin_body = "
                <h2>New Booking Submitted</h2>
                <p><strong>Service ID:</strong> $service_id</p>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Address:</strong> $address</p>
                <p><strong>Preferred Date:</strong> $date</p>
                <p><strong>Message:</strong> $message</p>
                <p>Please check the dashboard for details.</p>";
            
            // Vendor Email
            $vendor_subject = "New Order Received for Your Service";
            $vendor_body = "
                <h2>You Have a New Order</h2>
                <p><strong>Service ID:</strong> $service_id</p>
                <p><strong>Customer Name:</strong> $name</p>
                <p><strong>Customer Email:</strong> $email</p>
                <p><strong>Customer Phone:</strong> $phone</p>
                <p><strong>Preferred Date:</strong> $date</p>
                <p><strong>Message from Customer:</strong> $message</p>
                <p>Login to your vendor dashboard to manage this order.</p>";
            
            // Customer Email
            $customer_subject = "Your Order Confirmation - Service ID: $service_id";
            $customer_body = "
                <h2>Order Confirmation</h2>
                <p>Hi $name,</p>
                <p>Thank you for booking our service. Here are your order details:</p>
                <p><strong>Service ID:</strong> $service_id</p>
                <p><strong>Vendor Contact:</strong> $vendor_email</p>
                <p><strong>Preferred Date:</strong> $date</p>
                <p><strong>Your Message:</strong> $message</p>
                <p>The vendor will contact you soon for further details.</p>";

            // Email headers
            $headers = ['Content-Type: text/html; charset=UTF-8'];

            // Send response FIRST
wp_send_json_success(['message' => 'Booking request submitted successfully!']);

// Defer emails to run after response
add_action('shutdown', function () use (
    $admin_email, $vendor_email, $email,
    $admin_subject, $admin_body,
    $vendor_subject, $vendor_body,
    $customer_subject, $customer_body,
    $headers
) {
    wp_mail($admin_email, $admin_subject, $admin_body, $headers);
    wp_mail($vendor_email, $vendor_subject, $vendor_body, $headers);
    wp_mail($email, $customer_subject, $customer_body, $headers);
});
exit;

        } else {
            wp_send_json_error(['message' => 'Failed to submit booking.']);
        }
    }
}

// Hook into AJAX (for logged-in and non-logged-in users)
add_action('wp_ajax_servicehub_mvm_book_service', 'servicehub_mvm_handle_booking_submission');
add_action('wp_ajax_nopriv_servicehub_mvm_book_service', 'servicehub_mvm_handle_booking_submission');
