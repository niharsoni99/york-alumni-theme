<?php
/**
 * Template Part — USP Blocks Section (repeater).
 * @package YorkAlumni
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$page_id    = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$section_bg = york_get_meta( $page_id, '_york_usp_bg', 'bg-blue' );
$usp_items  = york_get_repeater( $page_id, '_york_usp_items', 4, array( 'heading' => '93.5% of York students', 'text' => 'Lorem ipsum dolor sit amet consectetur.', 'icon_id' => '' ) );
if ( empty( $usp_items ) ) return;
?>
<section class="york-cms-custom-section york-custom-section usp-section <?php echo esc_attr( $section_bg ); ?>" aria-label="<?php esc_attr_e( 'Key Statistics', 'york-alumni' ); ?>">
	<div class="container">
		<div class="usp-row">
			<?php foreach ( $usp_items as $block ) : ?>
				<div class="york-usp-block">
					<div class="usp-media" aria-hidden="true">
						<?php if ( ! empty( $block['icon_id'] ) ) : ?>
							<?php echo york_get_image( $block['icon_id'], 'thumbnail', array( 'alt' => '' ) ); // phpcs:ignore ?>
						<?php else : ?>
							<svg width="29" height="24" viewBox="0 0 29 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
								<path d="M26.3344 18.4341V9.34873L14.4839 15.8007L0 7.90033L14.4839 0L28.9679 7.90033V18.4341H26.3344ZM14.4839 23.701L5.26689 18.6975V12.1138L14.4839 17.1174L23.701 12.1138V18.6975L14.4839 23.701Z" fill="#8C8572"/>
							</svg>
						<?php endif; ?>
					</div>
					<div class="usp-content">
						<h3><?php echo esc_html( $block['heading'] ?? '' ); ?></h3>
						<?php if ( ! empty( $block['text'] ) ) : ?>
							<p><?php echo esc_html( $block['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
