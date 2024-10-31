<?php defined( 'ABSPATH' ) || die(); ?>
<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-informational-wrapper" style="background: <?php echo $notification->background_color ?>">

    <div class="notification-plus-informational-content">

            <img src="<?php echo $notification->image ?>" class="notification-plus-informational-image <?php echo ! empty( $notification->image ) ? '': 'hidden'; ?>"/>

        <div>
            <p class="notification-plus-informational-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>
            <p class="notification-plus-informational-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->description ?></p>

			<?php notification_plus_branding( $notification ); ?>

        </div>
    </div>

    <?php notification_plus_close($notification); ?>
</div>
