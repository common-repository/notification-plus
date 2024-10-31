<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-emoji-feedback-wrapper" style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-emoji-feedback-content">
        <p class="notification-plus-emoji-feedback-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>

        <div class="notification-plus-emoji-feedback-emojis">
            <img src="<?php echo NOTIFICATION_PLUS_ASSETS . '/images/emojis/angry.svg'; ?>" class="notification-plus-emoji-feedback-emoji notification-plus-emoji-feedback-angry <?php echo $notification->show_angry ? '' : 'hidden'; ?>" data-type="angry" alt="<?php _e( 'Feedback Angry', 'notification-plus' ) ?>"/>


            <img src="<?php echo NOTIFICATION_PLUS_ASSETS . '/images/emojis/sad.svg'; ?>" class="notification-plus-emoji-feedback-emoji notification-plus-emoji-feedback-sad <?php echo $notification->show_sad ? '' : 'hidden'; ?>" data-type="sad" alt="<?php _e( 'Feedback Sad', 'notification-plus' ) ?>"/>


            <img src="<?php echo NOTIFICATION_PLUS_ASSETS . '/images/emojis/neutral.svg'; ?>" class="notification-plus-emoji-feedback-emoji notification-plus-emoji-feedback-neutral <?php echo $notification->show_neutral ? '' : 'hidden'; ?>" data-type="neutral" alt="<?php _e( 'Feedback Neutral', 'notification-plus' ) ?>"/>


            <img src="<?php echo NOTIFICATION_PLUS_ASSETS . '/images/emojis/happy.svg'; ?>" class="notification-plus-emoji-feedback-emoji notification-plus-emoji-feedback-happy <?php echo $notification->show_happy ? '' : 'hidden'; ?>" data-type="happy" alt="<?php _e( 'Feedback Happy', 'notification-plus' ) ?>"/>


            <img src="<?php echo NOTIFICATION_PLUS_ASSETS . '/images/emojis/excited.svg'; ?>" class="notification-plus-emoji-feedback-emoji notification-plus-emoji-feedback-excited <?php echo $notification->show_excited ? '' : 'hidden'; ?>" data-type="excited" alt="<?php _e( 'Feedback Excited', 'notification-plus' ) ?>"/>

        </div>

		<?php
		notification_plus_branding( $notification );
		?>
    </div>

	<?php notification_plus_close( $notification ); ?>
</div>
