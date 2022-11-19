<?php
	
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ){
	 exit;
}
class Testimonial_Moving_Admin_Metaboxes_Template{
	/* TESTIMONIAL MOVING EFFECTS AND TIMING */
	public function testimonial_template_metabox_effects(){
		global $post;

		$timeout				= (int) esc_attr(get_post_meta( $post->ID, '_timeout', true ));
		$speed					= (int) esc_attr(get_post_meta( $post->ID, '_speed', true ));
		$fx						= esc_attr(get_post_meta( $post->ID, '_fx', true ));
		$shuffle				= esc_attr(get_post_meta( $post->ID, '_shuffle', true ));
		$verticalalign			= esc_attr(get_post_meta( $post->ID, '_verticalalign', true ));
		$prevnext				= esc_attr(get_post_meta( $post->ID, '_prevnext', true ));
		$limit					= (int) esc_attr(get_post_meta( $post->ID, '_limit', true ));
		$itemreviewed			= esc_attr(get_post_meta( $post->ID, '_itemreviewed', true ));
		$template				= esc_attr(get_post_meta( $post->ID, '_template', true ));
		$img_size				= esc_attr(get_post_meta( $post->ID, '_img_size', true ));
		$title_heading			= esc_attr(get_post_meta( $post->ID, '_title_heading', true ));
		
		$hidefeaturedimage		= esc_attr(get_post_meta( $post->ID, '_hidefeaturedimage', true ));
		$hide_microdata			= esc_attr(get_post_meta( $post->ID, '_hide_microdata', true ));
		$hide_title				= esc_attr( get_post_meta( $post->ID, '_hide_title', true ));
		$hide_stars				= esc_attr(get_post_meta( $post->ID, '_hide_stars', true ));
		$hide_body				= esc_attr(get_post_meta( $post->ID, '_hide_body', true ));
		$hide_author			= esc_attr(get_post_meta( $post->ID, '_hide_author', true ));
		$hide_company			= esc_attr(get_post_meta( $post->ID, '_hide_company', true ));
		
		$available_effects 		= testimonial_moving_base_transitions();
		$image_sizes 			= get_intermediate_image_sizes();
		
		if(!$timeout) 	{ $timeout = 5; }
		if(!$speed) 	{ $speed = 1; }
		if(!$template) 	{ $template = 'default'; }
		if(!$img_size) 	{ $img_size = 'thumbnail'; }
		if(!$title_heading) 	{ $title_heading = apply_filters('testimonial_template_title_heading', 'h2'); }
		
		$available_themes = testimonial_template_available_themes();
		?>
		
		<style>
			.hg_slider_column { width: 46%; margin: 0 2%; float: left; } 
			/* 680 */
			@media only screen and (max-width: 680px) {
				.hg_slider_column { width: 100%; margin: 0 0 20px 0; float: none; }
			}
		</style>
		
		<div class="hg_slider_column">
		<p>
			<select name="fx">
				<?php foreach($available_effects as $available_effect) { ?>
				<option value="<?php echo $available_effect ?>" <?php selected($available_effect,$fx);?> ><?php echo $available_effect ?></option>
				<?php } ?>
			</select>
			<?php _e('Transition Effect', 'testimonial-moving'); ?>
		</p>
		
		<p>
			<select name="img_size">
				<?php foreach($image_sizes as $image_size) { ?>
				<option value="<?php echo $image_size ?>" <?php selected($image_size,$img_size);?> ><?php echo $image_size ?></option>
				<?php } ?>
			</select>
			<?php _e('Image Size', 'testimonial-moving'); ?>
		</p>
		
		<p>
			<input type="text" style="width: 45px; text-align: center;" name="timeout" value="<?php echo esc_attr( $timeout ); ?>" maxlength="4" />
			<?php _e('Seconds per Testimonial', 'testimonial-moving'); ?>
		</p>
		
		<p>
			<input type="text" style="width: 45px; text-align: center;" name="speed" value="<?php echo esc_attr( $speed ); ?>" maxlength="4" />
			<?php _e('Transition Speed (in seconds)', 'testimonial-moving'); ?>
		</p>
		
		<p>
			<input type="text" style="width: 45px; text-align: center;" name="limit" value="<?php echo esc_attr( $limit ); ?>" maxlength="4" />
			<?php _e('Limit Number of Testimonials', 'testimonial-moving'); ?>
		</p>
		
		<p>
			<input type="text" style="width: 45px; text-align: center;" name="title_heading" value="<?php echo esc_attr( $title_heading ); ?>" maxlength="12" />
			<?php _e('Element for Title Field', 'testimonial-moving'); ?>
		</p>
		
		</div>
		
		<div class="hg_slider_column">
		<p>
			<input id="testimonial_moving_shuffle_check" type="checkbox" name="shuffle" value="1" <?php checked($shuffle,1); ?> />
			<label for="testimonial_moving_shuffle_check"><?php _e('Randomize Testimonials', 'testimonial-moving'); ?></label>
		</p>
		
		<p>
			<input id="testimonial_moving_align_check" type="checkbox" name="verticalalign" value="1" <?php checked($verticalalign,1); ?> />
			<label for="testimonial_moving_align_check"><?php _e('Vertical Align (Center Testimonials Height)', 'testimonial-moving'); ?></label>
		</p>
		
		<p>
			<input id="testimonial_moving_prevnext_check" type="checkbox" name="prevnext" value="1" <?php checked($prevnext,1); ?> />
			<label for="testimonial_moving_prevnext_check"><?php _e('Show Previous/Next Buttons', 'testimonial-moving'); ?></label>
		</p>
		
		<p>
			<input id="testimonial_moving_featimg_check" type="checkbox" name="hidefeaturedimage" value="1" <?php checked($hidefeaturedimage,1); ?>/>
			<label for="testimonial_moving_featimg_check"><?php _e('Hide Featured Image', 'testimonial-moving'); ?></label>
		</p>
		
		<p>
			<input id="testimonial_moving_hidetitle_check" type="checkbox" name="hide_title" value="1" <?php checked($hide_title,1); ?>/>
			<label for="testimonial_moving_hidetitle_check"><?php _e('Hide Title', 'testimonial-moving'); ?></label>
		</p>
		
		<p>
			<input id="testimonial_moving_hidestars_check" type="checkbox" name="hide_stars" value="1" <?php checked($hide_stars,1); ?> />
			<label for="testimonial_moving_hidestars_check"><?php _e('Hide Stars', 'testimonial-moving'); ?></label>
		</p>
		
		<p>
			<input id="testimonial_moving_hidebody_check" type="checkbox" name="hide_body" value="1" <?php checked($hide_body,1); ?> />
			<label for="testimonial_moving_hidebody_check"><?php _e('Hide Body', 'testimonial-moving'); ?></label>
		</p>
		
		<p>
			<input id="testimonial_moving_hideauthor_check" type="checkbox" name="hide_author" value="1" <?php checked($hide_author,1); ?> />
			<label for="testimonial_moving_hideauthor_check"><?php _e('Hide Author', 'testimonial-moving'); ?></label>
		</p>
		<p>
			<input id="testimonial_moving_hideauthor_check" type="checkbox" name="hide_company" value="1"  <?php checked($hide_company,1); ?>/>
			<label for="testimonial_moving_hideauthor_check"><?php _e('Hide Company', 'testimonial-moving'); ?></label>
		</p>
		<p>
			<input id="testimonial_moving_microdata_check" type="checkbox" name="hide_microdata" value="1" onchange="if(this.checked) { jQuery('#item-reviewed-p').slideUp(); } else { jQuery('#item-reviewed-p').slideDown(); }" <?php checked($hide_microdata,1); ?> />
			<label for="testimonial_moving_microdata_check"><?php _e('Hide Microdata (Review)', 'testimonial-moving'); ?></label>
		</p>
		
		</div>
		<div class="clear"></div>
		
		<p id="item-reviewed-p" style="border-top: solid 1px #ccc; margin-top: 15px; padding-top: 15px;<?php if($hide_microdata){ echo " display:none;";} ?>">
			<label for="itemreviewed"><?php _e('Name of Item Being Reviewed:', 'testimonial-moving'); ?></label><br />
			<small><?php _e("Company Name, Product Name, etc.", 'testimonial-moving'); ?> (<?php _e("not visible on your website, used for search engines", 'testimonial-moving'); ?>)</small><br />
			<input type="text" style="width: 80%;" id="itemreviewed" name="itemreviewed" value="<?php echo esc_attr( $itemreviewed ); ?>" />
		</p>

		<div style="padding: 15px 0; margin: 15px 0; border-top: solid 1px #ccc; border-bottom: solid 1px #ccc;">
			
			<style>
				.testimonial-moving-template-selector-wrap { border: solid 5px #ccc; } 
				.tr_template_selected { border: solid 5px #bee483; } 
				#testimonial-moving-templates a:focus { box-shadow: none; } 
			</style>
			
			<script>
				jQuery(document).ready(function() 
				{
					jQuery('.testimonial-moving-template-selector-wrap a').on('click', function() 
					{
						jQuery('.testimonial-moving-template-selector-wrap').removeClass('tr_template_selected');
						jQuery('#testimonial_moving_template').val( jQuery(this).data('slug') );
						jQuery(this).parent('.testimonial-moving-template-selector-wrap').addClass('tr_template_selected');
					});
				});
			</script>
		
			<p>
				<strong><?php _e('Select a Theme:', 'testimonial-moving'); ?></strong><br>
			</p>
			
			<div id="testimonial-moving-templates">
			
				<?php foreach( $available_themes as $theme_slug => $available_theme ) {
					
					if( !isset($available_theme['icon']) ){
						 $available_theme['icon'] = TESTIMONIAL_MOVING_DIR_URL . 'templates/' . $theme_slug. '/icon.png'; 
					}
					
					?>
					<div class="testimonial-moving-template-selector-wrap <?php if($template == $theme_slug){ echo "tr_template_selected";} ?>" style="float: left; text-align: center; padding: 10px; margin: 10px; min-height: 100px;">
						<a href="javascript:;" class="testimonial-moving-template-selector" data-slug="<?php echo esc_attr($theme_slug); ?>"><img src="<?php echo $available_theme['icon']; ?>" style="width: 155px;"></a><br>
						<b><?php echo $available_theme['title']; ?></b> - <a href="javascript:;" class="testimonial-moving-template-selector" data-slug="<?php echo esc_attr($theme_slug); ?>"><?php echo __('Use', 'testimonial-moving'); ?></a>
					</div>
				<?php } ?>
				
				<div style="clear:both;"></div>
				<input type="hidden" name="template" id="testimonial_moving_template" value="<?php echo $template; ?>">
			</div>
			
		</div>

		<?php if($post->post_name) { ?>
		<p>
			<strong><?php _e('Make a Custom Template:', 'testimonial-moving'); ?></strong><br>
			<?php _e('To create custom layouts for this template create a file called', 'testimonial-moving'); ?>
			<b>testimonial-moving/loop-testimonial-<?php echo $post->post_name; ?>.php</b> <?php _e('and place it in your theme.', 'testimonial-moving'); ?>
		</p>
		<?php } ?>

		<?php	
	}
	public function testimonial_template_testimonial_count_meta(){
		global $post;
		$originalpost = $post;
		 
		$id = $post->ID;
		
		$testimonials_args = array(
									'post_type' => 'testimonial',
									'order' => 'ASC',
									'orderby' => "menu_order",
									'posts_per_page' => -1,
									'meta_query' => tm_testimonial_meta_query( $id )
								);
								
		// GET TEMPLATE SLIDES
		$slide_query = new WP_Query( $testimonials_args );

		if( $slide_query->have_posts() ){
			echo "<ol>";
			while ( $slide_query->have_posts() ) {
				$slide_query->the_post();
				echo "<li><a href='post.php?post=" . get_the_id() . "&action=edit'>" . get_the_title() . "</a></li>";
			}
			echo "</ol>";
			
			echo "<a href='edit.php?post_type=testimonial&template_id=" . $id . "' class='button'>" . __('View in Edit List', 'testimonial-moving') . "</a>";
		}
		else
		{
			echo "<p>" . __('No testimonials have been associated to this template yet.', 'testimonial-moving') . "</p>";
		}
		
		wp_reset_postdata();
		$post = $originalpost;
	}
}
