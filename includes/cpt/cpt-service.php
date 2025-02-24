<?php
// Register the CPT
function servicehub_mvm_register_service_cpt() {
    register_post_type('service', array(
        'labels' => array(
            'name' => 'Services',
            'singular_name' => 'Service',
            'add_new' => 'Add New Service',
            'add_new_item' => 'Add New Service',
            'edit_item' => 'Edit Service',
            'new_item' => 'New Service',
            'view_item' => 'View Service',
            'search_items' => 'Search Services',
            'not_found' => 'No services found',
            'not_found_in_trash' => 'No services found in Trash'
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'services'),
        'supports' => array('title', 'editor', 'thumbnail', 'gallery'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_rest' => true
    ));
}
// Hook into WordPress init to register CPT
add_action('init', 'servicehub_mvm_register_service_cpt');








// =========================================Registering PRICE METABOX=================================================
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




// =========================================Registering AVAILABILITY METABOX=================================================
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









// =========================================Registering SERVICE TYPE TAXONOMY =================================================
// Register Service Types Taxonomy
function servicehub_mvm_register_service_types_taxonomy() {
    register_taxonomy('service_type', 'service', array(
        'labels' => array(
            'name' => 'Service Types',
            'singular_name' => 'Service Type',
            'search_items' => 'Search Service Types',
            'all_items' => 'All Service Types',
            'parent_item' => 'Parent Service Type',
            'parent_item_colon' => 'Parent Service Type:',
            'edit_item' => 'Edit Service Type',
            'update_item' => 'Update Service Type',
            'add_new_item' => 'Add New Service Type',
            'new_item_name' => 'New Service Type Name',
            'menu_name' => 'Service Types',
        ),
        'hierarchical' => true, // Like categories (true), false would behave like tags
        'show_admin_column' => true,
        'show_in_rest' => true, // For Gutenberg and REST API
        'rewrite' => array('slug' => 'service-type'),
    ));
}
add_action('init', 'servicehub_mvm_register_service_types_taxonomy');




// =========================================Registering SERVICE LOCATION TAXONOMY =================================================
// Register Service Location Taxonomy
function servicehub_mvm_register_service_Location_taxonomy() {
    register_taxonomy('service_location', 'service', array(
        'labels' => array(
            'name' => 'Service Location',
            'singular_name' => 'Service Location',
            'search_items' => 'Search Location',
            'all_items' => 'All Service Location',
            'parent_item' => 'Parent Service Location',
            'parent_item_colon' => 'Parent Service Location:',
            'edit_item' => 'Edit Service Location',
            'update_item' => 'Update Service Location',
            'add_new_item' => 'Add New Service Location',
            'new_item_name' => 'New Service Location Name',
            'menu_name' => 'Service Location',
        ),
        'hierarchical' => true, // Like categories (true), false would behave like tags
        'show_admin_column' => true,
        'show_in_rest' => true, // For Gutenberg and REST API
        'rewrite' => array('slug' => 'service-location'),
    ));
}
add_action('init', 'servicehub_mvm_register_service_location_taxonomy');
