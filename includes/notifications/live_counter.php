<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-live-counter-wrapper" style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-live-counter-content">

        <div class="notification-plus-live-counter-header">
            <div class="notification-plus-toast-pulse" style="background: <?php echo $notification->pulse_background_color ?>;"></div>

            <div class="notification-plus-live-counter-number" style="background: <?php echo $notification->number_background_color ?>; color: <?php echo $notification->number_color ?>">
				<?php echo $notification->number; ?>
            </div>
        </div>

        <p class="notification-plus-live-counter-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->description ?></p>

		<?php notification_plus_branding($notification); ?>

    </div>

    <?php notification_plus_close($notification); ?>
</div>
