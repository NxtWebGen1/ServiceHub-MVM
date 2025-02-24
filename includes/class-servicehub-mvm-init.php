<?php

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
