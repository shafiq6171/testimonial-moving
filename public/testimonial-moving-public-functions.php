<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ){
	 exit;
}
// SHORTCODE FOR TEMPLATE
function tm_testimonial_template_shortcode( $atts ){
	return get_tm_testimonial_template( $atts );
}

function tm_testimonial_single_shortcode( $atts ){
	$id = isset($atts['id']) ? $atts['id'] : false;

	if( $id ){
		$testimonial = get_post( $id );

		if( $testimonial->post_type == "testimonial" ){
			// SETUP VARIABLES
			$template_id 		= get_post_meta( $id, '_template', true );
			$template_ids		= (array) testimonial_moving_break_piped_string($template_id);
			$template_id			= reset($template_ids);

			$atts['is_single'] = true;
			$atts['id'] = $template_id;
			$atts['testimonial_id'] = $id;
			$atts['prev_next'] = false;

			return get_tm_testimonial_template( $atts );
		}else{
			testimonial_moving_error( __('Testimonial is not a testimonial post type', 'testimonial-moving' ) );
		}
	}else{
		testimonial_moving_error( sprintf( __('Testimonial could not be found with ID: %d', 'testimonial-moving' ), $id ) );
	}
}


// GET A TEMPLATE (YOU CAN USE THIS, ALSO USED BY SHORTCODE
function get_tm_testimonial_template( $atts ){
	ob_start();
	tm_testimonial_template( $atts );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}


