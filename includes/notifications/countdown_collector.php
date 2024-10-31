<?php defined( 'ABSPATH' ) || die(); ?>


<?php $date_ended = new \DateTime( $notification->end_date ) < new \DateTime(); ?>
<?php $date = ( new \DateTime( $notification->end_date ) )->diff( new \DateTime() ); ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-countdown-collector-wrapper"
     style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-countdown-collector-content">
        <p class="notification-plus-countdown-collector-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>
        <p class="notification-plus-countdown-collector-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->description ?></p>

        <p class="notification-plus-countdown-collector-content-title" style="color: <?php echo $notification->content_title_color ?>"><?php echo $notification->content_title ?></p>

        <div class="notification-plus-countdown-collector-timer">
            <input type="hidden" name="end_date" value="<?php echo ( new \DateTime( $notification->end_date ) )->getTimestamp() ?>"/>

            <div class="notification-plus-countdown-collector-timer-block">
                <div class="notification-plus-countdown-collector-time" <?php printf('style="color:%1$s; background-color:%2$s"', $notification->time_color, $notification->time_background_color); ?> data-type="days"><?php echo $date_ended ? '0' : $date->format( '%a' ) ?></div>
                <p class="notification-plus-countdown-collector-time-text"><?php _e( 'days', 'notification-plus' ) ?></p>
            </div>

            <div class="notification-plus-countdown-collector-timer-block">
                <div class="notification-plus-countdown-collector-time" <?php printf('style="color:%1$s; background-color:%2$s"', $notification->time_color, $notification->time_background_color); ?> data-type="hours"><?php echo $date_ended ? '0' : $date->format( '%h' ) ?></div>
                <p class="notification-plus-countdown-collector-time-text"><?php _e( 'hours', 'notification-plus' ) ?></p>
            </div>

            <div class="notification-plus-countdown-collector-timer-block">
                <div class="notification-plus-countdown-collector-time" <?php printf('style="color:%1$s; background-color:%2$s"', $notification->time_color, $notification->time_background_color); ?> data-type="minutes"><?php echo $date_ended ? '0' : $date->format( '%i' ) ?></div>
                <p class="notification-plus-countdown-collector-time-text"><?php _e( 'minutes', 'notification-plus' ) ?></p>
            </div>

            <div class="notification-plus-countdown-collector-timer-block">
                <div class="notification-plus-countdown-collector-time" <?php printf('style="color:%1$s; background-color:%2$s"', $notification->time_color, $notification->time_background_color); ?> data-type="seconds"><?php echo $date_ended ? '0' : $date->format( '%s' ) ?></div>
                <p class="notification-plus-countdown-collector-time-text"><?php _e( 'seconds', 'notification-plus' ) ?></p>
            </div>
        </div>

        <div>
            <form class="notification-plus-countdown-collector-form" id="notification-plus-countdown-collector-form" name="" action="" method="GET" role="form">
                <div class="notification-plus-countdown-collector-row">
                    <input type="text" class="notification-plus-countdown-collector-input-placeholder" name="input" placeholder="<?php echo $notification->input_placeholder ?>"/>

                    <button type="submit" class="notification-plus-countdown-collector-button" name="button" style="color: <?php echo $notification->button_color ?>; background: <?php echo $notification->button_background_color ?>"><?php echo $notification->button_text ?></button>
                </div>

				<?php notification_plus_agreement( $notification ); ?>

            </form>

			<?php notification_plus_branding( $notification ); ?>
        </div>
    </div>

	<?php notification_plus_close( $notification ); ?>
</div>
