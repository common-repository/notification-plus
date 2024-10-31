<?php
defined( 'ABSPATH' ) || exit;

$count = ! empty( $this->log_chart()[ $statistics_type . '_total' ] ) ? $this->log_chart()[ $statistics_type . '_total' ] : 0;
?>

<div class="statistics-summary">
	<div class="summary-icon">
		<img src="<?php echo NOTIFICATION_PLUS_ASSETS . '/images/statistics/' . $statistics_type . '.png' ?>" alt="<?php echo $this->type_title[ $statistics_type ]; ?>">
	</div>
	<div class="summary-info">
		<div class="summary-number"><?php echo $count; ?></div>
		<div class="summary-title"><?php echo $this->type_title[ $statistics_type ]; ?></div>
	</div>
</div>