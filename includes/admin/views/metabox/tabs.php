<?php defined( 'ABSPATH' ) || exit; ?>

<div class="notification-plus-tab-content">

	<?php

	//type tab
	include NOTIFICATION_PLUS_INCLUDES . '/admin/views/metabox/type.php';

	$tabs = Notification_Plus_Notifications::setting_tabs( $current_type );

	foreach ( $tabs as $key => $fields ) { ?>
        <div class="notification-plus-tab-content-item flex-column" id="<?php echo $key; ?>">
			<?php

			foreach ( $fields as $field ) {
				echo Notification_Plus_Notifications::settings_fields( $current_type, $field, $post_id );
			}

			?>
        </div>
	<?php } ?>

</div>