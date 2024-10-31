<?php defined( 'ABSPATH' ) || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-video-wrapper" style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-video-content">
        <p class="notification-plus-video-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>

        <div class="notification-plus-video-video-container">
			<?php
			$video = '';
			if ( ! empty( $notification->video ) ) {
				parse_str( parse_url( $notification->video, PHP_URL_QUERY ), $url_parts );
				$video = ! empty( $url_parts['v'] ) ? $url_parts['v'] : '';
			}
			?>
            <iframe class="notification-plus-video-video-iframe" src="https://youtube.com/embed/<?php echo $video ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>

        <a href="<?php echo $notification->button_url ?>" class="notification-plus-video-button" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>"><?php echo $notification->button_text ?></a>

		<?php notification_plus_branding( $notification ); ?>
    </div>

	<?php notification_plus_close( $notification ); ?>
</div>
