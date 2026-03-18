<?php
/**
 * USP / Statistics Meta Box.
 *
 * Renders a two-column post selector on the page edit screen:
 *   Left  — searchable list of all 'statistics' CPT posts (checkboxes)
 *   Right — selected posts (drag to reorder, remove button, max 4)
 *
 * Selected post IDs are saved as an ordered array in post meta
 * under the key '_york_usp_post_ids'.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ─────────────────────────────────────────────────────────────
// REGISTER META BOX
// ─────────────────────────────────────────────────────────────

function york_register_usp_meta_box(): void {
	add_meta_box(
		'york_usp_statistics',
		__( '📊 USP — Key Statistics', 'york-alumni' ),
		'york_render_usp_statistics_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'york_register_usp_meta_box' );

// ─────────────────────────────────────────────────────────────
// ENQUEUE META BOX ASSETS
// ─────────────────────────────────────────────────────────────

function york_usp_meta_box_assets( string $hook ): void {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	// Inline CSS.
	wp_add_inline_style( 'wp-admin', '
.york-usp-selector { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 8px; }
.york-usp-available, .york-usp-selected { border: 1px solid #dcdcde; border-radius: 4px; overflow: hidden; }
.york-usp-panel-header {
	background: #f6f7f7; padding: 10px 14px;
	border-bottom: 1px solid #dcdcde;
	display: flex; align-items: center; justify-content: space-between;
}
.york-usp-panel-header strong { font-size: 13px; color: #1d2327; }
.york-usp-count {
	background: #2271b1; color: #fff;
	border-radius: 10px; padding: 2px 8px;
	font-size: 11px; font-weight: 700;
}
.york-usp-count.is-max { background: #d63638; }
.york-usp-search {
	padding: 8px 10px;
	border-bottom: 1px solid #dcdcde;
	background: #fff;
}
.york-usp-search input {
	width: 100%; padding: 6px 10px;
	border: 1px solid #8c8f94; border-radius: 3px; font-size: 13px;
}
.york-usp-list { max-height: 260px; overflow-y: auto; }
.york-usp-available-item {
	display: flex; align-items: center; gap: 10px;
	padding: 9px 14px; border-bottom: 1px solid #f0f0f0;
	cursor: pointer; transition: background .1s;
}
.york-usp-available-item:hover { background: #f0f6fc; }
.york-usp-available-item.is-selected { background: #f0f6fc; opacity: .6; pointer-events: none; }
.york-usp-available-item input[type="checkbox"] { margin: 0; cursor: pointer; }
.york-usp-available-item label { font-size: 13px; cursor: pointer; flex: 1; margin: 0; font-weight: 400; }
.york-usp-empty { padding: 20px 14px; text-align: center; color: #8c8f94; font-size: 13px; }
.york-usp-selected-list { min-height: 100px; padding: 8px; }
.york-usp-selected-item {
	display: flex; align-items: center; gap: 8px;
	padding: 8px 10px; margin-bottom: 6px;
	background: #fff; border: 1px solid #dcdcde;
	border-radius: 4px; cursor: grab;
}
.york-usp-selected-item:last-child { margin-bottom: 0; }
.york-usp-drag-handle { color: #8c8f94; cursor: grab; font-size: 16px; flex-shrink: 0; }
.york-usp-selected-title { flex: 1; font-size: 13px; font-weight: 600; }
.york-usp-remove-btn {
	background: none; border: none; color: #b32d2e;
	cursor: pointer; font-size: 16px; line-height: 1;
	padding: 0 2px; flex-shrink: 0;
}
.york-usp-remove-btn:hover { color: #8a1f1f; }
.york-usp-selected-placeholder {
	display: flex; align-items: center; justify-content: center;
	height: 80px; color: #8c8f94; font-size: 13px;
	border: 2px dashed #dcdcde; border-radius: 4px; margin: 8px;
}
.york-usp-max-notice {
	padding: 8px 14px; background: #fcf9e8;
	border-top: 1px solid #dcdcde; font-size: 12px; color: #856404;
	display: none;
}
.york-usp-bg-row { margin-bottom: 14px; display: flex; align-items: center; gap: 12px; }
.york-usp-bg-row label { font-weight: 600; font-size: 12px; text-transform: uppercase; color: #50575e; }
.york-usp-bg-row select { padding: 4px 8px; border: 1px solid #8c8f94; border-radius: 3px; font-size: 13px; }
	' );

	// Inline JS.
	wp_add_inline_script( 'jquery', '
jQuery(function($){
	var MAX = 4;

	function getSelectedIds() {
		return $(".york-usp-selected-item").map(function(){ return $(this).data("id"); }).get();
	}

	function updateCounts() {
		var count = $(".york-usp-selected-item").length;
		var $badge = $(".york-usp-selected-count");
		$badge.text(count);
		$badge.toggleClass("is-max", count >= MAX);
		$(".york-usp-max-notice").toggle(count >= MAX);
		// Update hidden input.
		$("input[name=york_usp_post_ids]").val(getSelectedIds().join(","));
		// Mark available items.
		var ids = getSelectedIds();
		$(".york-usp-available-item").each(function(){
			var id = parseInt($(this).data("id"));
			var checked = ids.indexOf(id) > -1;
			$(this).toggleClass("is-selected", checked);
			$(this).find("input[type=checkbox]").prop("checked", checked);
		});
		// Show/hide placeholder.
		if (count === 0) {
			$(".york-usp-selected-placeholder").show();
		} else {
			$(".york-usp-selected-placeholder").hide();
		}
	}

	function addItem(id, title) {
		if (getSelectedIds().length >= MAX) return;
		if (getSelectedIds().indexOf(parseInt(id)) > -1) return;
		var html = \'<div class="york-usp-selected-item" data-id="\' + id + \'">\' +
			\'<span class="york-usp-drag-handle" title="Drag to reorder">⠿</span>\' +
			\'<span class="york-usp-selected-title">\' + title + \'</span>\' +
			\'<button type="button" class="york-usp-remove-btn" title="Remove">✕</button>\' +
		\'</div>\';
		$(".york-usp-selected-list").append(html);
		updateCounts();
	}

	// Checkbox click — add/remove.
	$(document).on("change", ".york-usp-available-item input[type=checkbox]", function(){
		var $item = $(this).closest(".york-usp-available-item");
		var id    = $item.data("id");
		var title = $item.find("label").text().trim();
		if ($(this).is(":checked")) {
			addItem(id, title);
		} else {
			$(".york-usp-selected-item[data-id=\'" + id + "\']").remove();
			updateCounts();
		}
	});

	// Row click — same as checkbox.
	$(document).on("click", ".york-usp-available-item label", function(e){
		e.preventDefault();
		var $cb = $(this).closest(".york-usp-available-item").find("input[type=checkbox]");
		$cb.prop("checked", !$cb.prop("checked")).trigger("change");
	});

	// Remove button.
	$(document).on("click", ".york-usp-remove-btn", function(){
		var id = $(this).closest(".york-usp-selected-item").data("id");
		$(this).closest(".york-usp-selected-item").remove();
		updateCounts();
	});

	// Search filter.
	$(document).on("input", ".york-usp-search-input", function(){
		var q = $(this).val().toLowerCase();
		$(".york-usp-available-item").each(function(){
			var title = $(this).find("label").text().toLowerCase();
			$(this).toggle(title.indexOf(q) > -1);
		});
		var visible = $(".york-usp-available-item:visible").length;
		$(".york-usp-no-results").toggle(visible === 0);
	});

	// Drag to reorder (native HTML5 drag).
	var $list = $(".york-usp-selected-list");
	var dragSrc = null;

	$list.on("dragstart", ".york-usp-selected-item", function(e){
		dragSrc = this;
		e.originalEvent.dataTransfer.effectAllowed = "move";
		$(this).css("opacity", "0.5");
	});
	$list.on("dragend", ".york-usp-selected-item", function(){
		$(this).css("opacity", "1");
		updateCounts();
	});
	$list.on("dragover", ".york-usp-selected-item", function(e){
		e.preventDefault();
		e.originalEvent.dataTransfer.dropEffect = "move";
		if (this !== dragSrc) {
			var rect = this.getBoundingClientRect();
			var mid  = rect.top + rect.height / 2;
			if (e.originalEvent.clientY < mid) {
				$(dragSrc).insertBefore(this);
			} else {
				$(dragSrc).insertAfter(this);
			}
		}
	});
	$list.on("dragenter", ".york-usp-selected-item", function(e){ e.preventDefault(); });

	// Make items draggable.
	$(document).on("mouseenter", ".york-usp-selected-item", function(){
		$(this).attr("draggable", "true");
	});

	// Init on load.
	updateCounts();
});
	' );
}
add_action( 'admin_enqueue_scripts', 'york_usp_meta_box_assets' );

// ─────────────────────────────────────────────────────────────
// RENDER META BOX
// ─────────────────────────────────────────────────────────────

function york_render_usp_statistics_meta_box( \WP_Post $post ): void {
	wp_nonce_field( 'york_usp_stats_save', '_york_usp_stats_nonce' );

	// Get all published statistics posts.
	$all_stats = get_posts( array(
		'post_type'      => 'statistics',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );

	// Get currently selected IDs (ordered).
	$saved_ids_raw = get_post_meta( $post->ID, '_york_usp_post_ids', true );
	$selected_ids  = ! empty( $saved_ids_raw )
		? array_filter( array_map( 'absint', explode( ',', $saved_ids_raw ) ) )
		: array();

	// Build selected posts in saved order.
	$selected_posts = array();
	foreach ( $selected_ids as $sid ) {
		foreach ( $all_stats as $stat ) {
			if ( (int) $stat->ID === $sid ) {
				$selected_posts[] = $stat;
				break;
			}
		}
	}

	// Section background setting.
	$section_bg = get_post_meta( $post->ID, '_york_usp_bg', true ) ?: 'bg-blue';
	?>

	<!-- Background selector -->
	<div class="york-usp-bg-row">
		<label for="york_usp_bg_select"><?php esc_html_e( 'Section Background', 'york-alumni' ); ?></label>
		<select id="york_usp_bg_select" name="york_usp_bg">
			<?php
			$bgs = array(
				'bg-blue'  => __( 'Blue', 'york-alumni' ),
				'bg-green' => __( 'Green', 'york-alumni' ),
				'bg-grey'  => __( 'Grey', 'york-alumni' ),
				''         => __( 'White', 'york-alumni' ),
			);
			foreach ( $bgs as $val => $label ) :
				?>
				<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $section_bg, $val ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</div>

	<p style="font-size:13px;color:#50575e;margin-bottom:8px;">
		<?php esc_html_e( 'Select statistics posts to display. Click checkboxes to add/remove, then drag to reorder.', 'york-alumni' ); ?>
	</p>

	<!-- Hidden input stores ordered IDs -->
	<input
		type="hidden"
		name="york_usp_post_ids"
		value="<?php echo esc_attr( implode( ',', $selected_ids ) ); ?>"
	/>

	<div class="york-usp-selector">

		<!-- LEFT: Available Posts -->
		<div class="york-usp-available">
			<div class="york-usp-panel-header">
				<strong><?php esc_html_e( 'Available Statistics', 'york-alumni' ); ?></strong>
				<span class="york-usp-count"><?php echo esc_html( count( $all_stats ) ); ?></span>
			</div>
			<div class="york-usp-search">
				<input
					type="text"
					class="york-usp-search-input"
					placeholder="<?php esc_attr_e( 'Search statistics...', 'york-alumni' ); ?>"
				/>
			</div>
			<div class="york-usp-list">
				<?php if ( empty( $all_stats ) ) : ?>
					<div class="york-usp-empty">
						<?php esc_html_e( 'No statistics found. Create some first.', 'york-alumni' ); ?>
					</div>
				<?php else : ?>
					<?php foreach ( $all_stats as $stat ) :
						$is_selected = in_array( (int) $stat->ID, $selected_ids, true );
						?>
						<div
							class="york-usp-available-item <?php echo $is_selected ? 'is-selected' : ''; ?>"
							data-id="<?php echo esc_attr( $stat->ID ); ?>"
						>
							<input
								type="checkbox"
								id="york_stat_<?php echo esc_attr( $stat->ID ); ?>"
								<?php checked( $is_selected ); ?>
							/>
							<label for="york_stat_<?php echo esc_attr( $stat->ID ); ?>">
								<?php echo esc_html( get_the_title( $stat ) ); ?>
							</label>
						</div>
					<?php endforeach; ?>
					<div class="york-usp-no-results york-usp-empty" style="display:none;">
						<?php esc_html_e( 'No results found.', 'york-alumni' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- RIGHT: Selected Posts -->
		<div class="york-usp-selected">
			<div class="york-usp-panel-header">
				<strong><?php esc_html_e( 'Selected Statistics (Drag to Reorder)', 'york-alumni' ); ?></strong>
				<span class="york-usp-count york-usp-selected-count <?php echo count( $selected_ids ) >= 4 ? 'is-max' : ''; ?>">
					<?php echo esc_html( count( $selected_ids ) ); ?>
				</span>
			</div>
			<div class="york-usp-selected-list">
				<?php if ( empty( $selected_posts ) ) : ?>
					<div class="york-usp-selected-placeholder">
						<?php esc_html_e( 'No statistics selected yet.', 'york-alumni' ); ?>
					</div>
				<?php else : ?>
					<?php foreach ( $selected_posts as $stat ) : ?>
						<div
							class="york-usp-selected-item"
							data-id="<?php echo esc_attr( $stat->ID ); ?>"
							draggable="true"
						>
							<span class="york-usp-drag-handle" title="<?php esc_attr_e( 'Drag to reorder', 'york-alumni' ); ?>">⠿</span>
							<span class="york-usp-selected-title"><?php echo esc_html( get_the_title( $stat ) ); ?></span>
							<button type="button" class="york-usp-remove-btn" title="<?php esc_attr_e( 'Remove', 'york-alumni' ); ?>">✕</button>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div class="york-usp-max-notice" <?php echo count( $selected_ids ) < 4 ? 'style="display:none;"' : ''; ?>>
				<?php esc_html_e( 'Maximum 4 statistics can be selected.', 'york-alumni' ); ?>
			</div>
		</div>

	</div><!-- .york-usp-selector -->
	<?php
}

// ─────────────────────────────────────────────────────────────
// SAVE
// ─────────────────────────────────────────────────────────────

function york_save_usp_statistics_meta( int $post_id ): void {
	if ( ! isset( $_POST['_york_usp_stats_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_york_usp_stats_nonce'] ) ), 'york_usp_stats_save' ) ) {
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

	// Save section background.
	if ( isset( $_POST['york_usp_bg'] ) ) {
		$allowed = array( 'bg-blue', 'bg-green', 'bg-grey', '' );
		$bg      = sanitize_text_field( wp_unslash( $_POST['york_usp_bg'] ) );
		update_post_meta( $post_id, '_york_usp_bg', in_array( $bg, $allowed, true ) ? $bg : 'bg-blue' );
	}

	// Save ordered post IDs (max 4).
	if ( isset( $_POST['york_usp_post_ids'] ) ) {
		$raw = sanitize_text_field( wp_unslash( $_POST['york_usp_post_ids'] ) );
		if ( empty( $raw ) ) {
			update_post_meta( $post_id, '_york_usp_post_ids', '' );
			return;
		}
		$ids = array_slice(
			array_filter( array_map( 'absint', explode( ',', $raw ) ) ),
			0,
			4 // Hard limit — max 4.
		);
		update_post_meta( $post_id, '_york_usp_post_ids', implode( ',', $ids ) );
	}
}
add_action( 'save_post_page', 'york_save_usp_statistics_meta' );