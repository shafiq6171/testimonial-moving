<?php 
// WIDGET
class Testimonial_Moving_Widget extends WP_Widget{
	public function __construct(){
		$widget_ops = array('classname' => 'Testimonial_Moving_Widget', 'description' => __('Displays moving testimonials', 'testimonial-moving') );
		parent::__construct('Testimonial_Moving_Widget', __('Testimonials moving', 'testimonial-moving'), $widget_ops);
	}

	public function form($instance){
		$testimonial_templates = get_posts( array( 'post_type' => 'testimonial_template', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );

		$title 							= isset($instance['title']) ? $instance['title'] : "";
		$testimonial_template_id 		= isset($instance['template_id']) ? $instance['template_id'] : 0;
		$format							= isset($instance['format']) ? $instance['format'] : "template";
		$excerpt_length					= isset($instance['excerpt_length']) ? $instance['excerpt_length'] : "";
		$limit 							= (int) isset($instance['limit']) ? $instance['limit'] : 5;
		$show_size 						= (isset($instance['show_size']) AND $instance['show_size'] == "full") ? "full" : "excerpt";
		$override_template_settings 		= (isset($instance['override_template_settings']) AND $instance['override_template_settings'] == 1) ? 1 : 0;
		
		
		// OVERRIDES
		$template						= isset($instance['template']) ? $instance['template'] : "default";
		$fx								= isset($instance['fx']) ? $instance['fx'] : false;
		$img_size						= isset($instance['img_size']) ? $instance['img_size'] : false;
		$timeout						= isset($instance['timeout']) ? $instance['timeout'] : "";
		$speed							= isset($instance['speed']) ? $instance['speed'] : "";
		$title_heading					= isset($instance['title_heading']) ? $instance['title_heading'] : "";
		$shuffle						= isset($instance['shuffle']) ? $instance['shuffle'] : false;
		$verticalalign					= isset($instance['verticalalign']) ? $instance['verticalalign'] : "";
		$prev_next						= isset($instance['prev_next']) ? $instance['prev_next'] : "";
		$itemreviewed					= isset($instance['itemreviewed']) ? $instance['itemreviewed'] : "";
		$show_link						= (isset($instance['show_link']) AND $instance['show_link'] != "" ) ? $instance['show_link'] : "";
		$link_text						= (isset($instance['link_text']) AND trim($instance['link_text']) != "" ) ? trim($instance['link_text']) : "";
		
		$hidefeaturedimage				= isset($instance['hidefeaturedimage']) ? $instance['hidefeaturedimage'] : "";
		$hide_microdata					= isset($instance['hide_microdata']) ? $instance['hide_microdata'] : "";
		$hide_title						= isset($instance['hide_title']) ? $instance['hide_title'] : "";	
		$hide_stars						= isset($instance['hide_stars']) ? $instance['hide_stars'] : "";	
		$hide_body						= isset($instance['hide_body']) ? $instance['hide_body'] : "";	
		$hide_author					= isset($instance['hide_author']) ? $instance['hide_author'] : "";	
		$hide_company					= isset($instance['hide_company']) ? $instance['hide_company'] : "";	
		
		// SELECT BOX DATA
		$available_effects 		= testimonial_moving_base_transitions();
		$image_sizes 			= get_intermediate_image_sizes();
		$available_themes 		= testimonial_template_available_themes();
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'testimonial-moving'); ?> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('template_id'); ?>"><?php _e('Template', 'testimonial-moving'); ?>:
		<select name="<?php echo $this->get_field_name('template_id'); ?>" class="widefat" id="<?php echo $this->get_field_id('template_id'); ?>">
			<option value=""><?php _e('All Templates', 'testimonial-moving'); ?></option>
			<?php foreach($testimonial_templates as $testimonial_template) { ?>
			<option value="<?php echo $testimonial_template->ID ?>" <?php selected($testimonial_template->ID ,$testimonial_template_id); ?>><?php echo $testimonial_template->post_title ?></option>
			<?php } ?>
		</select>
		</label>
		</p>

		<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', 'testimonial-moving'); ?> <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" /></label></p>

		<p>
			<label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Display:', 'testimonial-moving'); ?></label> &nbsp;
			<input id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" value="template" type="radio" <?php if($format != "list"){ echo " checked='checked'";} ?>> <?php _e('Template', 'testimonial-moving'); ?> &nbsp;
			<input id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" value="list" type="radio" <?php checked($format,"list"); ?> <?php _e('List', 'testimonial-moving'); ?>
		</p>

		<p class="testimonial_template_size">
			<label for="<?php echo $this->get_field_id('show_size'); ?>"><?php _e('Show as:', 'testimonial-moving'); ?></label> &nbsp;
			<input id="<?php echo $this->get_field_id('show_size'); ?>" name="<?php echo $this->get_field_name('show_size'); ?>" value="full" type="radio"<?php checked($show_size,"full"); ?>> <?php _e('Full', 'testimonial-moving'); ?>&nbsp;
			<input id="<?php echo $this->get_field_id('show_size'); ?>" name="<?php echo $this->get_field_name('show_size'); ?>" value="excerpt" type="radio"<?php checked($show_size,"excerpt"); ?>> <?php _e('Excerpt', 'testimonial-moving'); ?>
		</p>

		<p class="testimonial_excerpt_length" <?php if($show_size == "full"){ echo " style='display:none'";} ?>>
			<label for="<?php echo $this->get_field_id('excerpt_length'); ?>"><?php _e('Custom Excerpt Length: (in words)', 'testimonial-moving'); ?><br>
			<input class="" id="<?php echo $this->get_field_id('excerpt_length'); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" type="text" value="<?php echo esc_attr($excerpt_length); ?>" /></label>
		</p>
		<script>
			jQuery(".testimonial_template_size input").change(function()
			{
				jQuery("p.testimonial_excerpt_length").toggle();
			});
		</script>

		<hr>
		
		<div class="override_template_settings_block">
			
			<p>
	        	<input id="<?php echo $this->get_field_id('override_template_settings'); ?>" name="<?php echo $this->get_field_name('override_template_settings'); ?>" type="checkbox" value="1" <?php checked($override_template_settings,1);; ?> />
				<label for="<?php echo $this->get_field_id('override_template_settings'); ?>"><?php _e('Override Template Settings?', 'testimonial-moving'); ?></label>
			</p>
			
			<script>
				jQuery('#<?php echo $this->get_field_id('override_template_settings'); ?>').change(function() 
				{
					jQuery(this).parents('.override_template_settings_block').children('.override_template_settings_fields').toggleClass('hidden');
					
				});
			</script>
			<div class="override_template_settings_fields<?php if(!$override_template_settings){ echo " hidden"; }?>">
				
				<?php if(count($available_themes) > 1) { ?>
				<p>
					<select id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template'); ?>">
						<?php foreach( $available_themes as $theme_slug => $available_theme ) { ?>
						<option value="<?php echo $theme_slug ?>" <?php selected($theme_slug,$template); ?>><?php echo $available_theme['title']; ?></option>
						<?php } ?>
					</select>
					<label for="<?php echo $this->get_field_id('template'); ?>"><?php _e('Template', 'testimonial-moving'); ?></label>
				</p>
				<?php } ?>
				
				<p>
					<select id="<?php echo $this->get_field_id('fx'); ?>" name="<?php echo $this->get_field_name('fx'); ?>">
						<option value=""><?php _e('Template Default', 'testimonial-moving'); ?></option>
						<?php foreach($available_effects as $available_effect) { ?>
						<option value="<?php echo $available_effect ?>" <?php selected($available_effect,$fx); ?>><?php echo $available_effect ?></option>
						<?php } ?>
					</select>
					<label for="<?php echo $this->get_field_id('fx'); ?>"><?php _e('Transition Effect', 'testimonial-moving'); ?></label>
				</p>
				
				<p>
					<select id="<?php echo $this->get_field_id('img_size'); ?>" name="<?php echo $this->get_field_name('img_size'); ?>">
						<option value=""><?php _e('Template Default', 'testimonial-moving'); ?></option>
						<?php foreach($image_sizes as $image_size) { ?>
						<option value="<?php echo $image_size ?>" <?php selected($image_size,$img_size); ?>><?php echo $image_size ?></option>
						<?php } ?>
					</select>
					<label for="<?php echo $this->get_field_id('img_size'); ?>"><?php _e('Image Size', 'testimonial-moving'); ?></label>
				</p>
			
				<p>
					<input id="<?php echo $this->get_field_id('timeout'); ?>" name="<?php echo $this->get_field_name('timeout'); ?>" value="<?php echo esc_attr($timeout); ?>" type="text" style="width: 45px; text-align: center;">
					<label for="<?php echo $this->get_field_id('timeout'); ?>"><?php _e('Seconds per Testimonial', 'testimonial-moving'); ?></label>
				</p>
				
				<p>
					<input id="<?php echo $this->get_field_id('speed'); ?>" name="<?php echo $this->get_field_name('speed'); ?>" value="<?php echo esc_attr($speed); ?>" type="text" style="width: 45px; text-align: center;">
					<label for="<?php echo $this->get_field_id('speed'); ?>"><?php _e('Transition Speed (in seconds)', 'testimonial-moving'); ?></label>
				</p>
				
				<p>
					<input id="<?php echo $this->get_field_id('title_heading'); ?>" name="<?php echo $this->get_field_name('title_heading'); ?>" value="<?php echo esc_attr($title_heading); ?>" type="text" style="width: 45px; text-align: center;">
					<label for="<?php echo $this->get_field_id('title_heading'); ?>"><?php _e('Element for Title Field', 'testimonial-moving'); ?></label>
				</p>

				<p>
					<select id="<?php echo $this->get_field_id('shuffle'); ?>" name="<?php echo $this->get_field_name('shuffle'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($shuffle,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($shuffle,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('shuffle'); ?>"><?php _e('Randomize Testimonials', 'testimonial-moving'); ?></label> &nbsp;
				</p>
				
				<p>
					<select id="<?php echo $this->get_field_id('verticalalign'); ?>" name="<?php echo $this->get_field_name('verticalalign'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($verticalalign,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($verticalalign,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('verticalalign'); ?>"><?php _e('Vertical Align (Center Testimonials Height)', 'testimonial-moving'); ?></label> &nbsp;
				</p>
				
				<p>
					<select id="<?php echo $this->get_field_id('prev_next'); ?>" name="<?php echo $this->get_field_name('prev_next'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($prev_next,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($prev_next,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('prev_next'); ?>"><?php _e('Show Previous/Next Buttons', 'testimonial-moving'); ?></label> &nbsp;
				</p>
				
				<p>
					<select id="<?php echo $this->get_field_id('show_link'); ?>" name="<?php echo $this->get_field_name('show_link'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($show_link,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($show_link,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('show_link'); ?>"><?php _e('Show Link to Testimonial', 'testimonial-moving'); ?></label> &nbsp;
				</p>
				
			<script>
				jQuery("#<?php echo $this->get_field_id('show_link'); ?>").change(function(){
					if( jQuery(this).val() == "1" )
					{
						jQuery("p#testimonial_moving_link_text_p").slideDown();
					}	
					else
					{
						jQuery("p#testimonial_moving_link_text_p").slideUp();
					}
				});
			</script>
				
				<p id="testimonial_moving_link_text_p" <?php if($show_link != 1){ echo "style='display:none;'";} ?>>
					<label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Text for Link to Testimonial', 'testimonial-moving'); ?></label><br>
					<input id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" placeholder="<?php _e("Read more, View Full, etc.", 'testimonial-moving'); ?>" value="<?php echo esc_attr($link_text); ?>" type="text" style="width: 95%;">
				</p>

				<hr>
				
				<p>
					<select id="<?php echo $this->get_field_id('hidefeaturedimage'); ?>" name="<?php echo $this->get_field_name('hidefeaturedimage'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($hidefeaturedimage,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($hidefeaturedimage,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('hidefeaturedimage'); ?>"><?php _e('Hide Featured Image', 'testimonial-moving'); ?></label> &nbsp;
				</p>

				<p>
					<select id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($hide_title,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($hide_title,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide Title', 'testimonial-moving'); ?></label> &nbsp;
				</p>

				<p>
					<select id="<?php echo $this->get_field_id('hide_stars'); ?>" name="<?php echo $this->get_field_name('hide_stars'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($hide_stars,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($hide_stars,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('hide_stars'); ?>"><?php _e('Hide Stars', 'testimonial-moving'); ?></label> &nbsp;
				</p>
				
				<p>
					<select id="<?php echo $this->get_field_id('hide_body'); ?>" name="<?php echo $this->get_field_name('hide_body'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($hide_body,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($hide_body,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('hide_body'); ?>"><?php _e('Hide Body', 'testimonial-moving'); ?></label> &nbsp;
				</p>
					
				<p>
					<select id="<?php echo $this->get_field_id('hide_author'); ?>" name="<?php echo $this->get_field_name('hide_author'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($hide_author,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($hide_author,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('hide_author'); ?>"><?php _e('Hide Author', 'testimonial-moving'); ?></label> &nbsp;
				</p>
				<p>
					<select id="<?php echo $this->get_field_id('hide_company'); ?>" name="<?php echo $this->get_field_name('hide_company'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($hide_company, "1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($hide_company, "0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('hide_company'); ?>"><?php _e('Hide Author', 'testimonial-moving'); ?></label> &nbsp;
				</p>
							
				<p>
					<select id="<?php echo $this->get_field_id('hide_microdata'); ?>" name="<?php echo $this->get_field_name('hide_microdata'); ?>">
						<option value=""><?php _e('Default', 'testimonial-moving'); ?></option>
						<option value="1" <?php selected($hide_microdata,"1"); ?>><?php _e('Yes', 'testimonial-moving'); ?></option>
						<option value="0" <?php selected($hide_microdata,"0"); ?>><?php _e('No', 'testimonial-moving'); ?></option>
					</select>
					<label for="<?php echo $this->get_field_id('hide_microdata'); ?>"><?php _e('Hide Microdata (Review)', 'testimonial-moving'); ?></label> &nbsp;
				</p>
				
				<hr>
				
				<p>
					<label for="<?php echo $this->get_field_id('itemreviewed'); ?>"><?php _e('Name of Item Being Reviewed:', 'testimonial-moving'); ?></label><br>
					<input id="<?php echo $this->get_field_id('itemreviewed'); ?>" name="<?php echo $this->get_field_name('itemreviewed'); ?>" placeholder="<?php _e("Company Name, Product Name, etc.", 'testimonial-moving'); ?>" value="<?php echo esc_attr($itemreviewed); ?>" type="text" style="width: 95%;">
				</p>
			</div>
		</div>
	<?php
	}

	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] 						= $new_instance['title'];
		$instance['template_id'] 				= isset($new_instance['template_id']) ? $new_instance['template_id'] : '';
		$instance['format'] 					= $new_instance['format'];
		$instance['excerpt_length'] 			= $new_instance['excerpt_length'];
		
		$instance['show_size'] 					= $new_instance['show_size'];
		$instance['limit'] 						= $new_instance['limit'];
		
		// OVERRIDES
		$instance['override_template_settings'] 	= (isset($new_instance['override_template_settings']) AND $new_instance['override_template_settings'] == 1) ? 1 : 0;

		if( isset($new_instance['template']) ) 			{$instance['template'] 				= $new_instance['template'];}
		if( isset($new_instance['fx']) ) 				{$instance['fx'] 					= $new_instance['fx'];}
		if( isset($new_instance['img_size']) ) 			{$instance['img_size'] 				= $new_instance['img_size'];}
		if( isset($new_instance['timeout']) ) 			{$instance['timeout'] 				= $new_instance['timeout'];}
		if( isset($new_instance['speed']) ) 			{$instance['speed'] 				= $new_instance['speed'];}
		if( isset($new_instance['title_heading']) ) 	{$instance['title_heading'] 		= $new_instance['title_heading'];}
		if( isset($new_instance['shuffle']) ) 			{$instance['shuffle'] 				= $new_instance['shuffle'];}
		if( isset($new_instance['verticalalign']) ) 	{$instance['verticalalign'] 		= $new_instance['verticalalign'];}
		if( isset($new_instance['prev_next']) ) 		{$instance['prev_next'] 			= $new_instance['prev_next'];}
		if( isset($new_instance['show_link']) AND $new_instance['show_link'] != '' ){$instance['show_link'] = $new_instance['show_link'];}
		if( isset($new_instance['itemreviewed']) ) 		{$instance['itemreviewed'] 			= $new_instance['itemreviewed'];}
		if( isset($new_instance['link_text']) ) 		{$instance['link_text'] 			= $new_instance['link_text'];}

		if( isset($new_instance['hidefeaturedimage']) ) {$instance['hidefeaturedimage'] 	= $new_instance['hidefeaturedimage'];}
		if( isset($new_instance['hide_microdata']) ) 	{$instance['hide_microdata'] 		= $new_instance['hide_microdata'];}
		if( isset($new_instance['hide_title']) ) 		{$instance['hide_title'] 			= $new_instance['hide_title'];}
		if( isset($new_instance['hide_stars']) ) 		{$instance['hide_stars'] 			= $new_instance['hide_stars'];}
		if( isset($new_instance['hide_body']) ) 		{$instance['hide_body'] 			= $new_instance['hide_body'];}
		if( isset($new_instance['hide_author']) ) 		{$instance['hide_author'] 			= $new_instance['hide_author'];}
		if( isset($new_instance['hide_company']) ) 		{$instance['hide_company'] 			= $new_instance['hide_company'];}
		
		return $instance;
	}

	public function widget($args, $instance){
		extract($args, EXTR_SKIP);

		$widget_title 		= isset($instance['title']) ? $instance['title'] : false;
		echo $before_widget;

		if ( $widget_title ) { echo $before_title . $widget_title . $after_title; }

		$instance['id'] 				= isset($instance['template_id']) ? $instance['template_id'] : '';
		$instance['is_widget'] 			= true;	
		$instance['excerpt_length']		= $instance['excerpt_length'];
		
		
		// USER DEFINED SETTINGS
		if ( isset($instance['override_template_settings']) AND $instance['override_template_settings'] ){
			if( isset($instance['template']) ) 					{$instance['template']				= $instance['template'];}
			if( isset($instance['fx']) ) 						{$instance['fx']					= $instance['fx'];}
			if( isset($instance['img_size']) ) 					{$instance['img_size']				= $instance['img_size'];}
			if( isset($instance['timeout']) ) 					{$instance['timeout']				= $instance['timeout'];}
			if( isset($instance['speed']) ) 					{$instance['speed']					= $instance['speed'];}
			if( isset($instance['title_heading']) ) 			{$instance['title_heading']			= $instance['title_heading'];}
			if( isset($instance['shuffle']) ) 					{$instance['shuffle']				= $instance['shuffle'];}
			if( isset($instance['verticalalign']) ) 			{$instance['verticalalign']			= $instance['verticalalign'];}
			if( isset($instance['prev_next']) ) 				{$instance['prev_next']				= $instance['prev_next'];}
			if( isset($instance['hidefeaturedimage']) ) 		{$instance['hide_image']			= $instance['hidefeaturedimage'];}
			if( isset($instance['hide_microdata']) ) 			{$instance['hide_microdata']		= $instance['hide_microdata'];}
			if( isset($instance['hide_title']) ) 				{$instance['hide_title']			= $instance['hide_title'];}
			if( isset($instance['hide_stars']) ) 				{$instance['hide_stars']			= $instance['hide_stars'];}
			if( isset($instance['hide_body']) ) 				{$instance['hide_body']				= $instance['hide_body'];}
			if( isset($instance['hide_author']) ) 				{$instance['hide_author']			= $instance['hide_author'];}
			if( isset($instance['hide_company']) ) 				{$instance['hide_company']			= $instance['hide_company'];}
			if( isset($instance['show_link']) ) 				{$instance['show_link']				= $instance['show_link'];}
			if( isset($instance['link_text']) )					{$instance['link_text']				= $instance['link_text'];}
			if( isset($instance['itemreviewed']) )				{$instance['itemreviewed']			= $instance['itemreviewed'];}
		}else{
			// CLEAN IT UP
			unset(
					$instance['override_template_settings'], 
					$instance['template'],
					$instance['fx'],
					$instance['img_size'],
					$instance['timeout'],
					$instance['speed'],
					$instance['title_heading'],
					$instance['shuffle'],
					$instance['verticalalign'],
					$instance['prev_next'],
					$instance['hidefeaturedimage'],
					$instance['hide_microdata'],
					$instance['hide_title'],
					$instance['hide_stars'],
					$instance['hide_author'],
					$instance['hide_body'],
					$instance['show_link'],
					$instance['link_text'],
					$instance['itemreviewed']
				);
		}
		// HOOK INTO A WIDGET BEFORE IT GETS LOADED
		$instance = apply_filters( 'testimonial_moving_pre_widget_instance', $instance, $instance['id']);

		// CALL THE GOODS
		tm_testimonial_template( $instance );

		echo $after_widget;
	}
}