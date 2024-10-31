<?php defined( 'ABSPATH' ) || exit; ?>

<div class="notification-plus-tab-content-item notification-plus-notification-types active" id="notification_type">
	<?php

	foreach ( Notification_Plus_Notifications::get_config() as $type => $config ) { ?>
        <div class="notification-plus-notification-type <?php echo $current_type == $type ? 'active' : ''; ?> <?php echo isset( $config['is_pro'] ) ? 'item-is-pro' : ''; ?>">

            <h3 class="notification-plus-notification-type-title"><?php echo $config['name']; ?></h3>

            <div class="notification-plus-notification-type-icon">
                <i class="<?php echo $config['icon']; ?>"></i>
            </div>

            <input type="radio" name="notification_type" value="<?php echo $type; ?>" required="required" <?php checked( $current_type, $type ); ?>>

            <div class="preview" style="display: none;">
				<?php Notification_Plus_Notifications::preview( $type, $post_id ); ?>
            </div>

        </div>
	<?php } ?>

</div>