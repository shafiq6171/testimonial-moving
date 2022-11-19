<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profile.webmonch.com
 * @since             1.0.0
 * @package           Testimonial_Moving
 *
 * @wordpress-plugin
 * Plugin Name:       Testimonial Moving
 * Plugin URI:        https://webmonch.com
 * Description:       The best way to add testimonials to your WordPress site.
 * Version:           1.0.0
 * Author:            Shafiq
 * Author URI:        https://profile.webmonch.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       testimonial-moving
 * Domain Path:       /languages
 * Requires at least:       5.8
 * Tested up to:            6.1
 * Requires PHP: 			7.4 or greater
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
function define_testimonial_moving() {
		testimonial_moving_constants( 'TESTIMONIAL_MOVING_VERSION', '1.0.0' );
		testimonial_moving_constants( 'TESTIMONIAL_MOVING_FILE', __FILE__ );
		testimonial_moving_constants( 'TESTIMONIAL_MOVING_BASE', plugin_basename( TESTIMONIAL_MOVING_FILE));
		testimonial_moving_constants( 'TESTIMONIAL_MOVING_DIR_PATH', plugin_dir_path( TESTIMONIAL_MOVING_FILE) );
		testimonial_moving_constants( 'TESTIMONIAL_MOVING_DIR_URL', plugin_dir_url( TESTIMONIAL_MOVING_FILE ) );
}
function testimonial_moving_constants( $key, $value ) {
		if ( ! defined( $key ) ) {
			define( $key, $value );
		}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-testimonial-moving-activator.php
 */
function activate_testimonial_moving() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-testimonial-moving-activator.php';
	Testimonial_Moving_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-testimonial-moving-deactivator.php
 */
function deactivate_testimonial_moving() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-testimonial-moving-deactivator.php';
	Testimonial_Moving_Deactivator::deactivate();
}
/**
 * The setting page link from plugin page.
 */
function testimonial_moving_plugin_settings($links) {
	$links['settings'] = '<a href="edit.php?post_type=testimonial&page=testimonial-moving">Settings</a>';
	$links['supports'] = '<a href="https://webmonch.com" target="_blank">Supports</a>';
	return $links;
}
register_activation_hook( __FILE__, 'activate_testimonial_moving' );
register_deactivation_hook( __FILE__, 'deactivate_testimonial_moving' );
$plugin_dir = plugin_basename( __FILE__);
add_filter("plugin_action_links_" . $plugin_dir, 'testimonial_moving_plugin_settings');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-testimonial-moving.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_testimonial_moving() {
	define_testimonial_moving();
	$testimonial_moving = new Testimonial_Moving();
	$testimonial_moving->run();
	$GLOBALS['testimonial_moving_obj'] = $testimonial_moving;
	$GLOBALS['testimonial_moving_notices'] = false;

}
run_testimonial_moving();