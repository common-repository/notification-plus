<?php

defined( 'ABSPATH' ) || exit();

class Notification_Plus_CPT {

	/**
	 * Post_Types constructor.
	 */
	function __construct() {
		add_action( 'init', [ $this, 'register_post_types' ] );
		add_action( 'init', [ $this, 'flush_rewrite_rules' ], 99 );
	}

	/**
	 * register custom post types
	 *
	 * @since 1.0.0
	 */
	function register_post_types() {
		register_post_type( 'notification_plus', array(
			'labels'        => $this::get_posts_labels( __( 'Notification Plus', 'notification-plus' ), __( 'Notification', 'notification-plus' ), __( 'Notifications', 'notification-plus' ) ),
			'supports'      => [ 'title' ],
			'show_ui'      => true,
			'menu_position' => 5,
			'menu_icon'     => NOTIFICATION_PLUS_ASSETS.'/images/menu-icon.png',
		) );
	}


	/**
	 * Get all labels from post types
	 *
	 * @param $menu_name
	 * @param $singular
	 * @param $plural
	 *
	 * @return array
	 * @since 1.0.0
	 */
	protected static function get_posts_labels( $menu_name, $singular, $plural, $type = 'plural' ) {
		$labels = array(
			'name'               => 'plural' == $type ? $plural : $singular,
			'all_items'          => sprintf( __( "All %s", 'notification-plus' ), $plural ),
			'singular_name'      => $singular,
			'add_new'            => sprintf( __( 'Add New', 'notification-plus' ), $singular ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'notification-plus' ), $singular ),
			'edit_item'          => sprintf( __( 'Edit %s', 'notification-plus' ), $singular ),
			'new_item'           => sprintf( __( 'New %s', 'notification-plus' ), $singular ),
			'view_item'          => sprintf( __( 'View %s', 'notification-plus' ), $singular ),
			'search_items'       => sprintf( __( 'Search %s', 'notification-plus' ), $plural ),
			'not_found'          => sprintf( __( 'No %s found', 'notification-plus' ), $plural ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'notification-plus' ), $plural ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', 'notification-plus' ), $singular ),
			'menu_name'          => $menu_name,
		);

		return $labels;
	}

	/**
	 * Flash The Rewrite Rules
	 *
	 * @since 2.0.2
	 */
	function flush_rewrite_rules() {
		if ( get_option( 'notification_plus_flush_rewrite_rules' ) ) {
			flush_rewrite_rules();
			delete_option( 'notification_plus_flush_rewrite_rules' );
		}
	}
}

new Notification_Plus_CPT();