<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add Meta Box for Service Orders
function servicehub_mvm_add_service_order_metabox() {
    add_meta_box(
        'service_order_meta_box', // Meta Box ID
        'Service Order Details', // Meta Box Title
        'servicehub_mvm_render_service_order_metabox', // Callback Function
        'service_orders', // Post Type
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'servicehub_mvm_add_service_order_metabox');

// Render Meta Box Fields
function servicehub_mvm_render_service_order_metabox($post) {
    // Retrieve stored values
    $service_id = get_post_meta($post->ID, '_service_id', true);
    $vendor_id = get_post_meta($post->ID, '_vendor_id', true);
    $customer_name = get_post_meta($post->ID, '_customer_name', true);
    $customer_email = get_post_meta($post->ID, '_customer_email', true);
    $customer_phone = get_post_meta($post->ID, '_customer_phone', true);
    $order_status = get_post_meta($post->ID, '_order_status', true);
    $order_notes = get_post_meta($post->ID, '_order_notes', true);

    // Security nonce field
    wp_nonce_field('service_order_nonce_action', 'service_order_nonce');

    ?>
    <p>
        <label for="service_id">Service ID:</label>
        <input type="text" id="service_id" name="service_id" value="<?php echo esc_attr($service_id); ?>" />
    </p>

    <p>
        <label for="vendor_id">Vendor ID:</label>
        <input type="text" id="vendor_id" name="vendor_id" value="<?php echo esc_attr($vendor_id); ?>" />
    </p>

    <p>
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo esc_attr($customer_name); ?>" />
    </p>
    
    <p>
        <label for="customer_email">Customer Email:</label>
        <input type="email" id="customer_email" name="customer_email" value="<?php echo esc_attr($customer_email); ?>" />
    </p>

    <p>
        <label for="customer_phone">Customer Phone:</label>
        <input type="text" id="customer_phone" name="customer_phone" value="<?php echo esc_attr($customer_phone); ?>" />
    </p>

    <p>
        <label for="order_status">Order Status:</label>
        <select id="order_status" name="order_status">
            <option value="pending" <?php selected($order_status, 'pending'); ?>>Pending</option>
            <option value="accepted" <?php selected($order_status, 'accepted'); ?>>Accepted</option>
            <option value="rejected" <?php selected($order_status, 'rejected'); ?>>Rejected</option>
            <option value="completed" <?php selected($order_status, 'completed'); ?>>Completed</option>
        </select>
    </p>

    <p>
        <label for="order_notes">Notes:</label>
        <textarea id="order_notes" name="order_notes"><?php echo esc_textarea($order_notes); ?></textarea>
    </p>
    <?php
}

// Save Meta Box Data
function servicehub_mvm_save_service_order_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['service_order_nonce']) || !wp_verify_nonce($_POST['service_order_nonce'], 'service_order_nonce_action')) {
        return;
    }

    // Check for autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save Fields
    if (isset($_POST['service_id'])) {
        update_post_meta($post_id, '_service_id', sanitize_text_field($_POST['service_id']));
    }

    if (isset($_POST['vendor_id'])) {
        update_post_meta($post_id, '_vendor_id', sanitize_text_field($_POST['vendor_id']));
    }

    if (isset($_POST['customer_name'])) {
        update_post_meta($post_id, '_customer_name', sanitize_text_field($_POST['customer_name']));
    }

    if (isset($_POST['customer_email'])) {
        update_post_meta($post_id, '_customer_email', sanitize_email($_POST['customer_email']));
    }

    if (isset($_POST['customer_phone'])) {
        update_post_meta($post_id, '_customer_phone', sanitize_text_field($_POST['customer_phone']));
    }

    if (isset($_POST['order_status'])) {
        update_post_meta($post_id, '_order_status', sanitize_text_field($_POST['order_status']));
    }

    if (isset($_POST['order_notes'])) {
        update_post_meta($post_id, '_order_notes', sanitize_textarea_field($_POST['order_notes']));
    }
}

add_action('save_post', 'servicehub_mvm_save_service_order_meta');
