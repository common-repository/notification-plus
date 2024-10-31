<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-email-collector-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?>"
     style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-email-collector-content">
        <p class="notification-plus-email-collector-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>
        <p class="notification-plus-email-collector-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->description ?></p>


        <form id="notification-plus-email-collector-form" class="notification-plus-email-collector-form" name="" action="" method="GET" role="form">
            <div class="notification-plus-email-collector-row">
                <input type="email" class="notification-plus-email-collector-email-placeholder" name="email" placeholder="<?php echo $notification->email_placeholder ?>" required="required"/>

                <button type="submit" class="notification-plus-email-collector-button" name="button" style="color: <?php echo $notification->button_color ?>; background: <?php echo $notification->button_background_color ?>"><?php echo $notification->button_text ?></button>
            </div>


            <?php notification_plus_agreement($notification); ?>
        </form>

		<?php notification_plus_branding( $notification ); ?>
    </div>

	<?php notification_plus_close( $notification ); ?>
</div>
