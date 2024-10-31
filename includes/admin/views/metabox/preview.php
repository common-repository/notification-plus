<?php defined( 'ABSPATH' ) || exit; ?>

<div id="notification_preview" class="notification-preview d-flex">

    <div class="notification-preview-title">
        <h4>
            <i class="fa fa-network-wired"></i>
            <span class="notification-preview-title-type"><?php echo Notification_Plus_Notifications::get_config()[ $current_type ]['name']; ?></span>
        </h4>

        <h5><i class="fa fa-eye mr-2"></i> <?php _e( 'Preview', 'notification-plus' ) ?></h5>
    </div>

    <div class="notification-preview-content">
		<?php
		global $post_id;
		Notification_Plus_Notifications::preview( $current_type, $post_id );
		?>
    </div>
</div>