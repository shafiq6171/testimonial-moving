<?php 
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ){
	 exit;
}
function tm_testimonial_meta_query( $id ){
	return array( 'relation' => 'OR',
						array(
							'key' 		=> '_template_id',
							'value' 	=> $id
						),
						array(
							'key' 		=> '_template_id',
							'value' 	=> '|' . $id . '|',
							'compare'	=> 'LIKE'
						)
				);
}
function testimonial_moving_rating( $id = false, $return = 'stars' ){
	if( !$id ){
		 return false;
	}
	
	global $post;
	
	$global_rating = $testimonial_count = 0;
	
	$testimonials_args 	= array( 'post_type' => 'testimonial', 'posts_per_page' => -1, 'meta_query' => tm_testimonial_meta_query( $id ) );	
	$testimonials 		= new WP_Query( apply_filters( 'testimonial_template_rating_display_args', $testimonials_args, $id ) );			
				
	if( $testimonials->have_posts() ){
		while ( $testimonials->have_posts() ){
			$testimonials->the_post();	
			$rating = (int) get_post_meta( get_the_ID(), '_rating', true );
			if( $rating ){
				$global_rating += $rating;
				$testimonial_count++;
			}
		}
	}

	$global_rating_number = 0;
	if( $testimonial_count > 1 ){
		 $global_rating_number = round($global_rating / $testimonial_count, 1);
	}


	// RETURN OPTIONS
	if( $return == 'rating' ){
		return $global_rating_number;
	}elseif( $return == 'data' ){
		$obj = new stdclass;
		$obj->total_ratings 	= $testimonial_count;
		$obj->rating 			= $global_rating_number;
		return $obj;
	}else{
		$global_rating_number = (int) $global_rating_number;
		
		$testimonial_moving_star = apply_filters( 'testimonial_moving_star', 'fa-star', 'rating', $id );
		if( $testimonial_moving_star != '' AND substr($testimonial_moving_star,0,3) != 'fa-' ){
			$testimonial_moving_star = 'fa-' . $testimonial_moving_star;
		}
		
		$rtn = "<span class=\"testimonial_moving_stars cf-tr\">\n";
		for($r=1; $r <= $global_rating_number; $r++)
		{
			$rtn = $rtn . "<span class=\"testimonial_moving_star testimonial_moving_star_$r\"><i class=\"fa {$testimonial_moving_star}\"></i></span>";
		}
		$rtn = $rtn . "</span>\n";
		return $rtn;
	}
	
	wp_reset_postdata();

	return $global_rating_number;
}
// SETUP THE BASE TRANSITION ARRAY
function testimonial_moving_base_transitions(){
	return apply_filters( "testimonial_moving_base_transitions", array('fade', 'fadeout', 'scrollHorz', 'scrollVert', 'flipHorz', 'flipVert', 'none') );
}

function testimonial_template_available_themes(){
	$themes = array();
	$themes['default'] 		= array('title' => 'Default');
	$themes['longform'] 	= array('title' => 'Longform');
	$themes['onepig'] 		= array('title' => 'One Little Pig');
	$themes['twopigs'] 		= array('title' => 'Two Little Pigs');
	$themes['threepigs'] 	= array('title' => 'Three Little Pigs');
	$themes['fourpigs'] 	= array('title' => 'Four Little Pigs');
	$themes['starrynight'] 	= array('title' => 'Starry Night');
	$themes['headlined'] 	= array('title' => 'Headlined');
	
	return (array) apply_filters( 'testimonial_moving_themes', $themes );
}