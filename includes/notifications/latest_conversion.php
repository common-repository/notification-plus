<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-latest-conversion-wrapper" style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-latest-conversion-content">

            <img src="<?php echo $notification->image ?>" class="notification-plus-latest-conversion-image <?php echo ! empty( $notification->image ) ? '': 'hidden'; ?>"/>

        <div>
            <p class="notification-plus-latest-conversion-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->who; ?></p>
            <p class="notification-plus-latest-conversion-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->text ?></p>

            <div class="notification-plus-latest-conversion-footer">
                <div class="notification-plus-latest-conversion-time"><?php echo  $notification->time; ?></div>

				<?php notification_plus_branding($notification); ?>

            </div>

        </div>
    </div>

    <?php notification_plus_close($notification); ?>
</div>