// MEAT & POTATOES OF THE TEMPLATE
function tm_testimonial_template( $atts ){	
	// GET ID
	$id = isset($atts['id']) ? $atts['id'] : false;


	// GET TEMPLATE
	if( $id ){
		$template = get_post( $id );
		if( !$template ){
			 testimonial_moving_error( sprintf( __('Template could not be found with ID: %d', 'testimonial-moving' ), $id ) );
		}else{
			// TEMPLATE SLUG
			$template_slug = $template->post_name;
		}
	}else{
		$template_slug = "all";
	}
	
	// SET 'TRUE' ATTS TO '1'
	$bool_atts = array('shuffle','log','vertical_align','hide_title','hide_stars','hide_body','hide_author','hide_company','hide_microdata','hide_image','show_link','no_pause_on_hover','prev_next');
	foreach( $bool_atts as $bool_att ){
		if(!isset($atts[$bool_att]) ){
			continue;
		}
		if( $atts[$bool_att] == "true" ){
			$atts[$bool_att] = 1;
		}
		if( $atts[$bool_att] == "false" ){
			$atts[$bool_att] = 0;
		}
	}
	
	// GET OVERRIDE SETTINGS FROM WIDGET OR SHORTCODE
	$testimonial_id	 	= isset($atts['testimonial_id']) ? (int) $atts['testimonial_id'] : false;
	$extra_classes	 	= isset($atts['extra_classes']) ? $atts['extra_classes'] : "";
	$timeout 			= isset($atts['timeout']) ? intval($atts['timeout']) : false;
	$speed 				= isset($atts['speed']) ? intval($atts['speed']) : false;
	$fx 				= isset($atts['fx']) ? $atts['fx'] : false;
	$shuffle 			= (isset($atts['shuffle']) AND $atts['shuffle'] == 1) ? 1 : 0;
	$post_count			= isset($atts['limit']) ? (int) $atts['limit'] : false;
	$format				= isset($atts['format']) ? $atts['format'] : "template";
	$is_widget 			= isset($atts['is_widget']) ? true : false;
	$is_single 			= isset($atts['is_single']) ? true : false;
	$show_size 			= (isset($atts['show_size']) AND $atts['show_size'] == "excerpt") ? "excerpt" : "full";
	$title_heading 		= (isset($atts['title_heading'])) ? $atts['title_heading'] : false;
	$itemreviewed		= (isset($atts['itemreviewed'])) ? $atts['itemreviewed'] : false;
	$auto_height		= (isset($atts['auto_height'])) ? $atts['auto_height'] : apply_filters('testimonial_template_auto_height', 'calc', $id);
	$vertical_align		= (isset($atts['vertical_align']) AND $atts['vertical_align'] == 1) ? 1 : 0;
	$div_selector		= (isset($atts['div_selector'])) ? $atts['div_selector'] : apply_filters('testimonial_template_div_selector', ' div.slide', $id);
	$pause_on_hover		= (isset($atts['no_pause_on_hover']) AND $atts['no_pause_on_hover'] == 1) ? 'false' : 'true';
	$prev_next			= (isset($atts['prev_next']) AND $atts['prev_next'] == 1) ? true : false;
	$paged				= (isset($atts['paged']) AND $atts['paged'] == 1) ? true : false;
	$template_name		= (isset($atts['template'])) ? $atts['template'] : false;
	$img_size			= (isset($atts['img_size'])) ? $atts['img_size'] : false;
	$excerpt_length 	= (isset($atts['excerpt_length'])) ? intval($atts['excerpt_length']) : false;
	$log  				= (isset($atts['log']) && $atts['log'] == 1) ? 'true' : 'false';
	
	// TURN OFF ANY PART OF THE SLIDE
	$show_title 		= (isset($atts['hide_title']) AND $atts['hide_title'] == 1) ? false : true;
	$show_stars 		= (isset($atts['hide_stars']) AND $atts['hide_stars'] == 1) ? false : true;
	$show_body 			= (isset($atts['hide_body']) AND $atts['hide_body'] == 1) ? false : true;
	$show_author 		= (isset($atts['hide_author']) AND $atts['hide_author'] == 1) ? false : true;
	$show_company 		= (isset($atts['hide_company']) AND $atts['hide_company'] == 1) ? false : true;
	$show_microdata		= (isset($atts['hide_microdata']) AND $atts['hide_microdata'] == 1) ? false : true;
	$show_image 		= (isset($atts['hide_image']) AND $atts['hide_image'] == 1) ? false : true;
	$show_link 			= (isset($atts['show_link']) AND $atts['show_link'] == 1) ? true : false;
	$link_text 			= ($show_link AND isset($atts['link_text']) AND trim($atts['link_text']) != "") ? trim($atts['link_text']) : '';

	// SET DEFAULT SETTINGS IF NOT SET
	if(!$timeout) 					{ $timeout 			= esc_attr(get_post_meta( $id, '_timeout', true )); }
	if(!$speed) 					{ $speed 			= esc_attr(get_post_meta( $id, '_speed', true )); }
	if(!$fx)						{ $fx 				= esc_attr(get_post_meta( $id, '_fx', true )); }
	if(!$shuffle AND !$is_widget)	{ $shuffle 			= esc_attr(get_post_meta( $id, '_shuffle', true )); }
	if(!$vertical_align)			{ $vertical_align 	= esc_attr(get_post_meta( $id, '_verticalalign', true )); }
	if(!$prev_next)					{ $prev_next 		= esc_attr(get_post_meta( $id, '_prevnext', true )); }
	if(!$post_count)				{ $post_count 		= esc_attr(get_post_meta( $id, '_limit', true )); }
	if(!$template_name)				{ $template_name 	= esc_attr(get_post_meta( $id, '_template', true )); }
	if(!$img_size)					{ $img_size 		= esc_attr(get_post_meta( $id, '_img_size', true )); }
	if(!$title_heading)				{ $title_heading 	= esc_attr(get_post_meta( $id, '_title_heading', true )); }
	if(!$excerpt_length)			{ $excerpt_length 	= esc_attr(get_post_meta( $id, '_excerpt_length', true )); }
	if( $show_image AND get_post_meta( $id, '_hidefeaturedimage', true ))	{ $show_image = false;}
	if( $show_title AND get_post_meta( $id, '_hide_title', true ))			{ $show_title = false;}
	if( $show_stars AND get_post_meta( $id, '_hide_stars', true ))			{ $show_stars = false;}
	if( $show_body AND get_post_meta( $id, '_hide_body', true )) 			{ $show_body = false;}
	if( $show_author AND get_post_meta( $id, '_hide_author', true ))		{ $show_author = false;}
	if( $show_company AND get_post_meta( $id, '_hide_company', true ))		{ $show_company = false;}

	if( $show_microdata ){
		$hide_microdata = get_post_meta( $id, '_hide_microdata', true );
		$show_microdata = $hide_microdata ? false: true;
	}

	// SANATIZE SETTINGS
	if(!$timeout) 	{$timeout = 5;}
	if(!$speed) 	{$speed = 1;}
	$timeout 		= round($timeout * 1000);
	$speed 			= round($speed * 1000);
	$post_count     = (!$post_count) ? -1 : $post_count;
	if( $format != "template" ) 						{$prev_next = false;}
	if( !$img_size ) 								{$img_size = 'thumbnail';}
	if( $format == "list" AND $prev_next ) 			{$paged = true;}
	if( !trim($template_name) ) 					{$template_name = "default";}
	if( !trim($title_heading) ) 					{$title_heading =  apply_filters('testimonial_template_title_heading', 'h2', $template_name, $id);}
	if( !trim($excerpt_length) ) 					{$excerpt_length =  apply_filters('testimonial_template_excerpt_length', 20, $id);}


	// FILTER AVAILABLE FOR PAUSE ON HOVER
	// ONE PARAMETER PASSED IS THE ID OF THE TEMPLATE
	$pause_on_hover  = apply_filters('testimonial_template_hover', $pause_on_hover, $id );


	// STAR ICON
	$testimonial_moving_star 	= apply_filters( 'testimonial_moving_star', 'fa-star', $template_name, $id );
	if( $testimonial_moving_star != "" AND substr($testimonial_moving_star,0,3) != 'fa-' ){
		$testimonial_moving_star = "fa-" . $testimonial_moving_star;
	}
	
	// IF ID, QUERY FOR JUST THAT TEMPLATE
	$meta_query = array();
	if( !$testimonial_id AND $id ){
		 $meta_query = tm_testimonial_meta_query( $id );
	}
	// GET TESTIMONIALS
	$order_by = ($shuffle) ? 'rand' : 'menu_order';
	$testimonials_args = array(
							'post_type' => 'testimonial',
							'order' => apply_filters( 'testimonial_template_order', 'ASC', $template_name, $id ),
							'orderby' => $order_by,
							'posts_per_page' => $post_count,
							'meta_query' => $meta_query
						);

	// IF SINGLE
	if( $testimonial_id ){
		 $testimonials_args['p'] = $testimonial_id;
	}

	// PAGING
	if( $paged ){
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$testimonials_args['paged'] = $paged;
	}
	
	$testimonials = new WP_Query(apply_filters( 'testimonial_template_display_args', $testimonials_args, $id ) );

	// TEMPLATE CLASSES
	$cycle_class 						= ($format == "template") ? " cycle-slideshow" : '';
	$template_class_prefix 				= ($is_widget) ? '_widget' : '';
	if($extra_classes){
		$cycle_class .= " $extra_classes ";
	}
	$cycle_class 						.= " format-{$format}";
	$cycle_class 						.= " template-{$template_name}";
	$extra_wrap_class 					= apply_filters( 'testimonial_template_extra_wrap_class', '', $template_name, $id );
	if( $is_single ){			
		$cycle_class .= " testimonial-template-single ";
	}
	
	
	// VERTICAL ALIGN
	$centered = "";
	if( $vertical_align ){
		$centered = " data-cycle-center-horz=\"true\" data-cycle-center-vert=\"true\" ";
	}

	// FX FILTER
	$fx = apply_filters( 'testimonial_template_fx', $fx, $template_name, $id );
	

	// PREV/NEXT BUTTON
	$prevnextdata = "";
	if( $prev_next ){
		$prevnextdata = " data-cycle-next=\"#testimonial_template{$template_class_prefix}_wrap_{$id} .testimonial_template_next\" data-cycle-prev=\"#testimonial_moving{$template_class_prefix}_wrap_{$id} .testimonial_moving_prev\" ";
		$extra_wrap_class .= " with-prevnext ";

		// PREV / NEXT FONT AWESOME ICONS, FILTER READY
		if( $fx == 'scrollVert'){
			$prev_fa_icon 	= apply_filters( 'testimonial_template_fa_icon_prev_vert', 'fa-chevron-down', $id, $template_name );
			$next_fa_icon 	= apply_filters( 'testimonial_template_fa_icon_next_vert', 'fa-chevron-up', $id, $template_name );
		}else{
			$prev_fa_icon 	= apply_filters( 'testimonial_template_fa_icon_prev', 'fa-chevron-left', $id, $template_name );
			$next_fa_icon 	= apply_filters( 'testimonial_template_fa_icon_next', 'fa-chevron-right', $id, $template_name );
		}
	}


	// SWIPE FILTER
	$touch_swipe = apply_filters( 'testimonial_template_swipe', 'true', $id );

	// EXTRA DATA ATTRIBUTE FILTER
	$extra_data_attributes = apply_filters( 'testimonial_template_data_attributes', '', $template_name, $id );
	
	// USED FOR SINGLE TEMPLATE, WHEN ON TESTIMONIAL PAGE
	$is_single_page = false;
	
	if( $testimonials->have_posts() ){
		$item_prop = ''; 
		if( $is_single ){
			$template_prefix = 'single';
		}else{
			$template_prefix = 'loop';
			if( $show_microdata ){
				 $cycle_class .= ' hreview-aggregate ';
			}
		}
		echo "<div id=\"testimonial_moving{$template_class_prefix}_wrap_{$id}\" class=\"testimonial_moving{$template_class_prefix}_wrap{$extra_wrap_class}\">\n";
		echo "	<div id=\"testimonial_moving{$template_class_prefix}_{$id}\" class=\"testimonial_moving {$template_class_prefix}{$cycle_class}\" data-cycle-timeout=\"{$timeout}\" data-cycle-speed=\"{$speed}\" data-cycle-pause-on-hover=\"{$pause_on_hover}\" {$centered} data-cycle-swipe=\"{$touch_swipe}\" data-cycle-fx=\"{$fx}\" data-cycle-auto-height=\"{$auto_height}\" {$prevnextdata}data-cycle-slides=\"{$div_selector}\" data-cycle-log=\"{$log} {$extra_data_attributes} \">\n";

		do_action( 'testimonial_template_slides_before' );
		

		// LOOK FOR TEMPLATE IN THEME
		$template = locate_template( array( "{$template_prefix}-testimonial-{$template_slug}.php", "{$template_prefix}-testimonial-{$id}.php", "{$template_prefix}-testimonial.php" ) );


		// LOOK FOR TEMPLATE IN CUSTOM TEMPLATE THEME
		if( !$template AND $template_name != "default" AND $template_name != "longform" AND file_exists( trailingslashit( get_stylesheet_directory() ) . "testimonial-moving/{$template_name}/{$template_prefix}-testimonial.php" ) ){
			$template = trailingslashit( get_stylesheet_directory() ) . "testimonial-moving/{$template_name}/{$template_prefix}-testimonial.php";
		} 
		// LOOK IN PLUGIN
		if( !$template ){
			if( file_exists(TESTIMONIAL_MOVING_DIR_PATH . "/templates/{$template_name}/{$template_prefix}-testimonial.php") ){
				$template = TESTIMONIAL_MOVING_DIR_PATH . "/templates/{$template_name}/{$template_prefix}-testimonial.php";
			}else{
				testimonial_moving_error( sprintf(__("The template: %s could be found", "testimonial-moving"), $template_name ) );
			}
		}
	
		// RATING
		$rating_count = 0;

		$slide_count = 1;
		$extra_slide_count = 1;
		$total_count = $testimonials->found_posts;
		while ( $testimonials->have_posts() ){
			$testimonials->the_post();

			// HAS IMAGE, CAN BE HIDDEN IN TEMPLATE SETTINGS
			$has_image = has_post_thumbnail() ? "has-image" : false;

			// DATA
			if( !$itemreviewed ){
				$itemreviewed_meta = get_post_meta( $id, '_itemreviewed', true );
				if( $itemreviewed_meta ){
					$itemreviewed = $itemreviewed_meta;
				}else{
					$itemreviewed = get_bloginfo('name');
				}	
			}
			
			$author_name = get_post_meta( get_the_ID(), '_author_name', true );
			$company = get_post_meta( get_the_ID(), '_company', true );
			$rating = (int) get_post_meta( get_the_ID(), '_rating', true );
			
			// RATING COUNT
			if( $rating ){
				$rating_count++;
			}

			// LOAD TEMPLATE
			if( $template ){
				 include( $template );
			}
			// SLIDE COUNTER
			$slide_count++;
		}


		// GLOBAL RATING
		$post_count = $testimonials->post_count;
		if( $show_microdata ){
			echo "<div class=\"testimonial_moving_microdata\">\n";
			echo "\t<div class=\"rating\">" . testimonial_moving_rating($id, 'rating') . "</div>\n";
			echo "\t<div class=\"count\">{$rating_count}</div>\n";
			echo "\t<div class=\"item\"><div class=\"fn\">{$itemreviewed}</div></div>\n";
			echo "</div>\n";
		}

		do_action( 'testimonial_template_after' );

		echo "</div><!-- #testimonial_moving{$template_class_prefix}_{$id} -->\n";

		// PREVIOUS / NEXT
		if( $prev_next AND $post_count > 1 ){
			echo "<div class=\"testimonial_moving_nav\">";
				echo "	<div class=\"testimonial_moving_prev\"><i class=\"fa {$prev_fa_icon}\"></i></div>";
				echo "	<div class=\"testimonial_moving_next\"><i class=\"fa {$next_fa_icon}\"></i></div>";
			echo "</div>\n";
		}

		echo "</div><!-- .testimonial_moving{$template_class_prefix}_wrap -->\n\n";
		
		if( $paged ){
			echo "<div class=\"testimonial_moving_paged cf-tr\">";
				next_posts_link( __('Next Testimonials', 'testimonial-moving') . ' <i class="fa fa-angle-double-right"></i>', $testimonials->max_num_pages );
				previous_posts_link( '<i class="fa fa-angle-double-left"></i> ' . __('Previous Testimonials', 'testimonial-moving') );
			echo "</div>\n";
		}
	}
	wp_reset_postdata();
}

