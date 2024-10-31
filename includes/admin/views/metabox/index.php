<?php

defined( 'ABSPATH' ) || exit;

global $post_id;

$current_type = get_post_meta( $post_id, '_notification_type', true );

if ( ! $current_type ) {
	$current_type = array_key_first( Notification_Plus_Notifications::get_config() );
}

include NOTIFICATION_PLUS_INCLUDES . '/admin/views/metabox/preview.php';

include NOTIFICATION_PLUS_INCLUDES . '/admin/views/metabox/menu.php';

include NOTIFICATION_PLUS_INCLUDES . '/admin/views/metabox/tabs.php';

?>
<div class="notification-plus-next-prev">
    <a href="#" class="notification-plus-prev"><i class="fa fa-angle-left"></i> <?php _e( 'Previous', 'notification-plus' ); ?>
    </a> <a href="#" class="notification-plus-next"><?php _e( 'Next', 'notification-plus' ); ?>
        <i class="fa fa-angle-right"></i></a>
</div>

<div class="notification-plus-ajax-loader">
    <img src="<?php echo site_url( 'wp-includes/images/wpspin-2x.gif' ); ?>">
</div>
