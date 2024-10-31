<?php defined('ABSPATH') || exit; ?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Notification Statistics', 'notification-plus') ?></h1>

	<?php

	include_once NOTIFICATION_PLUS_INCLUDES . '/class-statistics.php';

	$statistics = new Notification_Plus_Statistics();

	$statistics->filter_bar();

	$statistics->summary();

	$statistics->chart();;

	$statistics->render_tables();

	?>

</div>