// ERROR HANDLING
function testimonial_moving_error( $msg ){
	$error_handling = get_option( 'testimonial-moving-error-handling' );
	if(!$error_handling){
		 $error_handling = "source";
	}
	if(!$msg){
		 $msg = __('Something unknown went wrong', 'testimonial-moving');
	}
	
	if( $error_handling == "display-admin"){
		// DISPLAY ADMIN
		if ( current_user_can( 'manage_options' ) ){
			echo "<div class='testimonial-moving-error'>" . $msg . "</div>";
		}
	}elseif( $error_handling == "display-all"){
		// DISPLAY ALL
		echo "<div class='testimonial-moving-error'>" . $msg . "</div>";
	}else{
		echo apply_filters( 'testimonial_moving_error', "<!-- TESTIMONIAL TEMPLATE ERROR: " . $msg . " -->" );
	}
}
function testimonial_moving_excerpt( $limit = 25, $more = null ){
	$excerpt = get_post_field('post_excerpt', get_the_ID());
	if( !$excerpt ){
		$content = trim(get_the_content());
		if( $content ){
			 $excerpt = $content;
		}
	}
	// SET MORE
	if( $more ){
		 $more = "... <a href=\"" . get_permalink(get_the_ID()) . "\" class=\"testimonial_moving_read_all\">" . $more . "</a>";
	}else{
		$more = apply_filters( 'testimonial_moving_the_excerpt_empty', '...' );
	}
	echo apply_filters('testimonial_moving_the_excerpt', wp_trim_words($excerpt, $limit, $more), $limit );
}
function testimonial_template_rating_shortcode( $atts ){
	$id = 0;
	if( isset($atts['id']) ){
		 $id = (int) $atts['id'];
	}
	if( !$id ){
		 return false;
	}
	$return = isset($atts['return']) ? $atts['return'] : null;
	if( $return == 'data' ){
		 $return = null;
	}
	return testimonial_moving_rating( $id, $return );
}