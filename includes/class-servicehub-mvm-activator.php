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
    add_role('customer', 'Customer', [
        'read' => true, // Basic access
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

        // Create Customer Login Page if not exists
        if (!get_page_by_title('Customer Login')) {
            wp_insert_post([
                'post_title'    => 'Customer Login',
                'post_content'  => '[customer_login_form]',
                'post_status'   => 'publish',
                'post_type'     => 'page'
            ]);
        }
    
        // Create Customer Registration Page if not exists
        if (!get_page_by_title('Customer Registration')) {
            wp_insert_post([
                'post_title'    => 'Customer Registration',
                'post_content'  => '[customer_registration_form]',
                'post_status'   => 'publish',
                'post_type'     => 'page'
            ]);
        }
    
        // Create Customer Dashboard Page if not exists
if (!get_page_by_title('Customer Dashboard')) {
    wp_insert_post([
        'post_title'    => 'Customer Dashboard',
        'post_content'  => '[customer_dashboard]', // Shortcode we’ll create
        'post_status'   => 'publish',
        'post_type'     => 'page'
    ]);
}

        // Create Archive Service Page if not exists
        if (!get_page_by_title('All Services')) {
            wp_insert_post([
                'post_title'    => 'Services',
                'post_content'  => '', 
                'post_status'   => 'publish',
                'post_type'     => 'page'
            ]);
        }
        

}

