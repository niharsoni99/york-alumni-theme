<?php
/**
 * Template helper functions.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function york_get_meta( int $post_id, string $meta_key, string $default = '' ): string {
	$value = get_post_meta( $post_id, $meta_key, true );
	return ( ! empty( $value ) ) ? (string) $value : $default;
}

function york_get_image( $image_id, string $size = 'york-card', array $attr = array() ): string {
	$image_id = absint( $image_id );
	if ( ! $image_id ) return '';
	$attr = array_merge( array( 'loading' => 'lazy' ), $attr );
	return wp_get_attachment_image( $image_id, $size, false, $attr );
}

/**
 * Get repeater items from meta, with optional default count.
 */
function york_get_repeater( int $post_id, string $meta_key, int $default_count = 0, array $default_item = array() ): array {
	$items = get_post_meta( $post_id, $meta_key, true );
	if ( ! empty( $items ) && is_array( $items ) ) {
		return $items;
	}
	if ( $default_count > 0 ) {
		return array_fill( 0, $default_count, $default_item );
	}
	return array();
}

/**
 * Render event meta on single event page.
 */
function york_render_event_meta( int $post_id ): void {
	$event_date      = get_post_meta( $post_id, '_event_date', true );
	$event_time      = get_post_meta( $post_id, '_event_time', true );
	$location        = get_post_meta( $post_id, '_event_location', true );
	$available_seats = get_post_meta( $post_id, '_available_seats', true );
	$booking_status  = get_post_meta( $post_id, '_booking_status', true );

	if ( empty( $event_date ) && empty( $event_time ) && empty( $location ) && '' === $available_seats ) {
		return;
	}

	$formatted_date = '';
	if ( ! empty( $event_date ) ) {
		$date_obj       = \DateTime::createFromFormat( 'Y-m-d', $event_date );
		$formatted_date = $date_obj ? $date_obj->format( 'F j, Y' ) : $event_date;
	}

	$formatted_time = '';
	if ( ! empty( $event_time ) ) {
		$time_obj       = \DateTime::createFromFormat( 'H:i', $event_time );
		$formatted_time = $time_obj ? $time_obj->format( 'g:i A' ) : $event_time;
	}

	$status_labels = array(
		'open'      => __( 'Open', 'york-alumni' ),
		'closed'    => __( 'Closed', 'york-alumni' ),
		'cancelled' => __( 'Cancelled', 'york-alumni' ),
	);
	$status_label = isset( $status_labels[ $booking_status ] )
		? $status_labels[ $booking_status ]
		: ucfirst( esc_html( (string) $booking_status ) );

	get_template_part( 'template-parts/event', 'meta', array(
		'formatted_date'  => $formatted_date,
		'formatted_time'  => $formatted_time,
		'location'        => $location,
		'available_seats' => $available_seats,
		'booking_status'  => $booking_status,
		'status_label'    => $status_label,
	) );
}

function york_svg_sprite(): void {
	$sprite_path = YORK_DIR . '/assets/images/sprite.svg';
	if ( file_exists( $sprite_path ) ) {
		echo '<div class="york-svg-sprite" aria-hidden="true" style="display:none;">';
		echo file_get_contents( $sprite_path ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
	}
}

function york_get_status_badge( string $status ): string {
	$labels = array(
		'open'      => __( 'Open', 'york-alumni' ),
		'closed'    => __( 'Closed', 'york-alumni' ),
		'cancelled' => __( 'Cancelled', 'york-alumni' ),
	);
	if ( empty( $status ) || ! isset( $labels[ $status ] ) ) return '';
	return sprintf(
		'<span class="york-event-status-badge york-event-status-%s">%s</span>',
		esc_attr( $status ),
		esc_html( $labels[ $status ] )
	);
}
