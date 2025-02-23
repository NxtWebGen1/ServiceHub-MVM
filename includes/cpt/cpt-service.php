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