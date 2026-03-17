<?php
/**
 * Template Part — Bottom CTA Blocks (repeater).
 * @package YorkAlumni
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$page_id = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$default = array( array( 'title' => 'DISCOVER YORK IN MUMBAI', 'btn_label' => 'Download our prospectus', 'btn_url' => '#', 'icon_id' => '' ), array( 'title' => 'REACH US', 'btn_label' => 'Connect with an advisor', 'btn_url' => '#', 'icon_id' => '' ), array( 'title' => 'READY TO JOIN UNIVERSITY OF YORK?', 'btn_label' => 'Apply now', 'btn_url' => '#', 'icon_id' => '' ) );
$blocks  = york_get_repeater( $page_id, '_york_block_items' );
if ( empty( $blocks ) ) $blocks = $default;
?>
<section class="york-cms-custom-section york-custom-section bg-grey" aria-label="<?php esc_attr_e( 'Get Started', 'york-alumni' ); ?>">
	<div class="container">
		<div class="york-custom-block-group york-custom-block-three">
			<?php foreach ( $blocks as $block ) : ?>
				<div class="york-custom-block box-shadow tac">
					<?php if ( ! empty( $block['icon_id'] ) ) : ?>
						<?php echo york_get_image( $block['icon_id'], 'thumbnail', array( 'alt' => '' ) ); // phpcs:ignore ?>
					<?php else : ?>
						<img src="<?php echo esc_url( YORK_URI . '/assets/images/landmark.svg' ); ?>" alt="" loading="lazy" />
					<?php endif; ?>
					<h3><?php echo esc_html( $block['title'] ?? '' ); ?></h3>
					<?php if ( ! empty( $block['btn_label'] ) ) : ?>
						<a href="<?php echo esc_url( $block['btn_url'] ?? '#' ); ?>" class="cta cta-blue"><span><?php echo esc_html( $block['btn_label'] ); ?></span></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
