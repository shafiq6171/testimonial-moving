<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profile.webmonch.com
 * @since      1.0.0
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/includes
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Testimonial_Moving_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		flush_rewrite_rules();
	}

}
