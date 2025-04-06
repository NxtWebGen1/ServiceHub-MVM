<?php



function my_plugin_enqueue_styles() {
    // Enqueue custom dashboard CSS
    wp_enqueue_style(
        'my-plugin-style', 
        plugin_dir_url(dirname(__FILE__)) . 'public/dashboard/dashboard.css',
        array(), 
        time() // Forces cache refresh on every reload
    );

    // Enqueue Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', 
        array(), 
        '5.3.2'
    );

    // Enqueue Bootstrap JS
    wp_enqueue_script(
        'bootstrap-js', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', 
        array('jquery'), 
        '5.3.2', 
        true // Load in footer
    );
}

// Hook styles and scripts properly
add_action('wp_enqueue_scripts', 'my_plugin_enqueue_styles'); // For frontend
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_styles'); // For admin


// Include CPT file
require_once SERVICEHUB_MVM_PLUGIN_PATH . 'includes/cpt/cpt-service.php';
require_once SERVICEHUB_MVM_PLUGIN_PATH . 'includes/cpt/cpt-service-orders.php';



//DISPLAY SINGLE SERVICE TEMPLATE FOR INDIVIDUAL SERVICE
// Load single service template from plugin folder
function servicehub_mvm_load_single_service_template($template) {
    if (is_singular('service')) {
        $plugin_template = SERVICEHUB_MVM_PLUGIN_PATH . 'public/templates/vendor/single-service.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'servicehub_mvm_load_single_service_template');



//including service booking form handler   
require_once SERVICEHUB_MVM_PLUGIN_PATH . 'public/dashboard/orders/service-booking-handler.php';

//INcluding Restrict-vendor-login gile,
require_once plugin_dir_path(__FILE__) . '/restrict-vendor-login.php';


//Including customer login form handler file 
require_once SERVICEHUB_MVM_PLUGIN_PATH . 'public/customers/customer-auth-handler.php';


//register shortcode for customer dashboard
function servicehub_mvm_customer_dashboard_shortcode() {
    ob_start();
    include SERVICEHUB_MVM_PLUGIN_PATH . 'public/customers/customer-dashboard/dashboard-customer.php';
    return ob_get_clean();
}
add_shortcode('customer_dashboard', 'servicehub_mvm_customer_dashboard_shortcode');










//Registers Vendor Public Profile template
// Register custom rewrite rule for vendor profile
function servicehub_mvm_add_vendor_profile_rewrite() {
    add_rewrite_rule(
        '^vendor/([^/]*)/?',
        'index.php?vendor_profile=$matches[1]',
        'top'
    );
}
add_action('init', 'servicehub_mvm_add_vendor_profile_rewrite');

// Add custom query var
function servicehub_mvm_vendor_profile_query_var($vars) {
    $vars[] = 'vendor_profile';
    return $vars;
}
add_filter('query_vars', 'servicehub_mvm_vendor_profile_query_var');

// Template loader for vendor profile
function servicehub_mvm_load_vendor_profile_template($template) {
    $vendor_username = get_query_var('vendor_profile');

    if ($vendor_username) {
        return SERVICEHUB_MVM_PLUGIN_PATH . 'public/templates/vendor/vendor-public-profile.php';
    }

    return $template;
}
add_filter('template_include', 'servicehub_mvm_load_vendor_profile_template');

// Flush rewrite rules on plugin activation
function servicehub_mvm_flush_rewrites_on_activation() {
    servicehub_mvm_add_vendor_profile_rewrite();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'servicehub_mvm_flush_rewrites_on_activation');




