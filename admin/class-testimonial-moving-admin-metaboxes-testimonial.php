<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ){
	exit;
}

class Testimonial_Moving_Admin_Metaboxes_Testimonial{

/* MAIN TESTIMONIAL META BOX (SELECT BOX OF TEMPLATE) */
	public function testimonial_options_metabox_select(){
		global $post;	
		$template_ids	= testimonial_moving_break_piped_string( get_post_meta( $post->ID, '_template_id', true ) ); 
		$rating				= get_post_meta( $post->ID, '_rating', true );
		$author_name				= get_post_meta( $post->ID, '_author_name', true );
		$company				= get_post_meta( $post->ID, '_company', true );
		
		$testimonial_templates = get_posts( array( 'post_type' => 'testimonial_template', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
	?>

		<?php if(!count($testimonial_templates)) { ?>
			<p style="color: red;">
				<b><?php _e('No Testimonial template have been created.', 'testimonial-moving'); ?></b><br />
				<?php _e("You need to publish this testimonial so you don't lose your work and then go create a Testimonial Moving. You can then edit this testimonial and select the template here.", 'testimonial-moving'); ?>
			</p>
		<?php } else { ?>
			<p>
			<?php _e('Attach to Template: ', 'testimonial-moving'); ?> &nbsp;

			<?php foreach($testimonial_templates as $template) { ?>
				<input id="testimonial_template_id_checkbox_<?php echo $template->ID ?>" type="checkbox" name="template_id[]" <?php echo in_array($template->ID, $template_ids) ? " CHECKED" : ""; ?> value="<?php echo $template->ID ?>"/> <label for="testimonial_template_id_checkbox_<?php echo $template->ID ?>"><?php echo $template->post_title ?></label> &nbsp; &nbsp;
			<?php } ?> 
			</p>
		<?php } ?>
		
		<div style="padding: 10px 0; margin: 10px 0; border-top: solid 1px #ccc; border-bottom: solid 1px #ccc;">
			<label for="stars"><?php _e('Star Rating:', 'testimonial-moving'); ?></label> &nbsp; 
			<input id="testimonial_moving_star_1" type="radio" name="rating" value="1" <?php checked($rating,1);?> /><label for="testimonial_moving_star_1"> 1 </label>&nbsp;
			<input id="testimonial_moving_star_2" type="radio" name="rating" value="2" <?php checked($rating,2);?> /><label for="testimonial_moving_star_2"> 2 </label>&nbsp;
			<input id="testimonial_moving_star_3" type="radio" name="rating" value="3" <?php checked($rating,3);?> /><label for="testimonial_moving_star_3"> 3 </label>&nbsp;
			<input id="testimonial_moving_star_4" type="radio" name="rating" value="4" <?php checked($rating,4);?> /><label for="testimonial_moving_star_4"> 4 </label>&nbsp;
			<input id="testimonial_moving_star_5" type="radio" name="rating" value="5" <?php checked($rating,5);?> /><label for="testimonial_moving_star_5"> 5 </label>&nbsp;
			<input id="testimonial_moving_star_0" type="radio" name="rating" value="0" <?php checked($rating,0);?> /><label for="testimonial_moving_star_0"> <?php _e("Don't Show", 'testimonial-moving'); ?></label>
		</div>                                                                              
		<p>
			<label for="author_name"><?php _e('Author Name', 'testimonial-moving'); ?></label><br>
		</p>
		<input type="text" value="<?php echo $author_name;?>" name="author_name" class="regular-text"/>
		<p>
			<label for="testimonial-moving-company"><?php _e('Company Information', 'testimonial-moving'); ?></label><br>
		</p>
		<?php 
		wp_editor($company, 'testimonial-moving-company', 
		array( 
			'tinymce' 			=> array( 'theme_advanced_buttons1' => 'bold,italic,link,unlink'), 
			'textarea_name' 	=> 'company',
			'media_buttons' 	=> false, 
			'textarea_rows' 	=> 3, 
			'quicktags' 		=> false,
			"teeny" 			=> true
		) ); 
	}
}