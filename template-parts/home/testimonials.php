<?php
/**
 * Template Part — Alumni Stories / Testimonials (repeater).
 * @package YorkAlumni
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$page_id       = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$section_title = york_get_meta( $page_id, '_york_testimonials_title', __( 'Alumni stories', 'york-alumni' ) );
$items         = york_get_repeater( $page_id, '_york_testi_items', 3, array( 'quote' => 'As a University for public good, our ambition is clear.', 'name' => 'Loreum Ipsum', 'designation' => 'History student', 'image_id' => '' ) );
if ( empty( $items ) ) return;
?>
<section class="york-cms-custom-section york-custom-section testimonials-section white-bg" aria-label="<?php echo esc_attr( $section_title ); ?>">
	<div class="container">
		<h2><?php echo esc_html( $section_title ); ?></h2>
		<div class="testimonials-slider js-testimonials-slider">
			<?php foreach ( $items as $slide ) : ?>
				<div class="slide">
					<div class="item-inner">
						<h3><?php echo esc_html( $slide['quote'] ?? '' ); ?></h3>
						<div class="media-with-caption">
							<?php if ( ! empty( $slide['image_id'] ) ) : ?>
								<?php echo york_get_image( $slide['image_id'], 'york-thumb', array( 'alt' => esc_attr( $slide['name'] ?? '' ) ) ); // phpcs:ignore ?>
							<?php else : ?>
								<img src="<?php echo esc_url( YORK_URI . '/assets/images/testimonials-img.jpg' ); ?>" alt="<?php echo esc_attr( $slide['name'] ?? '' ); ?>" loading="lazy" />
							<?php endif; ?>
							<div class="testimonials-caption">
								<?php echo esc_html( $slide['name'] ?? '' ); ?>,
								<span class="testimonials-designation"><?php echo esc_html( $slide['designation'] ?? '' ); ?></span>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
