<?php
/**
 * Template Part — Generic post card for index/archive.
 *
 * @package YorkAlumni
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'york-custom-block' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'york-card', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

	<div class="p-large"><?php the_excerpt(); ?></div>

	<a href="<?php the_permalink(); ?>" class="cta cta-transparant">
		<span><?php esc_html_e( 'Read more', 'york-alumni' ); ?></span>
	</a>

</article>
