<?php
/**
 * Template Part — Voices of Alumni (repeater).
 * @package YorkAlumni
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$page_id       = isset( $args['page_id'] ) ? (int) $args['page_id'] : get_the_ID();
$section_title = york_get_meta( $page_id, '_york_voices_title', __( 'Voices of our alumni', 'york-alumni' ) );
$section_text  = york_get_meta( $page_id, '_york_voices_text' );
$videos        = york_get_repeater( $page_id, '_york_voice_items', 3, array( 'title' => 'How I got my undergraduate placement', 'desc' => 'York graduate? See if you\'re eligible to save 10 per cent.', 'video_url' => '', 'thumb_id' => '' ) );
if ( empty( $videos ) ) return;
?>
<section class="york-cms-custom-section york-custom-section voice-of-alumni bg-green" aria-label="<?php echo esc_attr( $section_title ); ?>">
	<div class="container">
		<div class="heading-section text_white">
			<h2><?php echo esc_html( $section_title ); ?></h2>
			<?php if ( ! empty( $section_text ) ) : ?>
				<p class="p-large"><?php echo esc_html( $section_text ); ?></p>
			<?php endif; ?>
		</div>
		<div class="voice-of-alumni-row">
			<?php foreach ( $videos as $idx => $video ) :
				$modal_id = 'voiceModal' . $idx;
			?>
				<div class="voice-of-alumni-block"
					<?php if ( ! empty( $video['video_url'] ) ) : ?>
						data-bs-toggle="modal" data-bs-target="#<?php echo esc_attr( $modal_id ); ?>"
					<?php endif; ?>
					role="<?php echo ! empty( $video['video_url'] ) ? 'button' : 'article'; ?>"
					tabindex="<?php echo ! empty( $video['video_url'] ) ? '0' : '-1'; ?>"
					aria-label="<?php echo esc_attr( $video['title'] ?? '' ); ?>">
					<div class="voice-of-alumni-media">
						<?php if ( ! empty( $video['thumb_id'] ) ) : ?>
							<?php echo york_get_image( $video['thumb_id'], 'york-card', array( 'alt' => esc_attr( $video['title'] ?? '' ) ) ); // phpcs:ignore ?>
						<?php else : ?>
							<img src="<?php echo esc_url( YORK_URI . '/assets/images/voice-of-alumni.png' ); ?>" alt="<?php echo esc_attr( $video['title'] ?? '' ); ?>" loading="lazy" />
						<?php endif; ?>
						<?php if ( ! empty( $video['video_url'] ) ) : ?>
							<div class="play-button-overlay" aria-hidden="true">
								<img src="<?php echo esc_url( YORK_URI . '/assets/images/play-btn.svg' ); ?>" alt="" loading="lazy" />
							</div>
						<?php endif; ?>
						<div class="video-label label" aria-hidden="true">
							<span>
								<img src="<?php echo esc_url( YORK_URI . '/assets/images/video.png' ); ?>" alt="" loading="lazy" />
								<?php esc_html_e( 'Video', 'york-alumni' ); ?>
							</span>
						</div>
					</div>
					<div class="voice-of-alumni-content">
						<h3><?php echo esc_html( $video['title'] ?? '' ); ?></h3>
						<p><?php echo esc_html( $video['desc'] ?? '' ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php foreach ( $videos as $idx => $video ) :
	if ( empty( $video['video_url'] ) ) continue;
	$modal_id = 'voiceModal' . $idx;
?>
<div class="modal fade alumni-popup" id="<?php echo esc_attr( $modal_id ); ?>" tabindex="-1" aria-label="<?php echo esc_attr( $video['title'] ?? '' ); ?>" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header border-0">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e( 'Close', 'york-alumni' ); ?>"></button>
			</div>
			<div class="modal-body p-0">
				<div class="ratio ratio-16x9">
					<div class="js-video-thumb" style="cursor:pointer;" data-modal="<?php echo esc_attr( $modal_id ); ?>">
						<?php if ( ! empty( $video['thumb_id'] ) ) : ?>
							<?php echo york_get_image( $video['thumb_id'], 'york-alumni', array( 'class' => 'w-100 h-100 object-fit-cover', 'alt' => esc_attr( $video['title'] ?? '' ) ) ); // phpcs:ignore ?>
						<?php else : ?>
							<img src="<?php echo esc_url( YORK_URI . '/assets/images/voice-of-alumni.png' ); ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo esc_attr( $video['title'] ?? '' ); ?>" />
						<?php endif; ?>
						<div class="play-button-overlay">
							<img src="<?php echo esc_url( YORK_URI . '/assets/images/play-btn.svg' ); ?>" alt="<?php esc_attr_e( 'Play', 'york-alumni' ); ?>" />
						</div>
					</div>
					<iframe class="js-video-iframe w-100 h-100" data-src="<?php echo esc_url( $video['video_url'] ); ?>" frameborder="0" allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen title="<?php echo esc_attr( $video['title'] ?? '' ); ?>" style="display:none;"></iframe>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>
