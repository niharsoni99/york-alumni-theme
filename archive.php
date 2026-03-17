<?php
/**
 * The archive template.
 *
 * Used for category, tag, author, date archives.
 * The event CPT archive uses the plugin's own template.
 *
 * @package YorkAlumni
 */

get_header();
?>

<div class="york-custom-section">
	<div class="container">

		<header class="archive-header">
			<?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="archive-description p-large">', '</div>' ); ?>
		</header>

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
