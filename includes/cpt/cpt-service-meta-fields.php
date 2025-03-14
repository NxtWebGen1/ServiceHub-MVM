<?php
// ========================================= Registering PRICE METABOX =================================================

// Register Meta Box for Price
function servicehub_mvm_register_price_meta_box() {
    add_meta_box('service_price_meta_box', 'Service Price', 'servicehub_mvm_price_meta_box_callback', 'service', 'side');
}
add_action('add_meta_boxes', 'servicehub_mvm_register_price_meta_box');

// Meta Box Callback
function servicehub_mvm_price_meta_box_callback($post) {
    $price = get_post_meta($post->ID, '_service_price', true);
    echo '<label for="service_price">Price:</label>';
    echo '<input type="number" id="service_price" name="service_price" value="' . esc_attr($price) . '" style="width:100%;" />';
}

// Save Price Meta Box Data
function servicehub_mvm_save_price_meta_box($post_id) {
    if (isset($_POST['service_price'])) {
        update_post_meta($post_id, '_service_price', sanitize_text_field($_POST['service_price']));
    }
}
add_action('save_post', 'servicehub_mvm_save_price_meta_box');


// ========================================= Registering AVAILABILITY METABOX =================================================

// Register Meta Box for Availability Schedule
function servicehub_mvm_register_schedule_meta_box() {
    add_meta_box('service_schedule_meta_box', 'Availability Schedule', 'servicehub_mvm_schedule_meta_box_callback', 'service', 'normal');
}
add_action('add_meta_boxes', 'servicehub_mvm_register_schedule_meta_box');

// Meta Box Callback
function servicehub_mvm_schedule_meta_box_callback($post) {
    $schedule = get_post_meta($post->ID, '_service_schedule', true);
    echo '<label for="service_schedule">Enter Availability Schedule:</label>';
    echo '<textarea id="service_schedule" name="service_schedule" rows="5" style="width:100%;">' . esc_textarea($schedule) . '</textarea>';
}

// Save Schedule Meta Box Data
function servicehub_mvm_save_schedule_meta_box($post_id) {
    if (isset($_POST['service_schedule'])) {
        update_post_meta($post_id, '_service_schedule', sanitize_textarea_field($_POST['service_schedule']));
    }
}
add_action('save_post', 'servicehub_mvm_save_schedule_meta_box');

// ========================================= Registering Additional Service Meta Boxes =================================================
function servicehub_mvm_register_service_meta_boxes() {
    add_meta_box('service_duration_meta_box', 'Service Duration', 'servicehub_mvm_duration_meta_box_callback', 'service', 'normal');
    add_meta_box('service_discount_price_meta_box', 'Discount Price', 'servicehub_mvm_discount_price_meta_box_callback', 'service', 'normal');
    add_meta_box('service_status_meta_box', 'Service Status', 'servicehub_mvm_status_meta_box_callback', 'service', 'side');
    add_meta_box('service_payment_type_meta_box', 'Payment Type', 'servicehub_mvm_payment_type_meta_box_callback', 'service', 'normal');
    add_meta_box('service_availability_type_meta_box', 'Availability Type', 'servicehub_mvm_availability_type_meta_box_callback', 'service', 'normal');
    add_meta_box('service_max_bookings_meta_box', 'Max Bookings Per Day', 'servicehub_mvm_max_bookings_meta_box_callback', 'service', 'normal');
}
add_action('add_meta_boxes', 'servicehub_mvm_register_service_meta_boxes');

// Service Duration Meta Box
function servicehub_mvm_duration_meta_box_callback($post) {
    $duration = get_post_meta($post->ID, '_service_duration', true);
    echo '<label for="service_duration">Service Duration:</label>';
    echo '<input type="text" id="service_duration" name="service_duration" value="' . esc_attr($duration) . '" style="width:100%;" placeholder="e.g., 30 mins, 1 hour">';
}

// Discount Price Meta Box
function servicehub_mvm_discount_price_meta_box_callback($post) {
    $discount_price = get_post_meta($post->ID, '_service_discount_price', true);
    echo '<label for="service_discount_price">Discount Price:</label>';
    echo '<input type="number" id="service_discount_price" name="service_discount_price" value="' . esc_attr($discount_price) . '" style="width:100%;" min="0">';
}

// Service Status Meta Box
function servicehub_mvm_status_meta_box_callback($post) {
    $status = get_post_meta($post->ID, '_service_status', true);
    echo '<label for="service_status">Service Status:</label>';
    echo '<select id="service_status" name="service_status" style="width:100%;">';
    $options = ['Active', 'Inactive'];
    foreach ($options as $option) {
        echo '<option value="' . $option . '" ' . selected($status, $option, false) . '>' . $option . '</option>';
    }
    echo '</select>';
}

// Payment Type Meta Box
function servicehub_mvm_payment_type_meta_box_callback($post) {
    $payment_type = get_post_meta($post->ID, '_service_payment_type', true);
    echo '<label for="service_payment_type">Payment Type:</label>';
    echo '<select id="service_payment_type" name="service_payment_type" style="width:100%;">';
    $options = ['One-Time Payment', 'Recurring (Monthly)', 'Recurring (Weekly)'];
    foreach ($options as $option) {
        echo '<option value="' . $option . '" ' . selected($payment_type, $option, false) . '>' . $option . '</option>';
    }
    echo '</select>';
}

// Service Availability Meta Box
function servicehub_mvm_availability_type_meta_box_callback($post) {
    $availability_type = get_post_meta($post->ID, '_service_availability_type', true);
    echo '<label for="service_availability_type">Availability Type:</label>';
    echo '<select id="service_availability_type" name="service_availability_type" style="width:100%;">';
    $options = ['Online Service', 'In-Person Service', 'Both'];
    foreach ($options as $option) {
        echo '<option value="' . $option . '" ' . selected($availability_type, $option, false) . '>' . $option . '</option>';
    }
    echo '</select>';
}

// Max Bookings Meta Box
function servicehub_mvm_max_bookings_meta_box_callback($post) {
    $max_bookings = get_post_meta($post->ID, '_service_max_bookings', true);
    echo '<label for="service_max_bookings">Max Number of Bookings per Day:</label>';
    echo '<input type="number" id="service_max_bookings" name="service_max_bookings" value="' . esc_attr($max_bookings) . '" style="width:100%;" min="1">';
}

add_action('save_post', 'servicehub_mvm_save_schedule_meta_box');
