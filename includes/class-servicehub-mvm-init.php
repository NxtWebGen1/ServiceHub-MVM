<?php


// Enqueue Bootstrap CSS and JS
add_action('wp_enqueue_scripts', function() {
    // Bootstrap CSS
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css');

    // Bootstrap JS (optional, if you need JS features like modals or dropdowns)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js', [], false, true);
});



// Include CPT file
require_once plugin_dir_path(__FILE__) . '/cpt/cpt-service.php';



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


