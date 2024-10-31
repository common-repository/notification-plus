<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-coupon-wrapper"
     style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-coupon-content">

        <img src="<?php echo $notification->image ?>" class="notification-plus-coupon-image <?php echo ! empty( $notification->image ) ? '' : 'hidden'; ?>"/>

        <div>
            <p class="notification-plus-coupon-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>
            <p class="notification-plus-coupon-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->description ?></p>

            <div class="notification-plus-coupon-coupon-code"><?php echo $notification->coupon_code ?></div>

            <a href="<?php echo $notification->button_url ?>" class="notification-plus-coupon-button" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>"><?php echo $notification->button_text ?></a>

            <div>
                <a href="#" class="notification-plus-coupon-footer"><?php echo $notification->footer_text ?></a>
            </div>


			<?php notification_plus_branding( $notification ); ?>
        </div>
    </div>

	<?php notification_plus_close( $notification ); ?>
</div>