<?php

defined( 'ABSPATH' ) || exit();

class Notification_Plus_Admin_Ajax {

	/**
	 * Notification_Plus_Admin_Ajax constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_notification_plus_update_fields', [ $this, 'update_fields' ] );
		add_action( 'wp_ajax_notification_plus_n_status', [ $this, 'handle_status_change' ] );
		add_action( 'wp_ajax_notification_plus_preview', [ $this, 'notification_preview' ] );
		add_action( 'wp_ajax_notification_plus_get_statistics', [ $this, 'get_statistics' ] );
	}

	/**
	 * Update notification content fields based on notification type select
	 */
	public function update_fields() {

		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) {
			wp_send_json_error( [ 'msg' => __( 'No Cheating!', 'notification-plus' ) ] );
		}

		if ( empty( $current_type = wp_unslash( $_REQUEST['type'] ) ) ) {
			wp_send_json_error( [ 'msg' => __( 'Type is not set.', 'notification-plus' ) ] );
		}

		$post_id = ! empty( $_REQUEST['post_id'] ) ? intval( $_REQUEST['post_id'] ) : '';
		$tabs    = Notification_Plus_Notifications::setting_tabs( $current_type );

		//render menus
		ob_start();
		$menus = array_keys( $tabs );
		include_once NOTIFICATION_PLUS_INCLUDES . '/admin/views/metabox/menu.php';
		$menu = ob_get_clean();

		//render tab content
		ob_start();
		foreach ( $tabs as $key => $fields ) { ?>
            <div class="notification-plus-tab-content-item flex-column" id="<?php echo $key; ?>">
				<?php

				foreach ( $fields as $field ) {
					echo Notification_Plus_Notifications::settings_fields( $current_type, $field, $post_id );
				}

				?>
            </div>
		<?php }
		$content = ob_get_clean();

		wp_send_json_success( [
			'menu'    => $menu,
			'content' => $content,
		] );

	}

	/**
	 * Handle notification post_status change
	 */
	public function handle_status_change() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$post_id = ! empty( $_REQUEST['post_id'] ) ? intval( $_REQUEST['post_id'] ) : '';
		$status  = ! empty( $_REQUEST['status'] ) ? wp_unslash( $_REQUEST['status'] ) : 'draft';

		$args = [
			'ID'          => $post_id,
			'post_status' => $status
		];

		wp_update_post( $args );

		wp_send_json_success();

	}


	/**
	 * Get notification saved statistics data
	 */
	public function get_statistics() {

		include NOTIFICATION_PLUS_INCLUDES . '/class-statistics.php';

		$nid        = ! empty( $_REQUEST['nid'] ) ? intval( $_REQUEST['nid'] ) : '';
		$start_date = ! empty( $_REQUEST['start_date'] ) ? wp_unslash( $_REQUEST['start_date'] ) : '';
		$end_date   = ! empty( $_REQUEST['end_date'] ) ? wp_unslash( $_REQUEST['end_date'] ) : '';

		$args = [
			'nid'        => $nid,
			'start_date' => $start_date,
			'end_date'   => $end_date,
		];

		$statistics = new Notification_Plus_Statistics( $nid, $args );

		ob_start();
		$statistics->summary();
		$summary_html = ob_get_clean();

		ob_start();
		$statistics->chart();
		$chart_html = ob_get_clean();


		ob_start();
		$statistics->render_tables();
		$tables_html = ob_get_clean();

		wp_send_json_success( [
			'summary_html' => $summary_html,
			'chart_html'   => $chart_html,
			'tables_html'  => $tables_html,
		] );

	}

	/**
	 * Get notification preview
	 */
	public function notification_preview() {
		$post_id   = ! empty( $_REQUEST['post_id'] ) ? intval( $_REQUEST['post_id'] ) : '';
		$type      = get_post_meta( $post_id, '_notification_type', true );
		$type_name = Notification_Plus_Notifications::get_config( $type )['name'];


		ob_start();
		Notification_Plus_Notifications::preview( $type, $post_id );
		$html = ob_get_clean();

		wp_send_json_success( [
			'type' => $type_name,
			'html' => $html
		] );

	}

}

new Notification_Plus_Admin_Ajax();