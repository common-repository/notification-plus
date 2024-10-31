<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-conversions-counter-wrapper"
     style="background: <?php echo $notification->background_color ?>">

    <div class="notification-plus-conversions-counter-content">

        <div class="notification-plus-conversions-counter-header">
            <div class="notification-plus-conversions-counter-number" style="background: <?php echo $notification->number_background_color ?>; color: <?php echo $notification->number_color ?>">
				<?php echo isset( $notification->count ) && $notification->count >= $notification->display_minimum_activity ? $notification->count : 5 ?>
            </div>

            <div>
                <p class="notification-plus-conversions-counter-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>
                <p class="notification-plus-conversions-counter-time"><?php echo sprintf( __( 'In the last %s hours', 'notification-plus' ), $notification->last_activity ) ?></p>
            </div>
        </div>

		<?php notification_plus_branding( $notification ); ?>
    </div>

	<?php notification_plus_close( $notification ); ?>
</div>