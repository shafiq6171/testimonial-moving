<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profile.webmonch.com
 * @since      1.0.0
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/admin
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Testimonial_Moving_Admin {

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
	private $tm_metabox;
	private $tm_template_metabox;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();
	}
	private function load_dependencies() {
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-testimonial-moving-admin-metaboxes-testimonial.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-testimonial-moving-admin-metaboxes-testimonial-template.php';
		$this->tm_metabox = new Testimonial_Moving_Admin_Metaboxes_Testimonial();
		$this->tm_template_metabox = new Testimonial_Moving_Admin_Metaboxes_Template();
	}
	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/testimonial-moving-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/testimonial-moving-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function testimonial_moving_create_metaboxes() {
		// TESTIMONIAL OPTIONS
		add_meta_box( 'testimonial_options_metabox_select', __('Testimonial Options', 'testimonial-moving'), [$this->tm_metabox,'testimonial_options_metabox_select'], 'testimonial', 'normal', 'default' );
		
		// TESTIMONIAL IMAGE
		add_meta_box( 'postimagediv', __('Testimonial Image', 'testimonial-moving'), 'post_thumbnail_meta_box', 'testimonial', 'normal', 'default');
		
		// TESTIMONIAL ORDER
		add_meta_box( 'pageparentdiv', __('Testimonial Order', 'testimonial-moving'), 'page_attributes_meta_box', 'testimonial', 'normal', 'default');
		
		// IF ON EDIT SHOW THE SHORTCODE
		if(isset($_GET['action']) AND $_GET['action'] == "edit"){
			// ASSOCIATED TESTIMONIALS
			add_meta_box( 'testimonial_template_testimonial_count_meta', __('Associated Testimonials', 'testimonial-moving'),[$this->tm_template_metabox, 'testimonial_template_testimonial_count_meta'], 'testimonial_template', 'side', 'default' );
			// SHORTCODE HELPERS
			add_meta_box( 'testimonial_moving_shortcode_metabox', __('Moving Shortcode', 'testimonial-moving'), 'testimonial_moving_shortcode_metabox', 'testimonial_template', 'side', 'default' );
		}
		// Template OPTIONS
	add_meta_box( 'testimonial_template_metabox_effects', __('Moving Options', 'testimonial-moving'), [$this->tm_template_metabox,'testimonial_template_metabox_effects'], 'testimonial_template', 'normal', 'default' );
	}
	/* SAVE TESTIMONIAL META DATA */
	public function testimonial_moving_save_testimonial_meta( $post_id, $post ) {
		global $post;  
		if( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == "testimonial" ){
			// SAVE
			if ( isset( $_POST['template_id'] ) ){
				if( is_array($_POST['template_id'])){
					$template_ids = map_deep($_POST['template_id'], 'sanitize_text_field');
					update_post_meta($post_id, '_template_id', testimonial_moving_make_piped_string($template_ids));
				}else{
					$template_id = sanitize_text_field($_POST['template_id']);
					
					update_post_meta( $post_id, '_template_id',$template_id); 
				}
			}else{
				update_post_meta( $post_id, '_template_id', '' ); 
			}
			
			if( isset( $_POST['rating'] ) ){
				$rating = sanitize_text_field($_POST['rating']);
				update_post_meta( $post_id, '_rating',$rating); 
			}
			
			if( isset( $_POST['author_name'] ) ){
				$author_name = sanitize_text_field($_POST['author_name']);
				update_post_meta( $post_id, '_author_name', $author_name); 
			}
			if( isset( $_POST['company'] ) ){
				$company = sanitize_textarea_field($_POST['company']);
				update_post_meta( $post_id, '_company', wp_kses($company, wp_kses_allowed_html())); 
			}
			
		}
	}
	/* SAVE TESTIMONIAL TEMPLATE META DATA */
	public function testimonial_moving_save_testimonial_template_meta( $post_id, $post ) {
		global $post;  
		if( isset( $_POST ) && isset( $post->ID ) ){

			// INPUTS
			if( isset( $_POST['fx'] ) ) 				{ update_post_meta( $post->ID, '_fx', sanitize_text_field( $_POST['fx'] ) ); }
			if( isset( $_POST['timeout'] ) ) 			{ update_post_meta( $post->ID, '_timeout', sanitize_text_field( $_POST['timeout'] ) ); }
			if( isset( $_POST['speed'] ) ) 			{ update_post_meta( $post->ID, '_speed', sanitize_text_field( $_POST['speed'] ) ); }
			if( isset( $_POST['limit'] ) ) 			{ update_post_meta( $post->ID, '_limit', sanitize_text_field( $_POST['limit'] ) ); }
			if( isset( $_POST['itemreviewed'] ) ) 		{ update_post_meta( $post->ID, '_itemreviewed', sanitize_text_field( $_POST['itemreviewed'] ) ); }
			if( isset( $_POST['template'] ) ) 			{ update_post_meta( $post->ID, '_template', sanitize_text_field($_POST['template']) ); }
			if( isset( $_POST['img_size'] ) ) 			{ update_post_meta( $post->ID, '_img_size', sanitize_text_field( $_POST['img_size'] ) ); }
			if( isset( $_POST['title_heading'] ) ) 	{ update_post_meta( $post->ID, '_title_heading', sanitize_text_field( $_POST['title_heading'] ) ); }

			// CHECKBOXES
			update_post_meta( $post->ID, '_shuffle', isset( $_POST['shuffle']) ? 1 : 0 );
			update_post_meta( $post->ID, '_verticalalign', isset( $_POST['verticalalign']) ? 1 : 0 );
			update_post_meta( $post->ID, '_prevnext', isset( $_POST['prevnext']) ? 1 : 0 );
			update_post_meta( $post->ID, '_hidefeaturedimage', isset( $_POST['hidefeaturedimage']) ? 1 : 0 );
			update_post_meta( $post->ID, '_hide_microdata', isset( $_POST['hide_microdata']) ? 1 : 0 );
			update_post_meta( $post->ID, '_hide_title', isset( $_POST['hide_title']) ? 1 : 0 );
			update_post_meta( $post->ID, '_hide_stars', isset( $_POST['hide_stars']) ? 1 : 0 );
			update_post_meta( $post->ID, '_hide_body', isset( $_POST['hide_body']) ? 1 : 0 );
			update_post_meta( $post->ID, '_hide_author', isset( $_POST['hide_author']) ? 1 : 0 );
			update_post_meta( $post->ID, '_hide_company', isset( $_POST['hide_company']) ? 1 : 0 );
		}
	}

	/* ADMIN COLUMNS IN LIST VIEW */
	public function testimonial_testimonial_columns( $columns ){
		$columns = array(
			'cb'       		=> '<input type="checkbox" />',
			'image'    		=> __('Image', 'testimonial-moving'),
			'title'    		=> __('Title', 'testimonial-moving'),
			'rating'    	=> __('Rating', 'testimonial-moving'),
			'ID'       		=> __('Templates', 'testimonial-moving'),
			'order'    		=> __('Order', 'testimonial-moving'),
			'author_info'   => __('Author Information', 'testimonial-moving'),
			'shortcode' 	=> __('Shortcode', 'testimonial-moving')
		);

		return $columns;
	}
	
	public function testimonial_add_table_columns( $column, $post_id ) {
		$edit_link = get_edit_post_link( $post_id );
		$template_ids = testimonial_moving_break_piped_string( get_post_meta( $post_id, "_template_id", true ) );
		
		$template_title = "";
		foreach($template_ids as $template_id) {
			$template_title .= "<a href='post.php?action=edit&post=" . $template_id . "'>" . get_the_title( $template_id ) . "</a>"; 
		}
		$this_testimonial = get_post($post_id);
		if( $column == 'ID' ){
			echo $template_title;
		}elseif($column == 'image' ){
			echo '<a href="' . esc_url($edit_link) . '">' . get_the_post_thumbnail( $post_id, array( 50, 50 )) . '</a>';
		}elseif( $column == 'order' ){
			echo '<a href="' . esc_url($edit_link) . '">' . $this_testimonial->menu_order . '</a>';
		}elseif($column == 'rating' ){
			echo esc_attr(get_post_meta( $post_id, "_rating", true));
		}elseif($column == 'author_info' ){
			echo esc_attr(get_post_meta( $post_id, "_author_name", true));
		}elseif($column == 'shortcode' ){
			echo '<b>' . __('Display as Single' , 'testimonial-moving') . '</b><br />'; 
			echo '[testimonial_single id="' . $post_id . '"]';
		}
	}
	public function testimonial_table_column_sort($columns){
		$columns = array(
			'ID'       => __('Templates', 'testimonial-moving'),
			'title'    => 'title',
			'order'    => 'menu_order'
		);
		return $columns;
	}
	/* PARSE TESTIMONIALS BY TEMPLATE ID */
	public function testimonial_moving_parse_testimonials_by_template_id( $query ){
		global $pagenow;
		if( $pagenow == "edit.php" AND isset($query->query['post_type']) AND $query->query['post_type'] == "testimonial" AND isset($_GET['template_id']) ){
			// GET TESTIMONIALS ONLY FOR THIS TEMPLATE
			$id = sanitize_text_field($_GET['template_id']) ;
			$query->query_vars['meta_query'] = array( 'relation' => 'OR',
									array(
										'key' 		=> '_template_id',
										'value' 	=> $id
									),
									array(
										'key' 		=> '_template_id',
										'value' 	=> '|' . $id . '|',
										'compare'	=> 'LIKE'
									));				
		}
	}
	public function testimonial_template_table_columns( $columns ) {
		$columns = array(
			'cb'       		=> '<input type="checkbox" />',
			'title'    		=> __('Title', 'testimonial-moving'),
			'theme'    		=> __('Theme', 'testimonial-moving'),
			'count'    		=> __('Testimonial Count', 'testimonial-moving'),
			'aggregate'    	=> __('Aggregate Rating', 'testimonial-moving'),
			'shortcode'		=> __('Shortcodes', 'testimonial-moving')
		);

		return $columns;
	}
	
	public function testimonial_template_add_table_columns( $column, $post_id ) {
		if( $column == 'shortcode' ){
			echo '
				<b>' . __('Use Template Settings' , 'testimonial-moving') . '</b><br />
				[testimonial_template id=' . $post_id . ']<br /><br />
				
				<b>' . __('Display as List' , 'testimonial-moving') . '</b><br />
				[testimonial_template id=' . $post_id . ' format=list]
			'; 
		}elseif( $column == 'theme' ) {
			$theme = get_post_meta( $post_id, '_template', true );
			if(!$theme){
				echo ucwords($theme);
			}
		}elseif( $column == 'aggregate' ){
			echo testimonial_moving_rating( $post_id, 'rating' );
		}elseif( $column == 'count' ){
			$meta_query = array( 'relation' => 'OR',
									array(
										'key' 		=> '_template_id',
										'value' 	=> $post_id
									),
									array(
										'key' 		=> '_template_id',
										'value' 	=> '|' . $post_id . '|',
										'compare'	=> 'LIKE'
									));
			$args = array( 'posts_per_page' => -1, 'post_type' => 'testimonial', 'meta_query' => $meta_query );
			$count_query = new WP_Query( $args );
			
			if( !$count_query->found_posts ){
				echo __("None assigned yet", "testimonial-moving");
			}else{
				echo "<a href=\"edit.php?post_type=testimonial&template_id=" . $post_id . "\">" .  number_format($count_query->found_posts) . "</a>";	
			}
			wp_reset_postdata();
		}							
	}
	/* ADMIN ICON */
	public function testimonial_moving_menu_cpt_icon() {
		global $wp_version;
		if( $wp_version >= 3.8 ){
			echo '
				<style> 
					#adminmenu #menu-posts-testimonial div.wp-menu-image:before { content: "\f205"; }
				</style>
			';	
		}
	}
	/*  SUBMENU PAGE */
	public function register_testimonial_moving_submenu_page(){
		global $current_user;
		
		// ABILITY TO EDIT TEMPLATES FOR ADMINS
		add_submenu_page( 'edit.php?post_type=testimonial', __('Add Template', 'testimonial-moving'), __('Add Template', 'testimonial-moving'), 'manage_options', 'post-new.php?post_type=testimonial_template' ); 
		
		// SETTINGS PAGE
		add_submenu_page( 'edit.php?post_type=testimonial', __('Settings', 'testimonial-moving'), __('Settings', 'testimonial-moving'), 'manage_options', 'testimonial-moving', [$this,'testimonial_moving_settings_callback'] );
		
		if( !current_user_can('manage_options') ){
			$current_user_roles = (array) $current_user->roles;
			// ADD THE EDIT  PAGE FOR OTHER ROLES THAT ARE SELECTED IN SETTINGS
			$creator_setting = (array) get_option( 'testimonial-moving-creator-role' );
			if($creator_setting AND $current_user_roles ){
				foreach( $current_user_roles as $role){
					if( in_array( $role, $creator_setting)){
						add_submenu_page( 'edit.php?post_type=testimonial', __('Templates', 'testimonial-moving'), __('Templates', 'testimonial-moving'), $role, 'edit.php?post_type=testimonial_template' ); 
						add_submenu_page( 'edit.php?post_type=testimonial', __('Add New Template', 'testimonial-moving'), __('Add New Template', 'testimonial-moving'), $role, 'post-new.php?post_type=testimonial_template' ); 
						break;
					}
				}
			}
		}
	}
	public function testimonial_moving_settings_callback(){
		include_once TESTIMONIAL_MOVING_DIR_PATH . 'admin/partials/testimonial-moving-admin-menu.php';
	}
	/* TITLE INPUT FOR TESTIMONIALS */
	public function register_testimonial_form_title( $title ){
		 $screen = get_current_screen();
		 if( $screen->post_type == 'testimonial' ){
			  return __('Enter Highlight Here', 'testimonial-moving');
		 }
	}
	public function tm_admin_general_settings_page( $settings_general ){
		$settings_general = array(
			array(
				'title' => __( 'Error Handling', 'testimonial-moving' ),
				'type'  => 'radio',
				'description'  => __( 'What should the plugin do when there is an error?', 'testimonial-moving' ),
				'id'    => 'testimonial-moving-error-handling',
				'class' => 'regular-text',
				'style_type' => 'wps-form-field-inline',
				'options'=> array(
					'source'=>'Hidden in Source',
					'display-admin'=>'Display if Admin',
					'display-all'=>'Display for Anyone'
				),
				'value' => esc_attr(get_option( 'testimonial-moving-error-handling', '' ))
			),
			array(
				'title' => __( 'Who Sees template Menu?', 'testimonial-moving' ),
				'type'  => 'multicheck',
				'description'  => __( 'Select the roles that will be able to see the template List View and \'Add New\' Button in the admin menu.', 'testimonial-moving' ),
				'id'    => 'testimonial-moving-creator-role',
				'class' => 'regular-text',
				'style_type' => 'wps-form-field-inline',
			),
			array(
				'title' => __( 'Hide Font Awesome?', 'testimonial-moving' ),
				'type'  => 'checkbox',
				'description'  => __( 'Already loading Font Awesome with your theme or another plugin? You can turn off our version here.', 'testimonial-moving' ),
				'id'    => 'testimonial-moving-hide-fontawesome',
				'class' => 'regular-text',
				'value' => '1',
				'checked' => ( '1' === esc_attr(get_option( 'testimonial-moving-hide-fontawesome', '' )) ? '1' : '' ),
			),
			array(
				'title' => __( 'Archive Slug', 'testimonial-moving' ),
				'type'  => 'text',
				'id'    => 'testimonial-moving-archive-slug',
				'value' => esc_attr(get_option( 'testimonial-moving-archive-slug', '' )),
				'class' => 'regular-text',
				'placeholder' => 'testimonials',
				'description'  => __( 'This is the slug that will be used for the Testimonials custom post type archive page.<p><b>Note:</b> If you are not seeing your changes, you may need to <a href="http://localhost/plugin-development/wp-admin/options-permalink.php">resave your permalinks on this page.</a></p>', 'testimonial-moving' ),
			),
			array(
				'title' => __( 'Custom CSS', 'testimonial-moving' ),
				'type'  => 'textarea',
				'id'    => 'testimonial-moving-custom-css',
				'value' => esc_attr(get_option( 'testimonial-moving-custom-css', '' )),
				'class' => 'regular-text',
				'placeholder' => '',
				'description'  => __( 'This custom CSS will get inserted after the testimonial moving CSS is loaded so you can override the styles.', 'testimonial-moving' ),
			),
			array(
				'type'  => 'button',
				'id'    => 'swa_save_general_settings',
				'button_text' => __( 'Save Settings', 'testimonial-moving' ),
				'class' => 'sfw-button-class ',
			),
		);
		// Add general settings.
		return apply_filters( 'tmr_add_general_settings_fields', $settings_general );
	}
	/**
	 * testimonial roator settings tab save.
	 *
	 * @name tmr_admin_save_settings.
	 * @since 1.0.0
	 */
	public function tmr_admin_save_settings() {
		global $testimonial_moving_obj;
		global $testimonial_moving_notices;
		if( isset( $_POST['swa_save_general_settings'] ) && isset( $_POST['wps-general-nonce-field'] ) ) {

			$wps_geberal_nonce = sanitize_text_field( wp_unslash( $_POST['wps-general-nonce-field'] ) );
			if( wp_verify_nonce( $wps_geberal_nonce, 'wps-general-nonce' ) ) {
				$wps_gen_flag = false;
				// General settings.
				$wps_genaral_settings = apply_filters( 'tm_general_settings_array', array() );
				
				$wps_button_index = array_search( 'submit', array_column( $wps_genaral_settings, 'type' ) );
				if( isset( $wps_button_index ) && ( null == $wps_button_index || '' == $wps_button_index ) ) {
					$wps_button_index = array_search( 'button', array_column( $wps_genaral_settings, 'type' ) );
				}
				if( isset( $wps_button_index ) && '' !== $wps_button_index ) {
					unset( $wps_genaral_settings[ $wps_button_index ] );
					if( is_array( $wps_genaral_settings ) && ! empty( $wps_genaral_settings ) ) {
						foreach ( $wps_genaral_settings as $wps_genaral_setting ) {
							if( isset( $wps_genaral_setting['id'] ) && '' !== $wps_genaral_setting['id'] ) {
								if( isset( $_POST[ $wps_genaral_setting['id'] ] ) && ! empty( $_POST[ $wps_genaral_setting['id'] ] ) ) {
									if(is_array($_POST[$wps_genaral_setting['id']])){
										$posted_value = map_deep($_POST[ $wps_genaral_setting['id']], 'sanitize_text_field');
										update_option( $wps_genaral_setting['id'], $posted_value );
									}else{
										$posted_value = sanitize_text_field( wp_unslash( $_POST[ $wps_genaral_setting['id'] ] ) );
										update_option( $wps_genaral_setting['id'], $posted_value );
									}
								} else {
									update_option( $wps_genaral_setting['id'], '' );
								}
							} else {
								$wps_gen_flag = true;
							}
						}
					}
					if( $wps_gen_flag ) {
						$wps_error_text = esc_html__( 'Id of some field is missing', 'testimonial-moving' );
						$testimonial_moving_obj->tm_plug_admin_notice( $wps_error_text, 'error' );
					} else {
						$testimonial_moving_notices = true;
					}
				}
			}
		}
	}

}