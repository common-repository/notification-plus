<?php defined('ABSPATH') || die() ?>

<div class="notification-plus-wrapper notification-plus-wrapper-<?php echo $notification->border_radius ?> notification-plus-random-review-wrapper" style="background: <?php echo $notification->background_color ?>">
    <div class="notification-plus-random-review-content">

        <img src="<?php echo $notification->image ?>" class="notification-plus-random-review-image <?php echo ! empty( $notification->image ) ? '': 'hidden'; ?>" />

        <div>
            <div class="notification-plus-random-review-header">
                <p class="notification-plus-random-review-title" style="color: <?php echo $notification->title_color ?>"><?php echo $notification->title ?></p>

                <div class="notification-plus-random-review-stars">
                    <?php for($i = 1; $i <= $notification->stars; $i++): ?>
                    <div class="notification-plus-toast-star">★</div>
                    <?php endfor ?>
                </div>

            </div>
            <p class="notification-plus-random-review-description" style="color: <?php echo $notification->description_color ?>">"<?php echo $notification->description ?>"</p>

            <?php notification_plus_branding($notification); ?>

        </div>
    </div>

    <?php notification_plus_close($notification); ?>
</div>
