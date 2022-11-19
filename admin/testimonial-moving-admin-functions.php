<?php
	
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ){
	 exit;
}
// CREATE AND RETURN PIPED MOVING IDS
function testimonial_moving_make_piped_string( $arr ){
	return "|" . implode("|", (array) $arr) . "|";
}
function testimonial_moving_break_piped_string( $arr ){
	return array_filter( explode("|", (string) $arr), 'strlen' );
}



/* SHORTCODE DISPLAY HELPER */
function testimonial_moving_shortcode_metabox(){
	global $post;
	
	echo '
		<b>' . __('Base Template', 'testimonial-moving') . '</b><br />
		[testimonial_template id="' . $post->ID . '"]<br /><br />
		
		<b>' . __('List All Testimonials', 'testimonial-moving') . '</b><br />
		[testimonial_template id="' . $post->ID . '" format="list"]<br /><br />
		
		<b>' . __('Limit Results to 10 and list', 'testimonial-moving') . '</b><br />
		[testimonial_template id="' . $post->ID . '" format="list" limit="10" paged="1" prev_next="1"]<br /><br />
		
		<b>' . __('Randomize Testimonials', 'testimonial-moving') . '</b><br />
		[testimonial_template id="' . $post->ID . '" shuffle="true"]<br /><br />	
		
		<b>' . __('Show Aggregate Rating as Stars', 'testimonial-moving') . '</b><br />
		[testimonial_template_rating id="' . $post->ID . '"]<br /><br />	
		
		<b>' . __('Show Aggregate Rating as Number', 'testimonial-moving') . '</b><br />
		[testimonial_template_rating id="' . $post->ID . '" return="rating"]<br /><br />	
	';
}