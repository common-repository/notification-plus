<?php

defined( 'ABSPATH' ) || exit();

if ( ! function_exists( 'np_fs' ) ) {

	// Create a helper function for easy SDK access.
	function np_fs() {
		global $np_fs;

		if ( ! isset( $np_fs ) ) {
			// Include Freemius SDK.
			require_once dirname(__FILE__) . '/freemius/start.php';

			$np_fs = fs_dynamic_init( array(
				'id'                  => '6250',
				'slug'                => 'notification-plus',
				'premium_slug'        => 'wp-radio-premium',
				'type'                => 'plugin',
				'public_key'          => 'pk_e15e5eabdfafa3695e7ab7695484d',
				'is_premium'          => false,
				'has_addons'          => false,
				'has_paid_plans'      => false,
				'menu'                => array(
					'slug'           => 'edit.php?post_type=notification_plus',
					'account'        => false,
				),
			) );
		}

		return $np_fs;
	}

	// Init Freemius.
	np_fs();
	// Signal that SDK was initiated.
	do_action( 'np_fs_loaded' );
}