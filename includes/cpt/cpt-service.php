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
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'capability_type' => 'post',
        'map_meta_cap' => true,
        'show_in_rest' => true
    ));
}
// Hook into WordPress init to register CPT
add_action('init', 'servicehub_mvm_register_service_cpt');




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
