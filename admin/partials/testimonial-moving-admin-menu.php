<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $testimonial_moving_obj;
global $testimonial_moving_notices;
?>

<header>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h2><?php _e('Testimonial Moving Settings', 'testimonial-moving'); ?></h2>
	</div>
</header>


<div class="wrap">
	<?php 
	if( $testimonial_moving_notices ) {
		$tmr_error_text = esc_html__( 'Settings saved !', 'testimonial-moving' );
		$testimonial_moving_obj->tm_plug_admin_notice( $tmr_error_text, 'success' );
	}
	do_action( 'tm_notice_message' );
	
	do_action( 'tm_general_settings_form_before' );
	
	$tmr_content_path = TESTIMONIAL_MOVING_DIR_PATH . 'admin/partials/testimonial-moving-admin-settings.php';
	$testimonial_moving_obj->tm_plug_load_template( $tmr_content_path );
	
	do_action( 'tm_general_settings_form_after' )

	?>
</div>