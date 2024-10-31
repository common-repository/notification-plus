<?php defined( 'ABSPATH' ) || die() ?>


<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-score-feedback-wrapper" style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-score-feedback-content">
        <p class="notification-plus-score-feedback-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>

        <div class="notification-plus-score-feedback-scores">
            <button type="button" class="notification-plus-score-feedback-button" data-score="1" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>">1</button>
            <button type="button" class="notification-plus-score-feedback-button" data-score="2" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>">2</button>
            <button type="button" class="notification-plus-score-feedback-button" data-score="3" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>">3</button>
            <button type="button" class="notification-plus-score-feedback-button" data-score="4" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>">4</button>
            <button type="button" class="notification-plus-score-feedback-button" data-score="5" style="background: <?php echo $notification->button_background_color ?>;color: <?php echo $notification->button_color ?>">5</button>
        </div>

        <p class="notification-plus-score-feedback-description" style="color: <?php echo $notification->description_color ?>"><?php echo $notification->description ?></p>

		<?php
		notification_plus_branding( $notification );
		?>
    </div>

    <?php notification_plus_close($notification); ?>
</div>
