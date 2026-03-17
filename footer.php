<?php
/**
 * Site Footer — copyright + developed by only.
 *
 * @package YorkAlumni
 */
?>
</main><!-- #main-content -->

<footer class="site-footer" role="contentinfo">
	<div class="container">
		<div class="footer-bottom">
			<p>
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
				<?php esc_html_e( 'All rights reserved.', 'york-alumni' ); ?>
			</p>
			<p><?php esc_html_e( 'Developed by TPots Developer', 'york-alumni' ); ?></p>
		</div>
	</div>
</footer><!-- .site-footer -->

<?php wp_footer(); ?>
</body>
</html>
