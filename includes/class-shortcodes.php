<?php

defined( 'ABSPATH' ) || exit();

class Notification_Plus_Shortcodes {

	/**
	 * Notification_Plus_Shortcodes constructor.
	 */
	public function __construct() {
		add_shortcode( 'notification_plus', [ $this, 'render_notification' ] );
	}

	/**
	 * @param $atts
	 *
	 * @return false|string|void
	 * @throws Exception
	 */
	public function render_notification( $atts ) {

		$id = intval( $atts['id'] );

		if ( ! $id ) {
			return;
		}

		ob_start();
		Notification_Plus_Notifications::get_view( $id, true );

		return ob_get_clean();

	}

}

new Notification_Plus_Shortcodes();