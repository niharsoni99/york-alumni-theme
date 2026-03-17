<?php
/**
 * Template Part — Alumni Benefits Section (repeater).
 * @package YorkAlumni
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$page_id       = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$section_title = york_get_meta( $page_id, '_york_benefits_title', __( 'Alumni benefits and services', 'york-alumni' ) );
$items         = york_get_repeater( $page_id, '_york_benefit_items', 6, array( 'heading' => 'Lorem ipsum (PDF)', 'text' => 'Lorem ipsum dolor sit amet consectetur.' ) );
if ( empty( $items ) ) return;
?>
<section class="york-cms-custom-section york-custom-section bg-grey" aria-label="<?php echo esc_attr( $section_title ); ?>">
	<div class="container">
		<h2><?php echo esc_html( $section_title ); ?></h2>
		<div class="york-custom-block-group york-custom-block-three">
			<?php foreach ( $items as $item ) : ?>
				<div class="york-custom-block">
					<h3><?php echo esc_html( $item['heading'] ?? '' ); ?></h3>
					<?php if ( ! empty( $item['text'] ) ) : ?>
						<p class="p-large"><?php echo esc_html( $item['text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
