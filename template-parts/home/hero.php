<?php
/**
 * Template Part — Hero Banner Section.
 *
 * @package YorkAlumni
 * @var array $args { page_id: int }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_id  = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$title    = york_get_meta( $page_id, '_york_hero_title', get_the_title( $page_id ) );
$caption  = york_get_meta( $page_id, '_york_hero_caption' );
$btn_text = york_get_meta( $page_id, '_york_hero_btn_text', __( 'Join the alumni network', 'york-alumni' ) );
$btn_url  = york_get_meta( $page_id, '_york_hero_btn_url', home_url( '/' ) );
$image_id = york_get_meta( $page_id, '_york_hero_image_id' );

// Fallback: use featured image if no custom image set.
if ( empty( $image_id ) ) {
	$image_id = (string) get_post_thumbnail_id( $page_id );
}
?>

<section class="herobanner-cmspage" aria-label="<?php esc_attr_e( 'Hero Banner', 'york-alumni' ); ?>">

	<!-- Desktop hero image -->
	<div class="herobanner-cmspage-desktop">
		<?php if ( ! empty( $image_id ) ) : ?>
			<?php echo york_get_image( $image_id, 'york-hero', array( 'alt' => esc_attr( $title ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<img
				src="<?php echo esc_url( YORK_URI . '/assets/images/alumni-hero-banner.jpg' ); ?>"
				alt="<?php echo esc_attr( $title ); ?>"
				loading="lazy"
			/>
		<?php endif; ?>
	</div>

	<div class="container">
		<div class="herobanner-cms-row">

			<!-- Mobile hero image (hidden via CSS on desktop) -->
			<div class="herobanner-cmspage-mobile" aria-hidden="true">
				<?php if ( ! empty( $image_id ) ) : ?>
					<?php echo york_get_image( $image_id, 'york-card', array( 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<img
						src="<?php echo esc_url( YORK_URI . '/assets/images/alumni-hero-banner.jpg' ); ?>"
						alt=""
						loading="lazy"
					/>
				<?php endif; ?>
			</div>

			<div class="herobanner-cmspage-info">

				<?php if ( ! empty( $title ) ) : ?>
					<h1><?php echo esc_html( $title ); ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $caption ) ) : ?>
					<p class="hero-caption"><?php echo esc_html( $caption ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $btn_text ) ) : ?>
					<div>
						<a href="<?php echo esc_url( $btn_url ); ?>" class="cta cta-yellow">
							<span><?php echo esc_html( $btn_text ); ?></span>
						</a>
					</div>
				<?php endif; ?>

			</div><!-- .herobanner-cmspage-info -->

		</div><!-- .herobanner-cms-row -->
	</div><!-- .container -->

</section>
