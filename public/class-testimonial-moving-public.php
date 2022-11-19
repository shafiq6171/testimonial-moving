<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profile.webmonch.com
 * @since      1.0.0
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/public
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Testimonial_Moving_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Testimonial_Moving_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Testimonial_Moving_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/testimonial-moving.css', array(), $this->version, 'all' );

		$hide_font_awesome = get_option( 'testimonial-moving-hide-fontawesome' );
		$hide_font_awesome = ($hide_font_awesome == 1) ? true : false;

		if( !$hide_font_awesome ){
			$font_awesome_version = apply_filters( 'testimonial_moving_font_awesome_version', 'latest' );
			wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/' . $font_awesome_version . '/css/font-awesome.min.css' );
		}
		
		// CUSTOM CSS, DEFINE IN THE ADMIN UNDER TESTIMONIALS -> SETTINGS
		$custom_css = get_option( 'testimonial-moving-custom-css' );
		if( $custom_css ){
			wp_add_inline_style('testimonial-moving-style', $custom_css);
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Testimonial_Moving_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Testimonial_Moving_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$load_scripts_in_footer = apply_filters( 'testimonial_moving_scripts_in_footer', false );

		wp_enqueue_script( 'cycletwo', plugins_url('/js/jquery.cycletwo.js', __FILE__), array('jquery'), false, $load_scripts_in_footer );
		wp_enqueue_script( 'cycletwo-addons', plugins_url('/js/jquery.cycletwo.addons.js', __FILE__), array('jquery', 'cycletwo'), false, $load_scripts_in_footer );
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/testimonial-moving.js', array('jquery'), $this->version, true );

	}
	// CREATES THE CUSTOM POST TYPE
	public function testimonial_moving_init(){
		// REGISTER SHORTCODES
		add_shortcode( 'testimonial_template', 'tm_testimonial_template_shortcode' );
		add_shortcode( 'testimonial-template', 'tm_testimonial_template_shortcode' );
		add_shortcode( 'testimonial_single', 'tm_testimonial_single_shortcode' );
		add_shortcode( 'testimonial-single', 'tm_testimonial_single_shortcode' );
	
		add_shortcode( 'testimonial_template_rating', 'testimonial_template_rating_shortcode' );

		// POST THUMBNAILS (pippin)
		if( !current_theme_supports('post-thumbnails') ) {
			add_theme_support('post-thumbnails'); 
		}
		
		// ARCHIVE PAGE SLUG
		$archive_slug 			= 'testimonials';
		$archive_slug_filter 	= apply_filters( 'testimonial_moving_testimonial_slug', false);
		if( $archive_slug_filter ){
			$archive_slug = $archive_slug_filter;
		}
		// TESTIMONIAL CUSTOM POST TYPE
		$labels = array(
						'name' 					=> __('Testimonials', 'testimonial-moving'),
						'singular_name' 		=> __('Testimonial', 'testimonial-moving'),
						'add_new' 				=> __('Add New', 'testimonial-moving'),
						'add_new_item' 			=> __('Add New Testimonial', 'testimonial-moving'),
						'edit_item' 			=> __('Edit Testimonial', 'testimonial-moving'),
						'new_item' 				=> __('New Testimonial', 'testimonial-moving'),
						'all_items' 			=> __('All Testimonials', 'testimonial-moving'),
						'view_item' 			=> __('View Testimonial', 'testimonial-moving'),
						'search_items' 			=> __('Search Testimonials', 'testimonial-moving'),
						'not_found' 			=>  __('No testimonials found', 'testimonial-moving'),
						'not_found_in_trash' 	=> __('No testimonials found in Trash', 'testimonial-moving'),
						'parent_item_colon' 	=> '',
						'menu_name'				=> __('Testimonials', 'testimonial-moving')
						);
		$args = array(
						'labels' 				=> $labels,
						'public' 				=> true,
						'publicly_queryable' 	=> true,
						'show_ui' 				=> true,
						'show_in_menu' 			=> true,
						'query_var' 			=> true,
						'rewrite' 				=> array( 'slug' => $archive_slug ),
						'capability_type' 		=> 'post',
						'has_archive' 			=> true,
						'hierarchical' 			=> false,
						'menu_position' 		=> apply_filters( 'testimonial_moving_menu_position', 26.6 ),
						'exclude_from_search' 	=> true,
						'supports' 				=> apply_filters( 'testimonial_moving_testimonial_supports', array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields' ) )
						);

		register_post_type( 'testimonial', apply_filters( 'testimonial_moving_pt_args', $args ) );

		// TESTIMONIAL MOVING CUSTOM POST TYPE
		$template_labels = array(
						'name' 					=> __('Testimonial templates', 'testimonial-moving'),
						'singular_name' 		=> __('templates', 'testimonial-moving'),
						'add_new' 				=> __('Add New', 'testimonial-moving'),
						'add_new_item' 			=> __('Add New template', 'testimonial-moving'),
						'edit_item' 			=> __('Edit template', 'testimonial-moving'),
						'new_item' 				=> __('New template', 'testimonial-moving'),
						'all_items' 			=> __('All templates', 'testimonial-moving'),
						'view_item' 			=> __('View template', 'testimonial-moving'),
						'search_items' 			=> __('Search templates', 'testimonial-moving'),
						'not_found' 			=>  __('No templates found', 'testimonial-moving'),
						'not_found_in_trash' 	=> __('No templates found in Trash', 'testimonial-moving'),
						'parent_item_colon' 	=> '',
						'menu_name'				=> __('Templates', 'testimonial-moving')
						);

		$template_args = array(
						'labels' 				=> $template_labels,
						'public' 				=> false,
						'publicly_queryable' 	=> false,
						'show_ui' 				=> true,
						'show_in_menu' 			=> false,
						'query_var' 			=> true,
						'rewrite' 				=> array( 'with_front' => false ),
						'capability_type' 		=> 'post',
						'has_archive' 			=> false,
						'hierarchical' 			=> false,
						'menu_position' 		=> apply_filters( "testimonial_moving_menu_position", 26.6) + .1,
						'exclude_from_search' 	=> true,
						'supports' 				=> apply_filters( "testimonial_moving_supports", array( 'title', 'custom-fields' ) ),
						'show_in_menu'  		=> 'edit.php?post_type=testimonial',
						);
						
		register_post_type( 'testimonial_template', apply_filters( 'testimonial_templates_pt_args', $template_args )  );
	}

}
