<?php

/* block direct access */
defined( 'ABSPATH' ) || exit;

class Notification_Plus_Metabox {

	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
	}

	public function register_meta_boxes() {
		add_meta_box( 'notification_plus_type', __( 'Notification Settings', 'notification-plus' ), [
			$this,
			'render_type_metabox'
		], 'notification_plus', 'normal', 'high' );
	}


	public function render_type_metabox() {
		include NOTIFICATION_PLUS_INCLUDES . '/admin/views/metabox/index.php';
	}


}

new Notification_Plus_Metabox();