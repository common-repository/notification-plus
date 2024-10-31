<?php
/**
 * Plugin Name: Prince Settings
 * Plugin URI:  https://github.com/princeahmed/prince/
 * Description: Settings UI Builder for WordPress. A simple way to create & save Settings and Meta Boxes for free or premium themes.
 * Version:     1.0.0
 * Author:      Prince Ahmed
 * Author URI:  http://princeahmed.com
 * License:     GPLv3
 * Text Domain: prince-text-doamin
 */


/**
 * This is the Settings loader class.
 *
 * @package   Prince
 * @author    Prince Ahmed <israilahmed5@gmail.com>
 * @copyright Copyright (c) 2019, Prince Ahmed
 */

if ( ! class_exists( 'Prince_Settings_Loader' ) ) {

	class Prince_Settings_Loader {

		/**
		 * PHP5 constructor method.
		 *
		 * This method loads other methods of the class.
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'load_prince_settings' ), 1 );
		}

		/**
		 * Load Prince on the 'after_setup_theme' action. Then filters will
		 * be availble to the theme, and not only when in Theme Mode.
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0.2
		 */
		public function load_prince_settings() {
			/* setup the constants */
			$this->constants();

			/* include the required admin files */
			$this->admin_includes();

			/* include the required frontend files*/
			$this->includes();

			/* hook into WordPress */
			$this->hooks();
		}

		/**
		 * Constants
		 *
		 * Defines the constants for use within Prince. Constants
		 * are prefixed with 'OT_' to avoid any naming collisions.
		 *
		 * @return    void
		 *
		 * @access    private
		 * @since     1.0.0
		 */
		private function constants() {
			define( 'notification_plus_settings_assets_url', trailingslashit( plugin_dir_url( __FILE__ ) . '/assets' ) );
		}

		/**
		 * Include admin files
		 *
		 * These functions are included on admin pages only.
		 *
		 * @return    void
		 *
		 * @access    private
		 * @since     1.0.0
		 */
		private function admin_includes() {

			/* exit early if we're not on an admin page */
			if ( ! is_admin() ) {
				return;
			}

			/* global include files */
			$files = array(
				'admin-functions',
				'settings-types',
				'settings-api',
				'meta-box-api',
			);

			/* require the files */
			foreach ( $files as $file ) {
				include( __DIR__ . "/includes" . DIRECTORY_SEPARATOR . "{$file}.php" );
			}

			/* Registers the Theme Option page */
			add_action( 'init', 'prince_register_settings_page' );

		}

		/* include frontend files */
		public function includes() {
			include( __DIR__ . "/includes" . DIRECTORY_SEPARATOR . "frontend-functions.php" );
		}

		/**
		 * Execute the WordPress Hooks
		 *
		 * @return    void
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		private function hooks() {

			/* add scripts for metaboxes to post-new.php & post.php */
			add_action( 'admin_print_scripts-post-new.php', 'prince_admin_scripts', 11 );
			add_action( 'admin_print_scripts-post.php', 'prince_admin_scripts', 11 );

			/* add styles for metaboxes to post-new.php & post.php */
			add_action( 'admin_print_styles-post-new.php', 'prince_admin_styles', 11 );
			add_action( 'admin_print_styles-post.php', 'prince_admin_styles', 11 );


			/* Adds the Theme Option page to the admin bar */ //add_action( 'admin_bar_menu', 'prince_register_settings_admin_bar_menu', 10 );

			/* prepares the after save do_action */
			add_action( 'admin_init', 'prince_after_settings_save', 1 );

			/* default settings */
			add_action( 'admin_init', 'prince_default_settings', 2 );

			/* save settings */
			add_action( 'admin_init', 'prince_save_settings', 6 );

			/* create media post */
			add_action( 'admin_init', 'prince_create_media_post', 8 );

			/* Google Fonts front-end CSS */
			add_action( 'wp_enqueue_scripts', 'prince_load_google_fonts_css', 11 );

			/* dynamic front-end CSS */
			add_action( 'wp_enqueue_scripts', 'prince_load_dynamic_css', 999 );

			/* insert theme CSS dynamically */
			add_action( 'prince_after_settings_save', 'prince_save_css' );

			/* AJAX call to create a new contextual help */ //add_action( 'wp_ajax_add_the_contextual_help', array( $this, 'add_the_contextual_help' ) );

			/* AJAX call to create a new choice */
			add_action( 'wp_ajax_add_choice', array( $this, 'add_choice' ) );

			/* AJAX call to create a new list item setting */
			add_action( 'wp_ajax_add_list_item_setting', array( $this, 'add_list_item_setting' ) );


			/* AJAX call to create a new list item */
			add_action( 'wp_ajax_add_list_item', array( $this, 'add_list_item' ) );

			/* AJAX call to create a new social link */
			add_action( 'wp_ajax_add_social_links', array( $this, 'add_social_links' ) );

			/* AJAX call to retrieve Google Font data */
			add_action( 'wp_ajax_prince_google_font', array( $this, 'retrieve_google_font' ) );

			// Adds the temporary hacktastic shortcode
			add_filter( 'media_view_settings', array( $this, 'shortcode' ), 10, 2 );

			// AJAX update
			add_action( 'wp_ajax_gallery_update', array( $this, 'ajax_gallery_update' ) );
			add_action( 'wp_ajax_playlist_update', array( $this, 'ajax_playlist_update' ) );

			/* Modify the media uploader button */
			add_filter( 'gettext', array( $this, 'change_image_button' ), 10, 3 );

		}

		/**
		 * AJAX utility function for adding a new list item setting.
		 */
		public function add_list_item_setting() {
			echo prince_settings_view( esc_attr( $_REQUEST['name'] ) . '[settings]', esc_attr( $_REQUEST['count'] ) );
			die();
		}

		/**
		 * AJAX utility function for adding new contextual help content.
		 */
		public function add_the_contextual_help() {
			echo prince_contextual_help_view( esc_attr( $_REQUEST['name'] ), esc_attr( $_REQUEST['count'] ) );
			die();
		}

		/**
		 * AJAX utility function for adding a new choice.
		 */
		public function add_choice() {
			echo prince_choices_view( esc_attr( $_REQUEST['name'] ), esc_attr( $_REQUEST['count'] ) );
			die();
		}

		/**
		 * AJAX utility function for adding a new list item.
		 */
		public function add_list_item() {
			check_ajax_referer( 'prince', 'nonce' );
			prince_list_item_view( esc_attr( $_REQUEST['name'] ), esc_attr( $_REQUEST['count'] ), array(), esc_attr( $_REQUEST['post_id'] ), esc_attr( $_REQUEST['get_option'] ), unserialize( prince_decode( esc_attr( $_REQUEST['settings'] ) ) ), esc_attr( $_REQUEST['type'] ) );
			die();
		}

		/**
		 * AJAX utility function for adding a new social link.
		 */
		public function add_social_links() {
			check_ajax_referer( 'prince', 'nonce' );
			prince_social_links_view( esc_attr( $_REQUEST['name'] ), esc_attr( $_REQUEST['count'] ), array(), esc_attr( $_REQUEST['post_id'] ), esc_attr( $_REQUEST['get_option'] ), unserialize( prince_decode( esc_attr( $_REQUEST['settings'] ) ) ), esc_attr( $_REQUEST['type'] ) );
			die();
		}

		/**
		 * Fake the gallery shortcode
		 *
		 * The JS takes over and creates the actual shortcode with
		 * the real attachment IDs on the fly. Here we just need to
		 * pass in the post ID to get the ball rolling.
		 *
		 * @param array     The current settings
		 * @param object    The post object
		 *
		 * @return    array
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function shortcode( $settings, $post ) {
			global $pagenow;

			if ( in_array( $pagenow, array( 'upload.php', 'customize.php' ) ) ) {
				return $settings;
			}

			// Set the Prince post ID
			if ( ! is_object( $post ) ) {
				$post_id = isset( $_GET['post'] ) ? sanitize_text_field( $_GET['post'] ) : ( isset( $_GET['post_ID'] ) ? sanitize_text_field( $_GET['post_ID'] ) : 0 );
				if ( $post_id == 0 && function_exists( 'prince_get_media_post_ID' ) ) {
					$post_id = prince_get_media_post_ID();
				}
				$settings['post']['id'] = $post_id;
			}

			// No ID return settings
			if ( $settings['post']['id'] == 0 ) {
				return $settings;
			}

			// Set the fake shortcode
			$settings['prince_gallery']  = array( 'shortcode' => "[gallery id='{$settings['post']['id']}']" );
			$settings['prince_playlist'] = array( 'shortcode' => "[playlist id='{$settings['post']['id']}']" );

			// Return settings
			return $settings;

		}

		/**
		 * Returns the AJAX images
		 *
		 * @return    string
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function ajax_gallery_update() {

			if ( ! empty( $_POST['ids'] ) ) {

				$return = '';

				foreach ( $_POST['ids'] as $id ) {

					$thumbnail = wp_get_attachment_image_src( intval( $id ), 'thumbnail' );

					$return .= '<li><img  src="' . $thumbnail[0] . '" width="75" height="75" /></li>';

				}

				echo $return;
				exit();

			}

		}


		public function ajax_playlist_update() {

			if ( ! empty( $_POST['ids'] ) ) {

				$return = '';

				foreach ( $_POST['ids'] as $id ) { ?>
                    <li class="attachment">
                        <div class="attachment-preview">
                            <div class="thumbnail">
                                <div class="centered">
                                    <img src="<?php echo includes_url( 'images/media/audio.png' ); ?>" class="icon" draggable="false" alt="">
                                </div>
                                <div class="filename">
                                    <div>
										<?php
										$file = get_attached_file( $id );
										echo esc_html( wp_basename( $file ) );
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
				<?php }

				echo $return;
				exit();

			}

		}

		/**
		 * Returns a JSON encoded Google fonts array.
		 *
		 * @return    array
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function retrieve_google_font() {

			if ( isset( $_POST['field_id'], $_POST['family'] ) ) {

				prince_fetch_google_fonts();

				echo json_encode( array(
					'variants' => prince_recognized_google_font_variants( esc_attr( $_POST['field_id'] ), esc_attr( $_POST['family'] ) ),
					'subsets'  => prince_recognized_google_font_subsets( esc_attr( $_POST['field_id'] ), esc_attr( $_POST['family'] ) )
				) );

				exit();

			}

		}

		/**
		 * Filters the media uploader button.
		 *
		 * @return    string
		 *
		 * @access    public
		 * @since     1.0.0
		 */
		public function change_image_button( $translation, $text, $domain ) {
			global $pagenow;

			if ( $pagenow == apply_filters( 'prince_settings_parent_slug', 'themes.php' ) && 'default' == $domain && 'Insert into post' == $text ) {

				// Once is enough.
				remove_filter( 'gettext', array( $this, 'prince_change_image_button' ) );

				return apply_filters( 'prince_upload_text', __( 'Done', 'notification-plus' ) );

			}

			return $translation;

		}

	}

	/**
	 * Instantiate the Prince loader class.
	 *
	 * @since     1.0.0
	 */

	$settings_loader = new Prince_Settings_Loader();

}
