<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-social-share-wrapper" style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-social-share-content">
        <p class="notification-plus-social-share-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>

        <div class="notification-plus-social-share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( $notification->share_url ) ?>&amp;src=sdkpreparse" target="_blank" class="notification-plus-social-share-button notification-plus-social-share-button-facebook <?php echo $notification->share_facebook ? '' : 'hidden'; ?>"><?php _e( 'Facebook', 'notification-plus' ) ?></a>

            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( $notification->share_url ) ?>" target="_blank" class="notification-plus-social-share-button notification-plus-social-share-button-twitter <?php echo $notification->share_twitter ? '' : 'hidden'; ?>"><?php _e( 'Twitter', 'notification-plus' ) ?></a>

            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode( $notification->share_url ) ?>" target="_blank" class="notification-plus-social-share-button notification-plus-social-share-button-linkedin <?php echo $notification->share_linkedin ? '' : 'hidden'; ?>"><?php _e( 'Linkedin', 'notification-plus' ) ?></a>

        </div>

        <p class="notification-plus-social-share-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->description ?></p>

		<?php notification_plus_branding( $notification ); ?>
    </div>

	<?php notification_plus_close( $notification ); ?>
</div>
