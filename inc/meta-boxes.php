<?php
/**
 * Meta Boxes — Tabbed UI with Repeaters, Image Picker, Link Fields.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─────────────────────────────────────────────────────────────
// REGISTER META BOX
// ─────────────────────────────────────────────────────────────

function york_register_meta_boxes(): void {
	add_meta_box(
		'york_home_sections',
		__( '🏠 Home Page Sections', 'york-alumni' ),
		'york_render_tabbed_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'york_register_meta_boxes' );

// ─────────────────────────────────────────────────────────────
// ENQUEUE ADMIN ASSETS
// ─────────────────────────────────────────────────────────────

function york_meta_box_assets( string $hook ): void {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	// Media library for image picker.
	wp_enqueue_media();

	// Admin CSS — separate file, no inline.
	wp_enqueue_style(
		'york-admin',
		YORK_URI . '/assets/css/york-admin.css',
		array(),
		YORK_VERSION
	);

	// Admin JS — depends on WP default jquery (NOT our replaced frontend one).
	wp_enqueue_script(
		'york-admin',
		YORK_URI . '/assets/js/york-admin.js',
		array( 'jquery', 'media-upload' ),
		YORK_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'york_meta_box_assets' );

// ─────────────────────────────────────────────────────────────
// FIELD HELPERS
// ─────────────────────────────────────────────────────────────

function york_field_text( string $name, string $label, string $value, string $placeholder = '' ): void {
	?>
	<div class="york-field">
		<label><?php echo esc_html( $label ); ?></label>
		<input type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>">
	</div>
	<?php
}

function york_field_textarea( string $name, string $label, string $value, string $placeholder = '' ): void {
	?>
	<div class="york-field">
		<label><?php echo esc_html( $label ); ?></label>
		<textarea name="<?php echo esc_attr( $name ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
	</div>
	<?php
}

function york_field_link( string $name_url, string $name_label, string $field_label, string $url_val, string $label_val ): void {
	?>
	<div class="york-field">
		<label><?php echo esc_html( $field_label ); ?></label>
		<div class="york-link-field">
			<div>
				<span>Button Label</span>
				<input type="text" name="<?php echo esc_attr( $name_label ); ?>" value="<?php echo esc_attr( $label_val ); ?>" placeholder="e.g. Join Now">
			</div>
			<div>
				<span>URL</span>
				<input type="url" name="<?php echo esc_attr( $name_url ); ?>" value="<?php echo esc_url( $url_val ); ?>" placeholder="https://">
			</div>
		</div>
	</div>
	<?php
}

function york_field_image( string $name, string $label, $image_id ): void {
	$image_id  = absint( $image_id );
	$thumb_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';
	$has_image = ! empty( $thumb_url );
	?>
	<div class="york-field">
		<label><?php echo esc_html( $label ); ?></label>
		<div class="york-image-field">
			<div class="york-image-preview">
				<img src="<?php echo esc_url( $thumb_url ); ?>" <?php echo $has_image ? '' : 'style="display:none;"'; ?>>
			</div>
			<div class="york-image-controls">
				<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $image_id ); ?>" class="york-image-id">
				<button type="button" class="button york-select-image"><?php esc_html_e( '+ Select Image', 'york-alumni' ); ?></button>
				<button type="button" class="button-link york-remove-image" <?php echo $has_image ? '' : 'style="display:none;"'; ?>><?php esc_html_e( '✕ Remove', 'york-alumni' ); ?></button>
			</div>
		</div>
	</div>
	<?php
}

function york_field_select( string $name, string $label, string $value, array $options ): void {
	?>
	<div class="york-field">
		<label><?php echo esc_html( $label ); ?></label>
		<select name="<?php echo esc_attr( $name ); ?>">
			<?php foreach ( $options as $val => $opt_label ) : ?>
				<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $value, $val ); ?>><?php echo esc_html( $opt_label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php
}

// ─────────────────────────────────────────────────────────────
// REPEATER
// ─────────────────────────────────────────────────────────────

/**
 * Render repeater with collapsible rows + add/remove.
 *
 * @param string   $post_key  POST array key (e.g. york_usp_items).
 * @param string   $label     Row label prefix (e.g. "USP Block").
 * @param array    $items     Saved items.
 * @param callable $row_cb    function( int $index, array $item, bool $is_template ) renders fields.
 */
