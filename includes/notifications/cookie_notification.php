<?php defined( 'ABSPATH' ) || die(); ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-cookie-notification-wrapper"
     style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-cookie-notification-content">

        <div class="notification-plus-cookie-notification-header">
            <img src="<?php echo ! empty( $notification->image ) ? $notification->image : ''; ?>" class="notification-plus-cookie-notification-image <?php echo ! empty( $notification->image ) ? '' : 'hidden'; ?>"/>

            <div>
                <p class="notification-plus-cookie-notification-description" style="color: <?php echo $notification->description_color ?>">
					<?php echo $notification->description ?>
                </p>

                <span class="notification-plus-cookie-notification-url">
                    <a href="<?php echo $notification->link_url ?>"><?php echo $notification->link_url_text ?></a></span>
            </div>
        </div>

        <button type="button" class="notification-plus-cookie-notification-button" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>"><?php echo $notification->button_text ?></button>

		<?php notification_plus_branding( $notification ); ?>
    </div>

	<?php notification_plus_close( $notification ); ?>

</div>
