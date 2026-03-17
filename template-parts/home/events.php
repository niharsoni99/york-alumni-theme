<?php
/**
 * Template Part — Upcoming Events Display.
 *
 * Pulls upcoming events from the plugin's 'event' CPT.
 * Shows date, time, location, seats, status.
 * This is the extra section added to the home page.
 *
 * @package YorkAlumni
 * @var array $args { page_id: int }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Query upcoming events — future date only, sorted ASC.
$events_query = new WP_Query(
	array(
		'post_type'      => 'event',
		'post_status'    => 'publish',
		'posts_per_page' => 6,
		'meta_key'       => '_event_date',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
		'meta_query'     => array(
			array(
				'key'     => '_event_date',
				'value'   => current_time( 'Y-m-d' ),
				'compare' => '>=',
				'type'    => 'DATE',
			),
		),
	)
);

// Only render section if events exist.
if ( ! $events_query->have_posts() ) {
	wp_reset_postdata();
	return;
}
?>

<section class="york-cms-custom-section york-custom-section bg-grey york-events-section" aria-label="<?php esc_attr_e( 'Upcoming Events', 'york-alumni' ); ?>">
	<div class="container">

		<div class="york-events-header">
			<h2><?php esc_html_e( 'Upcoming Events', 'york-alumni' ); ?></h2>
			<a href="<?php echo esc_url( get_post_type_archive_link( 'event' ) ); ?>" class="cta cta-transparant">
				<span><?php esc_html_e( 'View All Events', 'york-alumni' ); ?></span>
			</a>
		</div>

		<div class="york-events-grid york-custom-block-group york-custom-block-three">

			<?php
			while ( $events_query->have_posts() ) :
				$events_query->the_post();

				$event_id        = get_the_ID();
				$event_date      = get_post_meta( $event_id, '_event_date', true );
				$event_time      = get_post_meta( $event_id, '_event_time', true );
				$location        = get_post_meta( $event_id, '_event_location', true );
				$available_seats = get_post_meta( $event_id, '_available_seats', true );
				$booking_status  = get_post_meta( $event_id, '_booking_status', true );

				// Format date.
				$formatted_date = '';
				if ( ! empty( $event_date ) ) {
					$date_obj       = \DateTime::createFromFormat( 'Y-m-d', $event_date );
					$formatted_date = $date_obj ? $date_obj->format( 'M j, Y' ) : $event_date;
				}

				// Format time.
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
				$status_label = isset( $status_labels[ $booking_status ] ) ? $status_labels[ $booking_status ] : ucfirst( esc_html( (string) $booking_status ) );
				?>

				<article class="york-custom-block york-event-card" id="event-<?php echo esc_attr( $event_id ); ?>">

					<?php if ( has_post_thumbnail() ) : ?>
						<div class="york-event-card-image">
							<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
								<?php the_post_thumbnail( 'york-card', array( 'loading' => 'lazy', 'alt' => get_the_title() ) ); ?>
							</a>
							<?php if ( ! empty( $booking_status ) ) : ?>
								<span class="york-event-status-badge york-event-status-<?php echo esc_attr( $booking_status ); ?>">
									<?php echo esc_html( $status_label ); ?>
								</span>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="york-event-card-body">

						<h3 class="york-event-card-title">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>

						<div class="york-event-card-meta">

							<?php if ( ! empty( $formatted_date ) ) : ?>
								<div class="york-event-meta-row">
									<span class="york-event-meta-icon" aria-hidden="true">&#128197;</span>
									<span><?php echo esc_html( $formatted_date ); ?></span>
									<?php if ( ! empty( $formatted_time ) ) : ?>
										<span aria-hidden="true">&bull;</span>
										<span><?php echo esc_html( $formatted_time ); ?></span>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $location ) ) : ?>
								<div class="york-event-meta-row">
									<span class="york-event-meta-icon" aria-hidden="true">&#128205;</span>
									<span><?php echo esc_html( wp_trim_words( $location, 6 ) ); ?></span>
								</div>
							<?php endif; ?>

							<?php if ( '' !== $available_seats && false !== $available_seats ) : ?>
								<div class="york-event-meta-row">
									<span class="york-event-meta-icon" aria-hidden="true">&#127903;</span>
									<span>
										<?php
										echo esc_html(
											sprintf(
												// translators: %d: number of available seats.
												_n( '%d seat available', '%d seats available', absint( $available_seats ), 'york-alumni' ),
												absint( $available_seats )
											)
										);
										?>
									</span>
								</div>
							<?php endif; ?>

						</div><!-- .york-event-card-meta -->

						<a href="<?php the_permalink(); ?>" class="cta cta-blue">
							<span>
								<?php echo 'open' === $booking_status ? esc_html__( 'View &amp; Book', 'york-alumni' ) : esc_html__( 'View Details', 'york-alumni' ); ?>
							</span>
						</a>

					</div><!-- .york-event-card-body -->

				</article>

			<?php endwhile; ?>

		</div><!-- .york-events-grid -->

	</div>
</section>

<?php wp_reset_postdata(); ?>
