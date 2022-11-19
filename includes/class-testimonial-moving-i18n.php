<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://profile.webmonch.com
 * @since      1.0.0
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/includes
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Testimonial_Moving_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'testimonial-moving',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
