<?php
/**
 * Front Page Template.
 *
 * Displays when "Front page displays" is set to a static page
 * in Settings > Reading. All content is pulled from meta boxes.
 *
 * Sections:
 *  1. Hero Banner
 *  2. Intro Text
 *  3. USP Blocks
 *  4. Alumni Benefits
 *  5. Content + Media
 *  6. Voices of Alumni
 *  7. Alumni Stories (Testimonials Slider)
 *  8. Inspire Section
 *  9. Bottom CTA Blocks
 * 10. Event Display (from plugin CPT)
 *
 * @package YorkAlumni
 */

get_header();

// Current page ID for all meta lookups.
$page_id = get_the_ID();
?>

<?php
// ─────────────────────────────────────────────────────────────
// SECTION 1 — HERO BANNER
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/hero', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 2 — INTRO PARAGRAPHS (from page content)
// ─────────────────────────────────────────────────────────────
if ( have_posts() ) :
	the_post();
	if ( ! empty( get_the_content() ) ) :
		?>
		<section class="york-cms-custom-section york-custom-section">
			<div class="container">
				<div class="york-intro-content">
					<?php the_content(); ?>
				</div>
			</div>
		</section>
		<?php
	endif;
endif;

// ─────────────────────────────────────────────────────────────
// SECTION 3 — USP BLOCKS
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/usp', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 4 — ALUMNI BENEFITS
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/benefits', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 5 — CONTENT + MEDIA
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/content-media', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 6 — VOICES OF ALUMNI
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/voices', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 7 — ALUMNI STORIES (TESTIMONIALS SLIDER)
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/testimonials', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 8 — INSPIRE SECTION
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/inspire', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 9 — BOTTOM CTA BLOCKS
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/bottom-blocks', null, array( 'page_id' => $page_id ) );

// ─────────────────────────────────────────────────────────────
// SECTION 10 — UPCOMING EVENTS (from plugin CPT)
// ─────────────────────────────────────────────────────────────
get_template_part( 'template-parts/home/events', null, array( 'page_id' => $page_id ) );
?>

<?php get_footer(); ?>
