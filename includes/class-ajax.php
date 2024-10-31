<?php

defined( 'ABSPATH' ) || exit();

class Notification_Plus_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_notification_plus_save_statistics', [ $this, 'save_statistics' ] );
		add_action( 'wp_ajax_nopriv_notification_plus_save_statistics', [ $this, 'save_statistics' ] );
	}

	public function save_statistics() {

		$posted = $_REQUEST['data'];

		$notification_id = ! empty( $posted['notification_id'] ) ? intval( $posted['notification_id'] ) : '';
		$ip              = ! empty( $posted['ip'] ) ? wp_unslash( $posted['ip'] ) : '';
		$type            = ! empty( $posted['type'] ) ? wp_unslash( $posted['type'] ) : '';
		$url             = ! empty( $posted['current_page'] ) ? esc_url( urldecode( $posted['current_page'] ) ) : '';
		$data            = ! empty( $posted['data'] ) ? wp_unslash( $posted['data'] ) : '';
		$date            = date( 'Y-m-d H:i:s' );

		$unique_id = md5( $notification_id . $ip . $type . $url . date( 'Y-m-d' ) );


		global $wpdb;
		$table = $wpdb->prefix . 'notification_plus_statistics';

		$sql = "INSERT INTO 
                        {$table} (`notification_id`,`unique_id`, `type`, `ip`, `url`,`data`, `created_at`, `updated_at`) 
                    VALUES 
                        (%d, %s, %s, %s, %s, %s, %s, %s)
                    ON DUPLICATE KEY UPDATE
                        `count` = `count` + 1,
                        `updated_at` = VALUES (updated_at)  
                ";

		$wpdb->query( $wpdb->prepare( $sql, [ $notification_id, $unique_id, $type, $ip, $url, $data, $date, $date ] ) );

		wp_send_json_success( [ 'msg' => 'success' ] );

	}

}

new Notification_Plus_Ajax();