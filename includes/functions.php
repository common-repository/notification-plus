<?php

defined( 'ABSPATH' ) || exit;

function notification_plus_locations() {

	return array(
		'is_front_page' => __( 'Front Page', 'notification-plus' ),
		'is_home'       => __( 'Blog Page', 'notification-plus' ),
		'is_singular'   => __( 'All Posts, Pages and Custom Post Types', 'notification-plus' ),
		'is_single'     => __( 'All Posts', 'notification-plus' ),
		'is_page'       => __( 'All Pages', 'notification-plus' ),
		'is_attachment' => __( 'All Attachments', 'notification-plus' ),
		'is_search'     => __( 'Search Results', 'notification-plus' ),
		'is_404'        => __( '404 Error Page', 'notification-plus' ),
		'is_archive'    => __( 'All Archives', 'notification-plus' ),
		'is_category'   => __( 'All Category Archives', 'notification-plus' ),
		'is_tag'        => __( 'All Tag Archives', 'notification-plus' ),
		'is_custom'     => __( 'Custom Post or Page IDs', 'notification-plus' ),
	);
}

function notification_plus_get_options( $key, $default = null ) {
	$settings = get_option( 'notification_plus_options' );

	return ! empty( $settings[ $key ] ) ? $settings[ $key ] : $default;
}

function notification_plus_get_timeago( $date ) {

	$estimate_time = time() - ( new \DateTime( $date ) )->getTimestamp();

	if ( $estimate_time < 1 ) {
		return 'now';
	}

	$condition = [
		12 * 30 * 24 * 60 * 60 => 'year',
		30 * 24 * 60 * 60      => 'month',
		24 * 60 * 60           => 'day',
		60 * 60                => 'hour',
		60                     => 'minute',
		1                      => 'second'
	];

	foreach ( $condition as $secs => $str ) {
		$d = $estimate_time / $secs;

		if ( $d >= 1 ) {
			$r = round( $d );

			/* Determine the language string needed */
			$language_string_time = $r > 1 ? $str . 's' : $str;

			return $r . ' ' . $language_string_time . ' ago';
		}
	}
}

function notification_plus_get_time($time){
	$from = strtotime($time);

	if((time() - $from) < 1){
	    return 'now';
    }

	return human_time_diff($from, current_time('timestamp')).' ago';
}

function notification_plus_branding( $notification ) {
	printf( '<a href="%1$s" class="notification-plus-site %2$s">%3$s</a>', $notification->branding_url, $notification->display_branding ? '' : 'hidden', $notification->branding_name );
}

function notification_plus_agreement( $notification ) { ?>
    <div class="notification-plus-agreement-checkbox <?php echo $notification->show_agreement ? '' : 'hidden'; ?>">
        <input type="checkbox" name="agreement" <?php echo $notification->show_agreement ? 'required="required"' : ''; ?> />
        <label class="notification-plus-agreement-checkbox-text" style="color: <?php echo $notification->description_color ?>">
            <a href="<?php echo $notification->agreement_url ?>">
				<?php echo $notification->agreement_text ?>
            </a> </label>
    </div>
	<?php
}

function notification_plus_close( $notification ) {
	printf( '    <span class="notification-plus-close">%s</span>', $notification->display_close_button ? '&#10006;' : '' );
}

/**
 * Get woocommerce orders
 *
 * @param $notification
 *
 * @return array|bool
 */
function notification_plus_get_woo_orders( $notification ) {
	global $wpdb;

	$limit = $notification->conversions_count;

	$table_posts    = $wpdb->prefix . "posts";
	$table_items    = $wpdb->prefix . "woocommerce_order_items";
	$table_itemmeta = $wpdb->prefix . "woocommerce_order_itemmeta";

	$statuses = array_keys( wc_get_order_statuses() );
	$statuses = implode( "','", $statuses );


	if ( 'all' == $notification->woo_product_type ) {
		return $wpdb->get_col( "
        SELECT ID FROM {$wpdb->prefix}posts
        WHERE post_type LIKE 'shop_order'
        AND post_status IN ('$statuses')
        LIMIT 0, $limit
    " );
	} elseif ( 'product' == $notification->woo_product_type ) {
		if ( ! empty( $notification->woo_product ) ) {
			$sql = "
                    SELECT $table_items.order_id
                    FROM $table_itemmeta, $table_items, $table_posts
                    WHERE $table_items.order_item_id = $table_itemmeta.order_item_id
                    AND $table_items.order_id = $table_posts.ID
                    AND $table_posts.post_status IN ( '$statuses' )
                    AND $table_itemmeta.meta_key LIKE '_product_id'
                    AND $table_itemmeta.meta_value LIKE '$notification->woo_product'
                    ORDER BY $table_items.order_item_id DESC
                    LIMIT 0, $limit
                    ";

			return $wpdb->get_col( $sql );

		}
	}

	return false;
}

function notification_plus_get_edd_sales( $notification ) {

	if ( ! class_exists( 'EDD_Payments_Query' ) ) {
		return false;
	}

	$limit = $notification->conversions_count;


	$args = array(
		'output'  => 'payments',
		'number'  => $limit,
		'orderby' => 'ID',
		'order'   => 'DESC',
		'status'  => 'any',
		//'start_date' => isset( $_GET['start-date'] ) ? sanitize_text_field( $_GET['start-date'] ) : null,
	);

	if ( 'product' == $notification->edd_product_type ) {
		$args['download'] = $notification->edd_product;
	}

	$p_query = new EDD_Payments_Query( $args );

	return $p_query->get_payments();

}