function york_render_repeater( string $post_key, string $label, array $items, callable $row_cb ): void {
	?>
	<div class="york-repeater-wrap" data-label="<?php echo esc_attr( $label ); ?>">
		<div class="york-repeater">

			<div class="york-repeater-list">
				<?php foreach ( $items as $i => $item ) : ?>
					<div class="york-repeater-item">
						<div class="york-repeater-header">
							<span class="york-repeater-handle" title="Drag">⠿</span>
							<span class="york-repeater-label"><?php echo esc_html( $label . ' ' . ( $i + 1 ) ); ?></span>
							<button type="button" class="york-repeater-remove"><?php esc_html_e( '✕ Remove', 'york-alumni' ); ?></button>
							<span class="york-repeater-toggle">▾</span>
						</div>
						<div class="york-repeater-body">
							<?php $row_cb( $i, $item, false ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php
			/*
			 * JS template for new rows.
			 * Uses __INDEX__ placeholder — replaced by JS on add.
			 * We call $row_cb with '__INDEX__' string as index so field names get __INDEX__.
			 */
			?>
			<script type="text/html" class="york-repeater-template">
				<div class="york-repeater-item is-open">
					<div class="york-repeater-header">
						<span class="york-repeater-handle" title="Drag">⠿</span>
						<span class="york-repeater-label"><?php echo esc_html( $label ); ?> __INDEX__</span>
						<button type="button" class="york-repeater-remove"><?php esc_html_e( '✕ Remove', 'york-alumni' ); ?></button>
						<span class="york-repeater-toggle">▾</span>
					</div>
					<div class="york-repeater-body">
						<?php $row_cb( '__INDEX__', array(), true ); ?>
					</div>
				</div>
			</script>

			<button type="button" class="york-add-repeater-item">
				+ <?php echo esc_html( sprintf( __( 'Add %s', 'york-alumni' ), $label ) ); ?>
			</button>

		</div>
	</div>
	<?php
}

// ─────────────────────────────────────────────────────────────
// MAIN TABBED META BOX
// ─────────────────────────────────────────────────────────────

function york_render_tabbed_meta_box( \WP_Post $post ): void {
	wp_nonce_field( 'york_home_save_all', '_york_home_nonce' );

	$id = $post->ID;

	$tabs = array(
		'hero'         => '🏠 Hero',
		'usp'          => '📊 USP',
		'benefits'     => '🎓 Benefits',
		'content'      => '🖼️ Content',
		'voices'       => '🎥 Voices',
		'testimonials' => '💬 Testimonials',
		'inspire'      => '✨ Inspire',
		'blocks'       => '📦 CTA Blocks',
	);
	?>
	<div class="york-tabs-wrap">

		<ul class="york-tabs-nav">
			<?php $first = true; foreach ( $tabs as $key => $label ) : ?>
				<li>
					<button type="button" data-tab="<?php echo esc_attr( $key ); ?>"
						class="<?php echo $first ? 'is-active' : ''; ?>">
						<?php echo esc_html( $label ); ?>
					</button>
				</li>
			<?php $first = false; endforeach; ?>
		</ul>

		<?php /* ── HERO ── */ ?>
		<div class="york-tab-panel is-active" data-tab="hero">
			<div class="york-field-row">
				<?php
				york_field_text( 'york_hero[title]', 'Hero Title', (string) get_post_meta( $id, '_york_hero_title', true ) );
				york_field_text( 'york_hero[caption]', 'Caption / Subtitle', (string) get_post_meta( $id, '_york_hero_caption', true ) );
				?>
			</div>
			<?php
			york_field_link(
				'york_hero[btn_url]',
				'york_hero[btn_label]',
				'CTA Button',
				(string) get_post_meta( $id, '_york_hero_btn_url', true ),
				(string) get_post_meta( $id, '_york_hero_btn_label', true )
			);
			york_field_image( 'york_hero[image_id]', 'Hero Background Image', get_post_meta( $id, '_york_hero_image_id', true ) );
			?>
		</div>

		<?php /* ── USP ── */ ?>
		<div class="york-tab-panel" data-tab="usp">
			<?php
			york_field_select(
				'york_usp[bg]',
				'Section Background',
				(string) ( get_post_meta( $id, '_york_usp_bg', true ) ?: 'bg-blue' ),
				array( 'bg-blue' => 'Blue', 'bg-green' => 'Green', 'bg-grey' => 'Grey', '' => 'White' )
			);
			$usp_items = get_post_meta( $id, '_york_usp_items', true );
			if ( empty( $usp_items ) || ! is_array( $usp_items ) ) {
				$usp_items = array_fill( 0, 4, array( 'heading' => '', 'text' => '', 'icon_id' => '' ) );
			}
			york_render_repeater(
				'york_usp_items',
				'USP Block',
				$usp_items,
				function ( $i, array $item, bool $tpl ) {
					york_field_text( "york_usp_items[{$i}][heading]", 'Heading', $item['heading'] ?? '' );
					york_field_textarea( "york_usp_items[{$i}][text]", 'Description', $item['text'] ?? '' );
					york_field_image( "york_usp_items[{$i}][icon_id]", 'Icon', $item['icon_id'] ?? '' );
				}
			);
			?>
		</div>

		<?php /* ── BENEFITS ── */ ?>
		<div class="york-tab-panel" data-tab="benefits">
			<?php
			york_field_text( 'york_benefits[title]', 'Section Title', (string) get_post_meta( $id, '_york_benefits_title', true ), 'Alumni benefits and services' );
			$benefit_items = get_post_meta( $id, '_york_benefit_items', true );
			if ( empty( $benefit_items ) || ! is_array( $benefit_items ) ) {
				$benefit_items = array_fill( 0, 6, array( 'heading' => '', 'text' => '' ) );
			}
			york_render_repeater(
				'york_benefit_items',
				'Benefit',
				$benefit_items,
				function ( $i, array $item, bool $tpl ) {
					york_field_text( "york_benefit_items[{$i}][heading]", 'Heading', $item['heading'] ?? '' );
					york_field_textarea( "york_benefit_items[{$i}][text]", 'Description', $item['text'] ?? '' );
				}
			);
			?>
		</div>

		<?php /* ── CONTENT + MEDIA ── */ ?>
		<div class="york-tab-panel" data-tab="content">
			<?php
			york_field_text( 'york_cm[title]', 'Section Title', (string) get_post_meta( $id, '_york_cm_title', true ) );
			york_field_textarea( 'york_cm[para1]', 'Paragraph 1', (string) get_post_meta( $id, '_york_cm_para1', true ) );
			york_field_textarea( 'york_cm[para2]', 'Paragraph 2 (optional)', (string) get_post_meta( $id, '_york_cm_para2', true ) );
			york_field_link(
				'york_cm[btn_url]',
				'york_cm[btn_label]',
				'CTA Button',
				(string) get_post_meta( $id, '_york_cm_btn_url', true ),
				(string) get_post_meta( $id, '_york_cm_btn_label', true )
			);
			york_field_image( 'york_cm[image_id]', 'Section Image', get_post_meta( $id, '_york_cm_image_id', true ) );
			?>
		</div>

		<?php /* ── VOICES ── */ ?>
		<div class="york-tab-panel" data-tab="voices">
			<?php
			york_field_text( 'york_voices[title]', 'Section Title', (string) get_post_meta( $id, '_york_voices_title', true ), 'Voices of our alumni' );
			york_field_textarea( 'york_voices[text]', 'Section Description', (string) get_post_meta( $id, '_york_voices_text', true ) );
			$voice_items = get_post_meta( $id, '_york_voice_items', true );
			if ( empty( $voice_items ) || ! is_array( $voice_items ) ) {
				$voice_items = array_fill( 0, 3, array( 'title' => '', 'desc' => '', 'video_url' => '', 'thumb_id' => '' ) );
			}
			york_render_repeater(
				'york_voice_items',
				'Video',
				$voice_items,
				function ( $i, array $item, bool $tpl ) {
					york_field_text( "york_voice_items[{$i}][title]", 'Title', $item['title'] ?? '' );
					york_field_textarea( "york_voice_items[{$i}][desc]", 'Description', $item['desc'] ?? '' );
					york_field_text( "york_voice_items[{$i}][video_url]", 'YouTube Embed URL', $item['video_url'] ?? '', 'https://www.youtube-nocookie.com/embed/...' );
					york_field_image( "york_voice_items[{$i}][thumb_id]", 'Thumbnail', $item['thumb_id'] ?? '' );
				}
			);
			?>
		</div>

		<?php /* ── TESTIMONIALS ── */ ?>
		<div class="york-tab-panel" data-tab="testimonials">
			<?php
			york_field_text( 'york_testimonials[title]', 'Section Title', (string) get_post_meta( $id, '_york_testimonials_title', true ), 'Alumni stories' );
			$testi_items = get_post_meta( $id, '_york_testi_items', true );
			if ( empty( $testi_items ) || ! is_array( $testi_items ) ) {
				$testi_items = array_fill( 0, 3, array( 'quote' => '', 'name' => '', 'designation' => '', 'image_id' => '' ) );
			}
			york_render_repeater(
				'york_testi_items',
				'Testimonial',
				$testi_items,
				function ( $i, array $item, bool $tpl ) {
					york_field_textarea( "york_testi_items[{$i}][quote]", 'Quote', $item['quote'] ?? '' );
					echo '<div class="york-field-row">';
					york_field_text( "york_testi_items[{$i}][name]", 'Name', $item['name'] ?? '' );
					york_field_text( "york_testi_items[{$i}][designation]", 'Designation', $item['designation'] ?? '' );
					echo '</div>';
					york_field_image( "york_testi_items[{$i}][image_id]", 'Photo', $item['image_id'] ?? '' );
				}
			);
			?>
		</div>

		<?php /* ── INSPIRE ── */ ?>
		<div class="york-tab-panel" data-tab="inspire">
			<?php
			york_field_text( 'york_inspire[title]', 'Title', (string) get_post_meta( $id, '_york_inspire_title', true ) );
			york_field_textarea( 'york_inspire[para1]', 'Paragraph 1', (string) get_post_meta( $id, '_york_inspire_para1', true ) );
			york_field_textarea( 'york_inspire[para2]', 'Paragraph 2 (optional)', (string) get_post_meta( $id, '_york_inspire_para2', true ) );
			york_field_link(
				'york_inspire[btn_url]',
				'york_inspire[btn_label]',
				'CTA Button',
				(string) get_post_meta( $id, '_york_inspire_btn_url', true ),
				(string) get_post_meta( $id, '_york_inspire_btn_label', true )
			);
			york_field_image( 'york_inspire[image_id]', 'Section Image', get_post_meta( $id, '_york_inspire_image_id', true ) );
			?>
		</div>

		<?php /* ── BOTTOM CTA BLOCKS ── */ ?>
		<div class="york-tab-panel" data-tab="blocks">
			<?php
			$block_items = get_post_meta( $id, '_york_block_items', true );
			if ( empty( $block_items ) || ! is_array( $block_items ) ) {
				$block_items = array_fill( 0, 3, array( 'title' => '', 'btn_url' => '', 'btn_label' => '', 'icon_id' => '' ) );
			}
			york_render_repeater(
				'york_block_items',
				'CTA Block',
				$block_items,
				function ( $i, array $item, bool $tpl ) {
					york_field_text( "york_block_items[{$i}][title]", 'Title', $item['title'] ?? '' );
					york_field_link(
						"york_block_items[{$i}][btn_url]",
						"york_block_items[{$i}][btn_label]",
						'Button',
						$item['btn_url'] ?? '',
						$item['btn_label'] ?? ''
					);
					york_field_image( "york_block_items[{$i}][icon_id]", 'Icon / Image', $item['icon_id'] ?? '' );
				}
			);
			?>
		</div>

	</div><!-- .york-tabs-wrap -->
	<?php
}

// ─────────────────────────────────────────────────────────────
// SAVE
// ─────────────────────────────────────────────────────────────

function york_save_all_meta_boxes( int $post_id ): void {
	if ( ! isset( $_POST['_york_home_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_york_home_nonce'] ) ), 'york_home_save_all' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Hero.
	if ( isset( $_POST['york_hero'] ) && is_array( $_POST['york_hero'] ) ) {
		$h = wp_unslash( $_POST['york_hero'] ); // phpcs:ignore
		update_post_meta( $post_id, '_york_hero_title',     sanitize_text_field( $h['title']     ?? '' ) );
		update_post_meta( $post_id, '_york_hero_caption',   sanitize_text_field( $h['caption']   ?? '' ) );
		update_post_meta( $post_id, '_york_hero_btn_label', sanitize_text_field( $h['btn_label'] ?? '' ) );
		update_post_meta( $post_id, '_york_hero_btn_url',   esc_url_raw( $h['btn_url']           ?? '' ) );
		update_post_meta( $post_id, '_york_hero_image_id',  absint( $h['image_id']               ?? 0 ) );
	}

	// USP section background.
	if ( isset( $_POST['york_usp'] ) && is_array( $_POST['york_usp'] ) ) {
		$u = wp_unslash( $_POST['york_usp'] ); // phpcs:ignore
		update_post_meta( $post_id, '_york_usp_bg', sanitize_text_field( $u['bg'] ?? 'bg-blue' ) );
	}

	// USP repeater items.
	if ( isset( $_POST['york_usp_items'] ) && is_array( $_POST['york_usp_items'] ) ) {
		$items = array();
		foreach ( wp_unslash( $_POST['york_usp_items'] ) as $item ) { // phpcs:ignore
			$items[] = array(
				'heading' => sanitize_text_field( $item['heading'] ?? '' ),
				'text'    => sanitize_textarea_field( $item['text'] ?? '' ),
				'icon_id' => absint( $item['icon_id'] ?? 0 ),
			);
		}
		update_post_meta( $post_id, '_york_usp_items', $items );
	}

	// Benefits title.
	if ( isset( $_POST['york_benefits'] ) && is_array( $_POST['york_benefits'] ) ) {
		$b = wp_unslash( $_POST['york_benefits'] ); // phpcs:ignore
		update_post_meta( $post_id, '_york_benefits_title', sanitize_text_field( $b['title'] ?? '' ) );
	}

	// Benefits repeater.
	if ( isset( $_POST['york_benefit_items'] ) && is_array( $_POST['york_benefit_items'] ) ) {
		$items = array();
		foreach ( wp_unslash( $_POST['york_benefit_items'] ) as $item ) { // phpcs:ignore
			$items[] = array(
				'heading' => sanitize_text_field( $item['heading'] ?? '' ),
				'text'    => sanitize_textarea_field( $item['text'] ?? '' ),
			);
		}
		update_post_meta( $post_id, '_york_benefit_items', $items );
	}

	// Content + Media.
	if ( isset( $_POST['york_cm'] ) && is_array( $_POST['york_cm'] ) ) {
		$cm = wp_unslash( $_POST['york_cm'] ); // phpcs:ignore
		update_post_meta( $post_id, '_york_cm_title',     sanitize_text_field( $cm['title']     ?? '' ) );
		update_post_meta( $post_id, '_york_cm_para1',     sanitize_textarea_field( $cm['para1'] ?? '' ) );
		update_post_meta( $post_id, '_york_cm_para2',     sanitize_textarea_field( $cm['para2'] ?? '' ) );
		update_post_meta( $post_id, '_york_cm_btn_label', sanitize_text_field( $cm['btn_label'] ?? '' ) );
		update_post_meta( $post_id, '_york_cm_btn_url',   esc_url_raw( $cm['btn_url']           ?? '' ) );
		update_post_meta( $post_id, '_york_cm_image_id',  absint( $cm['image_id']               ?? 0 ) );
	}

	// Voices section.
	if ( isset( $_POST['york_voices'] ) && is_array( $_POST['york_voices'] ) ) {
		$v = wp_unslash( $_POST['york_voices'] ); // phpcs:ignore
		update_post_meta( $post_id, '_york_voices_title', sanitize_text_field( $v['title']    ?? '' ) );
		update_post_meta( $post_id, '_york_voices_text',  sanitize_textarea_field( $v['text'] ?? '' ) );
	}

	// Voices repeater.
	if ( isset( $_POST['york_voice_items'] ) && is_array( $_POST['york_voice_items'] ) ) {
		$items = array();
		foreach ( wp_unslash( $_POST['york_voice_items'] ) as $item ) { // phpcs:ignore
			$items[] = array(
				'title'     => sanitize_text_field( $item['title']    ?? '' ),
				'desc'      => sanitize_textarea_field( $item['desc'] ?? '' ),
				'video_url' => esc_url_raw( $item['video_url']        ?? '' ),
				'thumb_id'  => absint( $item['thumb_id']              ?? 0 ),
			);
		}
		update_post_meta( $post_id, '_york_voice_items', $items );
	}

	// Testimonials title.
	if ( isset( $_POST['york_testimonials'] ) && is_array( $_POST['york_testimonials'] ) ) {
		$t = wp_unslash( $_POST['york_testimonials'] ); // phpcs:ignore
		update_post_meta( $post_id, '_york_testimonials_title', sanitize_text_field( $t['title'] ?? '' ) );
	}

	// Testimonials repeater.
	if ( isset( $_POST['york_testi_items'] ) && is_array( $_POST['york_testi_items'] ) ) {
		$items = array();
		foreach ( wp_unslash( $_POST['york_testi_items'] ) as $item ) { // phpcs:ignore
			$items[] = array(
				'quote'       => sanitize_textarea_field( $item['quote']   ?? '' ),
				'name'        => sanitize_text_field( $item['name']        ?? '' ),
				'designation' => sanitize_text_field( $item['designation'] ?? '' ),
				'image_id'    => absint( $item['image_id']                 ?? 0 ),
			);
		}
		update_post_meta( $post_id, '_york_testi_items', $items );
	}

	// Inspire.
	if ( isset( $_POST['york_inspire'] ) && is_array( $_POST['york_inspire'] ) ) {
		$ins = wp_unslash( $_POST['york_inspire'] ); // phpcs:ignore
		update_post_meta( $post_id, '_york_inspire_title',     sanitize_text_field( $ins['title']     ?? '' ) );
		update_post_meta( $post_id, '_york_inspire_para1',     sanitize_textarea_field( $ins['para1'] ?? '' ) );
		update_post_meta( $post_id, '_york_inspire_para2',     sanitize_textarea_field( $ins['para2'] ?? '' ) );
		update_post_meta( $post_id, '_york_inspire_btn_label', sanitize_text_field( $ins['btn_label'] ?? '' ) );
		update_post_meta( $post_id, '_york_inspire_btn_url',   esc_url_raw( $ins['btn_url']           ?? '' ) );
		update_post_meta( $post_id, '_york_inspire_image_id',  absint( $ins['image_id']               ?? 0 ) );
	}

	// Bottom CTA blocks repeater.
	if ( isset( $_POST['york_block_items'] ) && is_array( $_POST['york_block_items'] ) ) {
		$items = array();
		foreach ( wp_unslash( $_POST['york_block_items'] ) as $item ) { // phpcs:ignore
			$items[] = array(
				'title'     => sanitize_text_field( $item['title']     ?? '' ),
				'btn_label' => sanitize_text_field( $item['btn_label'] ?? '' ),
				'btn_url'   => esc_url_raw( $item['btn_url']           ?? '' ),
				'icon_id'   => absint( $item['icon_id']                ?? 0 ),
			);
		}
		update_post_meta( $post_id, '_york_block_items', $items );
	}
}
add_action( 'save_post_page', 'york_save_all_meta_boxes' );
