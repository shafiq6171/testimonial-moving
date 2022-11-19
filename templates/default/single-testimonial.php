<?php

global $post;

// WRAPPER
$hclass = $show_microdata ? 'hreview itemreviewed' : '';
echo "<div class=\"testimonial_moving testimonial_moving_single template-{$template_name} {$hclass} item {$has_image} cf-tr\">\n";		

// POST THUMBNAIL
if ( $has_image AND $show_image ){
	 echo "<div class=\"testimonial_moving_img img\">" . get_the_post_thumbnail( $testimonial_id, $img_size ) . "</div>\n"; 
}

// DESCRIPTION
echo "<div class=\"text testimonial_moving_description\">\n";

// IF SHOW TITLE
if( $show_title ){
	 echo "<{$title_heading} class=\"testimonial_moving_slide_title\">" . get_the_title() . "</{$title_heading}>\n";
}

// RATING
if( $rating AND $show_stars ){
	echo "<div class=\"testimonial_moving_stars cf-tr\">\n";
	for($r=1; $r <= $rating; $r++){
		echo "	<span class=\"testimonial_moving_star testimonial_moving_star_$r\"><i class=\"fa {$testimonial_moving_star}\"></i></span>";
	}
	echo "</div>\n";
}

// CONTENT
if( $show_body ){
	echo "<div class=\"testimonial_moving_quote\">\n";
	echo get_the_content();
	echo "</div>\n";
}

// AUTHOR INFO
if( $author_name AND $show_author ){
	echo "<div class=\"testimonial_moving_author_info cf-tr\">\n";
	echo esc_attr($author_name);
	echo "</div>\n";				
}
// company INFO
if( $company AND $show_company ){
	echo "<div class=\"testimonial_moving_author_info cf-tr\">\n";
	echo wp_kses_post($company);
	echo "</div>\n";				
}
echo "	</div>\n";
	
// MICRODATA 
if( $show_microdata ){
	if( !$itemreviewed ){
		$itemreviewed = get_bloginfo('name');
	}
	
	echo "	<div class=\"testimonial_moving_microdata\">\n";
		if($itemreviewed){
			 echo "\t<div class=\"item\"><div class=\"fn\">{$itemreviewed}</div></div>\n";
		}
		if($rating){
			 echo "\t<div class=\"rating\">{$rating}</div>\n";
		}

		echo "	<div class=\"dtreviewed\"> " . get_the_date('c') . "</div>";
		echo "	<div class=\"reviewer\"> ";
			echo "	<div class=\"fn\"> " . esc_attr($author_name) . "</div>";
			echo "	<div class=\"fn\"> " . wp_kses_post($company) . "</div>";
			if( has_post_thumbnail()) { echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array('class' => 'photo' )); }
		echo "	</div>";
		echo "	<div class=\"summary\"> " . testimonial_moving_excerpt(apply_filters('testimonial_moving_microdata_summary_length', 300)) . "</div>";
		echo "	<div class=\"permalink\"> " . get_permalink() . "</div>";
	echo "	</div><!-- .testimonial_moving_microdata -->\n";
}

echo "</div>\n";