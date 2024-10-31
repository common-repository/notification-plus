<?php

defined( 'ABSPATH' ) || exit();

class Notification_Plus_Enqueue {

	public $notification_plus_admin_screens = [
		'notification_plus_page_notification-statistics',
		'notification_plus_page_prince-options',
		'edit-notification_plus',
		'notification_plus',
	];

	/**
	 * Notification_Plus_Enqueue constructor.
	 */
	function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ], 101 );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_prince_scripts' ] );
	}

	/**
	 * Frontend scripts
	 */
	public function frontend_scripts() {
		wp_enqueue_style( 'notification-plus-frontend-css', NOTIFICATION_PLUS_ASSETS . '/css/frontend.css', [], NOTIFICATION_PLUS_VERSION );

		wp_enqueue_script( 'notification-plus-frontend-js', NOTIFICATION_PLUS_ASSETS . '/js/frontend.js', [
			'jquery',
			'wp-util'
		], NOTIFICATION_PLUS_VERSION, true );

	}

	/**
	 * Admin Scripts
	 *
	 * @param $hooks
	 */
	public function admin_scripts( $hooks ) {

		wp_enqueue_style( 'notification-plus-admin-css', NOTIFICATION_PLUS_ASSETS . '/css/admin.css', [], NOTIFICATION_PLUS_VERSION );
		//don't load any script if is not notification_plus admin screen
		if ( ! in_array( get_current_screen()->id, $this->notification_plus_admin_screens ) ) {
			return;
		}

		wp_dequeue_style( 'jquery-ui-css' );
		wp_deregister_style( 'jquery-ui-css' );

		wp_enqueue_style( 'fontawesome', NOTIFICATION_PLUS_ASSETS . '/vendor/fontawesome/css/all.min.css', false, '5.13.0' );

		wp_enqueue_style( 'select2-css', NOTIFICATION_PLUS_ASSETS . '/vendor/select2/select2.min.css', [], '4.0.6' );
		wp_enqueue_script( 'sweetalert', NOTIFICATION_PLUS_ASSETS . '/vendor/sweetalert.min.js', false, '', true );
		wp_enqueue_script( 'select2', NOTIFICATION_PLUS_ASSETS . '/vendor/select2/select2.min.js', false, '4.0.6', true );
		wp_enqueue_script( 'notification-plus-admin-js', NOTIFICATION_PLUS_ASSETS . '/js/admin.js', [
			'jquery',
			'wp-util',
		], NOTIFICATION_PLUS_VERSION, true );

		//load chart.js only on the statistics page
		if ( 'notification_plus_page_notification-statistics' == $hooks ) {
			wp_enqueue_script( 'chart', NOTIFICATION_PLUS_ASSETS . '/vendor/Chart.bundle.min.js', false, '2.8.0', false );
		}

		//load notifications styles on edit notification screen
		if ( in_array( get_current_screen()->id, [ 'notification_plus', 'edit-notification_plus' ] ) ) {

			wp_enqueue_style( 'notification-plus-notification-css', NOTIFICATION_PLUS_ASSETS . '/css/frontend.css', false, NOTIFICATION_PLUS_VERSION );
		}

		/* Create localized JS array */
		$localized_array = [
			'_wpnonce'              => wp_create_nonce(),
			'site_url'              => site_url(),
			'notification_plus_url' => plugins_url( '', NOTIFICATION_PLUS_FILE ),
			'i18n'                  => [
				'disabled'         => __( 'Disabled', 'notification-plus' ),
				'enabled'          => __( 'Enabled', 'notification-plus' ),
				'notification'     => __( 'Notification', 'notification-plus' ),
				'copied'           => __( 'Copied to Clipboard.', 'notification-plus' ),
				'select_locations' => __( 'Select Locations', 'notification-plus' ),
				'choose_image'     => __( 'Choose Image', 'notification-plus' ),
				'pro_msg'          => __( 'This type is only available on the PRO version', 'notification-plus' ),
			]
		];

		wp_localize_script( 'notification-plus-admin-js', 'notificationPlus', $localized_array );

	}

	/**
	 * load prince-settings scripts on the notification_plus screens
	 */
	public function load_prince_scripts() {

		if ( ! in_array( get_current_screen()->id, $this->notification_plus_admin_screens ) ) {
			return;
		}

		if ( function_exists( 'prince_admin_styles' ) ) {
			prince_admin_styles();
			prince_admin_scripts();
		}
	}

}

new Notification_Plus_Enqueue();