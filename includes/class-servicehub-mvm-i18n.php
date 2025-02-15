<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/NxtWebGen1
 * @since      1.0.0
 *
 * @package    Servicehub_Mvm
 * @subpackage Servicehub_Mvm/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Servicehub_Mvm
 * @subpackage Servicehub_Mvm/includes
 * @author     Next Web Gen <nxtwebgen1@gmail.com>
 */
class Servicehub_Mvm_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'servicehub-mvm',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
