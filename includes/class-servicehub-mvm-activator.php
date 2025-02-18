<?php
/**
 * Handles plugin activation tasks.
 *
 * @since 1.0.0
 */

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
// Hook the activation function
register_activation_hook(__FILE__, 'servicehub_mvm_activate');
