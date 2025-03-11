<?php
/**
 * Handles plugin activation tasks.
 *
 * @since 1.0.0
 */
//ADD CUSTOM VENDOR USER ROLE
 function servicehub_mvm_activate() {
    add_role('vendor', 'Vendor', [
        'read'                   => true,
        'upload_files'           => true,
        'edit_posts'             => true,
        'delete_posts'           => true,
        'publish_posts'          => true,
        'edit_published_posts'   => true,
        'delete_published_posts' => true,
    ]);
}





// Create Login and Registration pages for VENDOR REGISTRATION
function servicehub_mvm_create_pages() {

    // Create Login Page if not exists
    if (!get_page_by_title('Vendor Login')) {
        wp_insert_post([
            'post_title'    => 'Vendor Login',
            'post_content'  => '[vendor_login_form]', // Login form shortcode
            'post_status'   => 'publish',
            'post_type'     => 'page'
        ]);
    }

    // Create Registration Page if not exists
    if (!get_page_by_title('Vendor Registration')) {
        wp_insert_post([
            'post_title'    => 'Vendor Registration',
            'post_content'  => '[vendor_registration_form]', // Registration form shortcode
            'post_status'   => 'publish',
            'post_type'     => 'page'
        ]);
    }

    // Check if a page with the slug 'vendor-registration-pending' already exists
    if (!get_page_by_path('vendor-registration-pending')) {
        // Create the page
        wp_insert_post([
            'post_title'   => 'Vendor Registration Pending',
            'post_name'    => 'vendor-registration-pending',
            'post_content' => 'Thank you for registering! Your account is under review. We will notify you once approved.',
            'post_status'  => 'publish',
            'post_type'    => 'page'
        ]);
    }
}
