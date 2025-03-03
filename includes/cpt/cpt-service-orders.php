<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register the Service Orders CPT
function servicehub_mvm_register_service_orders_cpt() {
    register_post_type('service_orders', array(
        'labels' => array(
            'name' => 'Service Orders',
            'singular_name' => 'Service Order',
            'add_new' => 'Add New Order',
            'add_new_item' => 'Add New Service Order',
            'edit_item' => 'Edit Service Order',
            'new_item' => 'New Service Order',
            'view_item' => 'View Service Order',
            'search_items' => 'Search Service Orders',
            'not_found' => 'No orders found',
            'not_found_in_trash' => 'No orders found in Trash'
        ),
        'public' => false, // Not publicly accessible
        'show_ui' => true, // Show in WP Admin
        'has_archive' => false,
        'supports' => array('title'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_menu' => true,
        'menu_position' => 26,
        'menu_icon' => 'dashicons-list-view',
        'show_in_rest' => true, // Enable for Gutenberg/API
    ));
}

// Hook into WordPress init to register CPT
add_action('init', 'servicehub_mvm_register_service_orders_cpt');



require_once plugin_dir_path(__FILE__) . 'meta-box-service-orders.php';
