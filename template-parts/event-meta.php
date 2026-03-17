<?php
/**
 * Template Part — Event Meta Details.
 *
 * Displayed on the single event page.
 * Called via get_template_part() from template-functions.php.
 * All values are pre-sanitised before passing.
 *
 * @package YorkAlumni
 * @var array $args {
 *   formatted_date: string,
 *   formatted_time: string,
 *   location:       string,
 *   available_seats: string,
 *   booking_status:  string,
 *   status_label:    string,
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$formatted_date  = isset( $args['formatted_date'] ) ? $args['formatted_date'] : '';
$formatted_time  = isset( $args['formatted_time'] ) ? $args['formatted_time'] : '';
$location        = isset( $args['location'] ) ? $args['location'] : '';
$available_seats = isset( $args['available_seats'] ) ? $args['available_seats'] : '';
$booking_status  = isset( $args['booking_status'] ) ? $args['booking_status'] : '';
$status_label    = isset( $args['status_label'] ) ? $args['status_label'] : '';
?>

<div class="york-event-details">
	<h3><?php esc_html_e( 'Event Details', 'york-alumni' ); ?></h3>

	<div class="york-event-meta-grid">

		<?php if ( ! empty( $formatted_date ) ) : ?>
			<div class="york-event-meta-item">
				<span class="york-event-meta-label"><?php esc_html_e( 'Date', 'york-alumni' ); ?></span>
				<span class="york-event-meta-value"><?php echo esc_html( $formatted_date ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $formatted_time ) ) : ?>
			<div class="york-event-meta-item">
				<span class="york-event-meta-label"><?php esc_html_e( 'Time', 'york-alumni' ); ?></span>
				<span class="york-event-meta-value"><?php echo esc_html( $formatted_time ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $location ) ) : ?>
			<div class="york-event-meta-item">
				<span class="york-event-meta-label"><?php esc_html_e( 'Location', 'york-alumni' ); ?></span>
				<span class="york-event-meta-value"><?php echo nl2br( esc_html( $location ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</div>
		<?php endif; ?>

		<?php if ( '' !== $available_seats ) : ?>
			<div class="york-event-meta-item">
				<span class="york-event-meta-label"><?php esc_html_e( 'Available Seats', 'york-alumni' ); ?></span>
				<span class="york-event-meta-value"><?php echo esc_html( absint( $available_seats ) ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $booking_status ) ) : ?>
			<div class="york-event-meta-item">
				<span class="york-event-meta-label"><?php esc_html_e( 'Booking Status', 'york-alumni' ); ?></span>
				<span class="york-event-status-badge york-event-status-<?php echo esc_attr( $booking_status ); ?>">
					<?php echo esc_html( $status_label ); ?>
				</span>
			</div>
		<?php endif; ?>

	</div>
</div>
