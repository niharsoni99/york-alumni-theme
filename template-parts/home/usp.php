<?php
/**
 * Template Part — USP / Key Statistics Section.
 *
 * Fetches 'statistics' CPT posts selected and ordered
 * via the USP meta box on the page edit screen.
 * Icons use the default SVG — can be overridden via
 * the Statistics CPT featured image if set.
 *
 * @package YorkAlumni
 * @var array $args { page_id: int }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id    = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$section_bg = get_post_meta( $page_id, '_york_usp_bg', true ) ?: 'bg-blue';

// Get selected ordered post IDs (saved as comma-separated string).
$saved_ids_raw = get_post_meta( $page_id, '_york_usp_post_ids', true );
$selected_ids  = ! empty( $saved_ids_raw )
	? array_slice( array_filter( array_map( 'absint', explode( ',', $saved_ids_raw ) ) ), 0, 4 )
	: array();

// Bail if nothing selected.
if ( empty( $selected_ids ) ) {
	return;
}

// Fetch posts in the saved order using posts__in + orderby.
$stats_query = new WP_Query( array(
	'post_type'      => 'statistics',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'post__in'       => $selected_ids,
	'orderby'        => 'post__in', // Preserve manual order.
) );

if ( ! $stats_query->have_posts() ) {
	return;
}
?>

<section
	class="york-cms-custom-section york-custom-section usp-section <?php echo esc_attr( $section_bg ); ?>"
	aria-label="<?php esc_attr_e( 'Key Statistics', 'york-alumni' ); ?>"
>
	<div class="container">
		<div class="usp-row">

			<?php while ( $stats_query->have_posts() ) : $stats_query->the_post(); ?>

				<div class="york-usp-block">

					<!-- Icon: featured image if set, else default SVG -->
					<div class="usp-media" aria-hidden="true">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'thumbnail', array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
						<?php else : ?>
							<svg width="29" height="24" viewBox="0 0 29 24" fill="none"
								xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
								<path d="M26.3344 18.4341V9.34873L14.4839 15.8007L0 7.90033L14.4839 0L28.9679 7.90033V18.4341H26.3344ZM14.4839 23.701L5.26689 18.6975V12.1138L14.4839 17.1174L23.701 12.1138V18.6975L14.4839 23.701Z" fill="#8C8572"/>
							</svg>
						<?php endif; ?>
					</div>

					<!-- Title (heading) + Excerpt (description) -->
					<div class="usp-content">
						<h3><?php the_title(); ?></h3>
						<?php if ( has_excerpt() ) : ?>
							<p><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
					</div>

				</div><!-- .york-usp-block -->

			<?php endwhile; ?>

		</div><!-- .usp-row -->
	</div><!-- .container -->
</section>

<?php wp_reset_postdata(); ?>
