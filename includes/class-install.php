<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class WP_Plugin_Boilerplate_Install
 * Do the activate stuffs
 */
class Notification_Plus_Install {

	/**
	 * Toto Activate
	 */
	public static function activate() {
		update_option( 'notification_plus_version', notification_plus()->version );
		update_option( 'notification_plus_flush_rewrite_rules', true );

		if ( empty( get_option( 'notification_plus_install_date' ) ) ) {
			update_option( 'notification_plus_install_date', current_time( 'timestamp' ) );
		}

		//Create Tables
		self::create_tables();
	}

	/**
	 * Creat tables
	 * @since 1.0.0
	 */
	public static function create_tables() {

		global $wpdb;
		$wpdb->hide_errors();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$tables = [
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}notification_plus_statistics(
         	id bigint(20) NOT NULL AUTO_INCREMENT,
         	unique_id varchar (32) NOT NULL DEFAULT '',
			notification_id bigint(20) NOT NULL,
			`count` bigint(20) NOT NULL DEFAULT '1',
			ip varchar(128)  NOT NULL DEFAULT '',
			`type` varchar(32)  NOT NULL DEFAULT '',
			url text  NOT NULL,
			`data` text NULL,
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			updated_at DATETIME NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY `unique_id` (`unique_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
		];

		foreach ( $tables as $table ) {
			dbDelta( $table );
		}
	}

}