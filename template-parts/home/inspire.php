<?php
/**
 * Template Part — Inspire Section (bg-blue).
 * @package YorkAlumni
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$page_id   = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$title     = york_get_meta( $page_id, '_york_inspire_title', __( 'Your journey can inspire future learners', 'york-alumni' ) );
$para_1    = york_get_meta( $page_id, '_york_inspire_para1', __( 'If you\'re a UoL alumni, share your experience through a video or written testimonial.', 'york-alumni' ) );
$para_2    = york_get_meta( $page_id, '_york_inspire_para2' );
$btn_label = york_get_meta( $page_id, '_york_inspire_btn_label', __( 'Share your experience', 'york-alumni' ) );
$btn_url   = york_get_meta( $page_id, '_york_inspire_btn_url', '#' );
$image_id  = york_get_meta( $page_id, '_york_inspire_image_id' );
?>
<section class="york-cms-custom-section york-custom-section bg-blue" aria-label="<?php echo esc_attr( $title ); ?>">
	<div class="container">
		<div class="custom-content-with-media">
			<div class="custom-content">
				<h2><?php echo esc_html( $title ); ?></h2>
				<?php if ( ! empty( $para_1 ) ) : ?><p class="p-large"><?php echo esc_html( $para_1 ); ?></p><?php endif; ?>
				<?php if ( ! empty( $para_2 ) ) : ?><p class="p-large last"><?php echo esc_html( $para_2 ); ?></p><?php endif; ?>
				<?php if ( ! empty( $btn_label ) ) : ?>
					<a href="<?php echo esc_url( $btn_url ); ?>" class="cta cta-yellow-transparant"><span><?php echo esc_html( $btn_label ); ?></span></a>
				<?php endif; ?>
			</div>
			<div class="custom-media">
				<?php if ( ! empty( $image_id ) ) : ?>
					<?php echo york_get_image( $image_id, 'york-alumni', array( 'alt' => esc_attr( $title ) ) ); // phpcs:ignore ?>
				<?php else : ?>
					<img src="<?php echo esc_url( YORK_URI . '/assets/images/graduation.png' ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
