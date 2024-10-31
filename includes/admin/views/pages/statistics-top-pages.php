<?php defined( 'ABSPATH' ) || exit; ?>

<div class="statistics-table-wrap <?php echo ! empty( $this->get_top_pages() ) ? '' : 'hidden'; ?>">

	<div class="statistics-table-header">
		<h3 class="statistics-table-title"><?php _e( 'Top Pages', 'notification-plus' ) ?></h3>
		<p class="statistics-table-description description"><?php _e( 'Most active pages on which notifications had most activity.', 'notification-plus' ) ?></p>
	</div>

	<table class="widefat statistics-table" id="top-pages-table">
		<thead>
		<tr>
			<th>#</th>
			<th><?php _e( 'URL', 'notification-plus' ) ?></th>
			<th><?php _e( 'Type', 'notification-plus' ) ?></th>
			<th><?php _e( 'Uniques', 'notification-plus' ) ?></th>
			<th><?php _e( 'Sessions', 'notification-plus' ) ?></th>
		</tr>
		</thead>

		<tbody>

		<?php

		if ( ! empty( $this->get_top_pages() ) ) {
			$i = 1;
			foreach ( $this->get_top_pages() as $item ) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><a href="<?php echo $item->url; ?>" target="_blank"><?php echo $item->url; ?></a></td>
					<td><?php echo $this->type_title[ $item->type ]; ?></td>
					<td><?php echo $item->total_uniques; ?></td>
					<td><?php echo $item->total_sessions; ?></td>
				</tr>
				<?php
				$i ++;
			}
		}
		?>

		</tbody>

		<tfoot class="hidden">
		<tr>
			<td colspan="5">
				<div class="ph-item">

					<div class="ph-col-12">
						<div class="ph-row notification-plus-mb-50">
							<div class="ph-col-6 big"></div>
							<div class="ph-col-4 empty big"></div>
							<div class="ph-col-2 big"></div>
							<div class="ph-col-4"></div>
							<div class="ph-col-8 empty"></div>
							<div class="ph-col-6"></div>
							<div class="ph-col-6 empty"></div>
							<div class="ph-col-12"></div>
						</div>

						<div class="ph-row notification-plus-mb-50">
							<div class="ph-col-6 big"></div>
							<div class="ph-col-4 empty big"></div>
							<div class="ph-col-2 big"></div>
							<div class="ph-col-4"></div>
							<div class="ph-col-8 empty"></div>
							<div class="ph-col-6"></div>
							<div class="ph-col-6 empty"></div>
							<div class="ph-col-12"></div>
						</div>


						<div class="ph-row notification-plus-mb-50">
							<div class="ph-col-6 big"></div>
							<div class="ph-col-4 empty big"></div>
							<div class="ph-col-2 big"></div>
							<div class="ph-col-4"></div>
							<div class="ph-col-8 empty"></div>
							<div class="ph-col-6"></div>
							<div class="ph-col-6 empty"></div>
							<div class="ph-col-12"></div>
						</div>


						<div class="ph-row notification-plus-mb-50">
							<div class="ph-col-6 big"></div>
							<div class="ph-col-4 empty big"></div>
							<div class="ph-col-2 big"></div>
							<div class="ph-col-4"></div>
							<div class="ph-col-8 empty"></div>
							<div class="ph-col-6"></div>
							<div class="ph-col-6 empty"></div>
							<div class="ph-col-12"></div>
						</div>

					</div>

				</div>
			</td>
		</tr>
		</tfoot>

	</table>

</div>
