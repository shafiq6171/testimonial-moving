<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $testimonial_moving_obj;
$tmr_genaral_settings = apply_filters( 'tm_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-gen-section-form">
	<div class="wps-section">
		<?php
		$tm_general_html = $testimonial_moving_obj->tm_plug_generate_html( $tmr_genaral_settings );
		echo esc_html( $tm_general_html );
		wp_nonce_field( 'wps-general-nonce', 'wps-general-nonce-field' );
		?>
	</div>
</form>
