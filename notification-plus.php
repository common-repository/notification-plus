<?php

/**
 * Plugin Name: Notification Plus
 * Plugin URI:  https://princeboss.com
 * Description: Notification Plus is a fully loaded toolkit with marketing tools and pop-ups to create FOMO, Social Proof notifications to Engage and keep your website visitors.
 * Version:     1.0.1
 * Author:      Prince
 * Author URI:  http://github.com/princeahmed
 * Text Domain: notification-plus
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit();


/**
 * Main initiation class
 *
 * @since 1.0.0
 */
final class Notification_Plus {

	public $version = '1.0.1';

	public $min_php = '5.6.0';

	public $name = 'Notification Plus';

	protected static $instance = null;

	public function __construct() {

		if ( $this->check_environment() ) {
			$this->define_constants();
			$this->includes();
			$this->init_hooks();
			do_action( 'notification_plus_loaded' );
		}

	}

	public function check_environment() {

		$return = true;

		if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
			$return = false;

			$notice = sprintf( esc_html__( 'Unsupported PHP version Min required PHP Version: "%s"', 'notification-plus' ), $this->min_php );
		}

		if ( ! $return ) {
			// Add notice and deactivate the plugin
			add_action( 'admin_notices', function () use ( $notice ) { ?>
                <div class="notice is-dismissible notice-error">
                    <p><?php echo $notice; ?></p>
                </div>
			<?php } );

			if ( ! function_exists( 'deactivate_plugins' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		return $return;

	}

	public function define_constants() {
		define( 'NOTIFICATION_PLUS_VERSION', $this->version );
		define( 'NOTIFICATION_PLUS_FILE', __FILE__ );
		define( 'NOTIFICATION_PLUS_PATH', dirname( NOTIFICATION_PLUS_FILE ) );
		define( 'NOTIFICATION_PLUS_INCLUDES', NOTIFICATION_PLUS_PATH . '/includes' );
		define( 'NOTIFICATION_PLUS_URL', plugins_url( '', NOTIFICATION_PLUS_FILE ) );
		define( 'NOTIFICATION_PLUS_ASSETS', NOTIFICATION_PLUS_URL . '/assets' );
		define( 'NOTIFICATION_PLUS_TEMPLATES', NOTIFICATION_PLUS_PATH . '/templates' );
	}

	public function includes() {

		/* core includes */
		include_once NOTIFICATION_PLUS_INCLUDES . '/freemius.php';

		/* core includes */
		include_once NOTIFICATION_PLUS_INCLUDES . '/class-install.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/class-hooks.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/class-cpt.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/class-shortcodes.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/class-enqueue.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/class-ajax.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/class-notifications.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/functions.php';
		include_once NOTIFICATION_PLUS_INCLUDES . '/prince-settings/prince-loader.php';

		/* admin includes */
		if ( is_admin() ) {
			include_once NOTIFICATION_PLUS_INCLUDES . '/admin/class-admin.php';
		}

	}

	public function init_hooks() {

		/* Installation */
		register_activation_hook( NOTIFICATION_PLUS_FILE, [ 'Notification_Plus_Install', 'activate' ] );

		/* Localize our plugin */
		add_action( 'init', [ $this, 'localization_setup' ] );

		/* action_links */
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );

	}

	public function localization_setup() {
		load_plugin_textdomain( 'notification-plus', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function plugin_action_links( $links ) {
		//$links[] = '<a href="' . admin_url( 'edit.php?post_type=post_type&page=settings' ) . '">' . __( 'Settings', 'notification-plus' ) . '</a>';

		return $links;
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}

function notification_plus() {
	return Notification_Plus::instance();
}

notification_plus();
