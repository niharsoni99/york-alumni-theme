<?php
/**
 * The main template file — WordPress template hierarchy fallback.
 *
 * Used when no more specific template is found.
 * For posts archive, search results, etc.
 *
 * @package YorkAlumni
 */

get_header();
?>

<div class="york-custom-section">
	<div class="container">

		<?php if ( have_posts() ) : ?>

			<div class="york-custom-block-group york-custom-block-two">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );
				endwhile;
				?>
			</div>

			<?php the_posts_pagination(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</div>
</div>

<?php
get_footer();
