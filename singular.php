<?php
/**
 * The singular template — used for posts, pages, and CPTs
 * that don't have a dedicated template.
 *
 * @package YorkAlumni
 */

get_header();
?>

<div class="york-custom-section">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();

			// Use the appropriate template part based on post type.
			get_template_part( 'template-parts/content', get_post_type() );

		endwhile;
		?>
	</div>
</div>

<?php
get_footer();
