<?php

defined( 'ABSPATH' ) || exit;

global $post;

$menu_map = [

	'source' => [
		'icon'  => 'fa-database',
		'title' => __( 'Source', 'notification-plus' ),
	],

	'theme' => [
		'icon'  => 'fa-file',
		'title' => __( 'Theme', 'notification-plus' ),
	],

	'content' => [
		'icon'  => 'fa-edit',
		'title' => __( 'Content', 'notification-plus' ),
	],

	'display' => [
		'icon'  => 'fa-desktop',
		'title' => __( 'Display', 'notification-plus' ),
	],

	'customize' => [
		'icon'  => 'fa-tools',
		'title' => __( 'Customize', 'notification-plus' ),
	],
	'sound'     => [
		'icon'  => 'fa-bell',
		'title' => __( 'Sound', 'notification-plus' ),
	],
];

if ( ! isset( $menus ) ) {
	$tabs  = Notification_Plus_Notifications::setting_tabs( $current_type );
	$menus = array_keys( $tabs );
}

?>

<ul class="notification-plus-meta-tabs">

    <li class="notification-plus-tab-item">
        <a class="notification-plus-tab-link active" data-target="notification_type">
            <i class="fa fa-network-wired"></i> <?php _e( 'Type', 'notification-plus' ) ?> </a>
    </li>

	<?php foreach ( $menus as $menu ) { ?>
        <li class="notification-plus-tab-item">
            <a href="#" class="notification-plus-tab-link" data-target="<?php echo $menu; ?>">
                <i class="fa <?php echo $menu_map[ $menu ]['icon']; ?>"></i>
				<?php echo $menu_map[ $menu ]['title']; ?>
            </a>
        </li>
	<?php } ?>

</ul>