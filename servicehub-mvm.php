<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/NxtWebGen1
 * @since             1.0.0
 * @package           Servicehub_Mvm
 *
 * @wordpress-plugin
 * Plugin Name:       ServiceHub MVM
 * Plugin URI:        https://github.com/NxtWebGen1/ServiceHub-MVM
 * Description:        A dynamic multi-vendor service marketplace plugin for WordPress, enabling service providers to list, manage, and sell services with dashboards, bookings, payments, and messaging.
 * Version:           1.0.0
 * Author:            Next Web Gen
 * Author URI:        https://github.com/NxtWebGen1/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       servicehub-mvm
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SERVICEHUB_MVM_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-servicehub-mvm-activator.php
 */
function activate_servicehub_mvm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-servicehub-mvm-activator.php';
	Servicehub_Mvm_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-servicehub-mvm-deactivator.php
 */
function deactivate_servicehub_mvm() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-servicehub-mvm-deactivator.php';
	Servicehub_Mvm_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_servicehub_mvm' );
register_deactivation_hook( __FILE__, 'deactivate_servicehub_mvm' );

/**
 * The core plugin class that is used to define,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-servicehub-mvm-init.php';

