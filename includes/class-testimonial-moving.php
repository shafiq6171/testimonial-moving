<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profile.webmonch.com
 * @since      1.0.0
 *
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Testimonial_Moving
 * @subpackage Testimonial_Moving/includes
 * @author     Shafiq <shafiq6171@gmail.com>
 */
class Testimonial_Moving {

	
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Testimonial_Moving_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if( defined( 'TESTIMONIAL_MOVING_VERSION' ) ) {
			$this->version = TESTIMONIAL_MOVING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'testimonial-moving';

		$this->load_dependencies();
		$this->set_locale();
		$this->moving_widgets();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Testimonial_Moving_Loader. Orchestrates the hooks of the plugin.
	 * - Testimonial_Moving_i18n. Defines internationalization functionality.
	 * - Testimonial_Moving_Admin. Defines all hooks for the admin area.
	 * - Testimonial_Moving_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-testimonial-moving-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-testimonial-moving-i18n.php';		
		
		/**
		 * The global funtions
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/testimonial-moving-fn.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-testimonial-moving-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/testimonial-moving-admin-functions.php';
		/**
		 * wp widgets.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/wp-widgets/class-testimonial-moving-widget.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-testimonial-moving-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/testimonial-moving-public-functions.php';
		$this->loader = new Testimonial_Moving_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Testimonial_Moving_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Testimonial_Moving_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	/**
	 * reigster widgets.
	 *
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function moving_widgets() {
		$this->loader->add_action( 'widgets_init', $this, 'testimonial_moving_widgets_register' );

	}
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Testimonial_Moving_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		if( is_admin() ){
			$this->loader->add_action( 'add_meta_boxes',  $plugin_admin,'testimonial_moving_create_metaboxes' );
			$this->loader->add_action( 'save_post_testimonial', $plugin_admin, 'testimonial_moving_save_testimonial_meta', 1, 3 );
			$this->loader->add_action( 'save_post_testimonial_template',  $plugin_admin,'testimonial_moving_save_testimonial_template_meta', 1, 3 );

			$this->loader->add_filter( 'manage_edit-testimonial_columns', $plugin_admin, 'testimonial_testimonial_columns' );
			$this->loader->add_action( 'manage_testimonial_posts_custom_column', $plugin_admin, 'testimonial_add_table_columns', 10, 2 );
			$this->loader->add_filter( 'manage_edit-testimonial_sortable_columns', $plugin_admin, 'testimonial_table_column_sort' );
			$this->loader->add_filter( 'parse_query', $plugin_admin, 'testimonial_moving_parse_testimonials_by_template_id' );

			$this->loader->add_filter( 'manage_edit-testimonial_template_columns', $plugin_admin, 'testimonial_template_table_columns' );
			$this->loader->add_action( 'manage_testimonial_template_posts_custom_column', $plugin_admin, 'testimonial_template_add_table_columns', 10, 2 );

			$this->loader->add_action( 'admin_head', $plugin_admin, 'testimonial_moving_menu_cpt_icon' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_testimonial_moving_submenu_page' );

			$this->loader->add_filter( 'enter_title_here', $plugin_admin, 'register_testimonial_form_title' );
			$this->loader->add_filter( 'tm_general_settings_array', $plugin_admin, 'tm_admin_general_settings_page', 10 );
			$this->loader->add_action( 'admin_init', $plugin_admin, 'tmr_admin_save_settings' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Testimonial_Moving_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'testimonial_moving_init' );
		

	}
	public function testimonial_moving_widgets_register(){
		register_widget( 'Testimonial_Moving_Widget' );
	}
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Testimonial_Moving_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
			/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 */
	public function tm_plug_load_template( $content_path ) {

		if( file_exists( $content_path ) ) {
			include $content_path;
		} else {
			/* translators: %s: file path */
			$tmr_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'testimonial-moving' ), $content_path );
			$this->tm_plug_admin_notice( $tmr_notice, 'error' );
		}
	}
	/**
	 * Show admin notices.
	 *
	 * @param  string $swa_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function tm_plug_admin_notice( $tmr_message, $type = 'error' ) {

		$tmr_classes = 'notice ';

		switch ( $type ) {
			
			case 'update':
				$tmr_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$tmr_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$tmr_classes .= 'notice-success is-dismissible';
				break;

			default:
				$tmr_classes .= 'notice-error is-dismissible';
		}

		$tmr_notice  = '<div class="' . esc_attr( $tmr_classes ) . ' wps-errorr-8">';
		$tmr_notice .= '<p>' . esc_html( $tmr_message ) . '</p>';
		$tmr_notice .= '</div>';

		echo wp_kses_post( $tmr_notice );
	}
	public function tm_plug_generate_html( $sfw_components = array() ) {
		if( is_array( $sfw_components ) && ! empty( $sfw_components ) ) {
			foreach ( $sfw_components as $sfw_component ) {
				$wps_sfw_name = array_key_exists( 'name', $sfw_component ) ? $sfw_component['name'] : $sfw_component['id'];
				switch ( $sfw_component['type'] ) {

					case 'hidden':
					case 'number':
					case 'email':
					case 'text':
						?>
					<div class="wps-form-group wps-<?php echo esc_attr( $sfw_component['type'] ); ?>">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="wps-form-group__control">
							<label class="wps-text-field wps-text-field--outlined">
						
								<input 
								class="wps-text-field__input <?php echo esc_attr( $sfw_component['class'] ); ?>" 
								name="<?php echo esc_attr( $wps_sfw_name ); ?>"
								id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $sfw_component['type'] ); ?>"
								value="<?php echo esc_attr( $sfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $sfw_component['placeholder'] ); ?>"
								>
							</label>
							<div class="wps-text-field-helper-line">
								<div class="wps-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo  $sfw_component['description'] ; ?></div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'password':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); // WPCS: XSS ok. ?></label>
						</div>
						<div class="wps-form-group__control">
							<label class="wps-text-field wps-text-field--outlined wps-text-field--with-trailing-icon">
								<span class="wps-notched-outline">
									<span class="wps-notched-outline__leading"></span>
									<span class="wps-notched-outline__notch">
									</span>
									<span class="wps-notched-outline__trailing"></span>
								</span>
								<input 
								class="wps-text-field__input <?php echo esc_attr( $sfw_component['class'] ); ?> wps-form__password" 
								name="<?php echo esc_attr( $wps_sfw_name ); ?>"
								id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
								type="<?php echo esc_attr( $sfw_component['type'] ); ?>"
								value="<?php echo esc_attr( $sfw_component['value'] ); ?>"
								placeholder="<?php echo esc_attr( $sfw_component['placeholder'] ); ?>"
								>
								<i class="material-icons wps-text-field__icon wps-text-field__icon--trailing wps-password-hidden" tabindex="0" role="button">visibility</i>
							</label>
							<div class="wps-text-field-helper-line">
								<div class="wps-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo esc_attr( $sfw_component['description'] ); ?></div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'textarea':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label class="wps-form-label" for="<?php echo esc_attr( $sfw_component['id'] ); ?>"><?php echo esc_attr( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<label class="wps-text-field wps-text-field--outlined wps-text-field--textarea"  	for="text-field-hero-input">
								<span class="wps-notched-outline">
									<span class="wps-notched-outline__leading"></span>
									<span class="wps-notched-outline__notch">
										<span class="wps-floating-label"><?php echo esc_attr( $sfw_component['placeholder'] ); ?></span>
									</span>
									<span class="wps-notched-outline__trailing"></span>
								</span>
								<span class="wps-text-field__resizer">
									<textarea class="wps-text-field--textarea <?php echo esc_attr( $sfw_component['class'] ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo esc_attr( $wps_sfw_name ); ?>" id="<?php echo esc_attr( $sfw_component['id'] ); ?>" placeholder="<?php echo esc_attr( $sfw_component['placeholder'] ); ?>"><?php echo esc_textarea( $sfw_component['value'] ); // WPCS: XSS ok. ?></textarea>
								</span>
							</label>

						</div>
					</div>

						<?php
						break;

					case 'select':
					case 'multiselect':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label class="wps-form-label" for="<?php echo esc_attr( $sfw_component['id'] ); ?>"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<div class="wps-form-select">
								<select name="<?php echo esc_attr( $wps_sfw_name ); ?><?php echo ( 'multiselect' === $sfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo esc_attr( $sfw_component['class'] ); ?>" <?php echo 'multiselect' === $sfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
									<?php
									foreach ( $sfw_component['options'] as $sfw_key => $sfw_val ) {
										?>
										<option value="<?php echo esc_attr( $sfw_key ); ?>"
											<?php
											if( is_array( $sfw_component['value'] ) ) {
												selected( in_array( (string) $sfw_key, $sfw_component['value'], true ), true );
											} else {
												selected( $sfw_component['value'], (string) $sfw_key );
											}
											?>
											/>
											<?php echo esc_html( $sfw_val ); ?>
										</option>
										<?php
									}
									?>
								</select>
								<label class="mdl-textfield__label" for="octane"><?php echo esc_html( $sfw_component['description'] ); ?></label>
							</div>
						</div>
					</div>

						<?php
						break;

					case 'checkbox':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<div class="wps-form-field">
								<div class="wps-checkbox">
									<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>">
										<input 
										name="<?php echo esc_attr( $wps_sfw_name ); ?>"
										id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
										type="checkbox"
										class="wps-checkbox__native-control <?php echo esc_attr( isset( $sfw_component['class'] ) ? $sfw_component['class'] : '' ); ?>"
										value="<?php echo esc_attr( $sfw_component['value'] ); ?>"
										<?php if ( '1' === $sfw_component['checked'] ) {
											checked( $sfw_component['checked'], '1' );
										} ?>
										/>
										<?php echo esc_html( $sfw_component['description'] ); // WPCS: XSS ok. ?>
									</label>
								</div>
							</div>
						</div>
					</div>
						<?php
						break;
					case 'multicheck':
						?>
						<div class="wps-form-group">
							<div class="wps-form-group__label">
								<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<div class="wps-flex-col  <?php echo esc_attr( $sfw_component['style_type'] ); ?>">
									<?php
									$setting = (array) get_option( 'testimonial-moving-creator-role' );
									foreach( get_editable_roles() as $role_name => $role_info ){
										if( $role_name == "administrator"){
											 continue;
										}
										$checkd = "";
										if( in_array( $role_name, $setting ) ){
											 $checkd = " checked='checked' ";
										}
										?>
										<div class="wps-form-field">
											<div class="wps-radio">
											<label for="<?php echo esc_attr($role_name); ?>" class="wps-form-radio-label">
													<input
													name="testimonial-moving-creator-role[]"
													value="<?php echo esc_attr($role_name);?>"
													type="checkbox"
													class="wps-radio__native-control <?php echo esc_attr( $sfw_component['class'] ); ?>"
													id="<?php echo esc_attr( $role_name ); ?>"
													<?php echo $checkd;?>
													><?php echo  esc_attr($role_name);?>
											</label>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="wps-text-field-helper-line">
									<?php echo esc_html( $sfw_component['description'] ); ?>
								</div>
							</div>
						</div>
						<?php
						break;

					case 'radio':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="<?php echo esc_attr( $sfw_component['id'] ); ?>" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<div class="wps-flex-col  <?php echo esc_attr( $sfw_component['style_type'] ); ?>">
								<?php
								foreach ( $sfw_component['options'] as $sfw_radio_key => $sfw_radio_val ) {
									?>
									<div class="wps-form-field">
										<div class="wps-radio">
										<label for="<?php echo esc_attr( $sfw_radio_key ); ?>" class="wps-form-radio-label">
												<input
												name="<?php echo esc_attr( $wps_sfw_name ); ?>"
												value="<?php echo esc_attr( $sfw_radio_key ); ?>"
												type="radio"
												class="wps-radio__native-control <?php echo esc_attr( $sfw_component['class'] ); ?>"
												<?php checked( $sfw_radio_key, $sfw_component['value'] ); ?>
												id="<?php echo esc_attr( $sfw_radio_key ); ?>"
												><?php echo esc_html( $sfw_radio_val ); ?>
										</label>
										</div>
									</div>	
									<?php
								}
								?>
							</div>
							<div class="wps-text-field-helper-line">
								<?php echo esc_html( $sfw_component['description'] ); ?>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'radio-switch':
						?>

					<div class="wps-form-group">
						<div class="wps-form-group__label">
							<label for="" class="wps-form-label"><?php echo esc_html( $sfw_component['title'] ); ?></label>
						</div>
						<div class="wps-form-group__control">
							<div>
								<div class="wps-switch">
									<div class="wps-switch__track"></div>
									<div class="wps-switch__thumb-underlay">
										<div class="wps-switch__thumb"></div>
										<input name="<?php echo esc_attr( $wps_sfw_name ); ?>" type="checkbox" id="basic-switch" value="on" class="wps-switch__native-control" role="switch" aria-checked="
																<?php
																if ('on' == $sfw_component['value'] ) {
																	echo 'true';
																} else {
																	echo 'false';
																}
																?>
										"
										<?php checked( $sfw_component['value'], 'on' ); ?>
										>
									</div>
								</div>
							</div>
						</div>
					</div>
						<?php
						break;

					case 'button':
						?>
					<div class="wps-form-group">
						<div class="wps-form-group__label"></div>
						<div class="wps-form-group__control">
							<button class="wps-button wps-button--raised <?php echo esc_attr( $sfw_component['class'] ); ?>" name="<?php echo esc_attr( $wps_sfw_name ); ?>"
								id="<?php echo esc_attr( $sfw_component['id'] ); ?>"> <span class="wps-button__ripple"></span>
								<span class="wps-button__label"><?php echo esc_attr( $sfw_component['button_text'] ); ?></span>
							</button>
						</div>
					</div>

						<?php
						break;

					case 'submit':
						?>
					<tr valign="top">
						<td scope="row">
							<input type="submit" class="button button-primary" 
							name="<?php echo esc_attr( $wps_sfw_name ); ?>"
							id="<?php echo esc_attr( $sfw_component['id'] ); ?>"
							value="<?php echo esc_attr( $sfw_component['button_text'] ); ?>"
							/>
						</td>
					</tr>
						<?php
						break;

					default:
						break;
				}
			}
		}
	}

}