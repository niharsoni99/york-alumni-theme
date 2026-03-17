<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php york_svg_sprite(); ?>

<a class="skip-link screen-reader-text" href="#main-content">
	<?php esc_html_e( 'Skip to content', 'york-alumni' ); ?>
</a>

<header class="site-header" role="banner">
	<div class="container">
		<div class="header-inner">

			<!-- Site Branding -->
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
				<?php endif; ?>
			</div>

			<!-- Primary Navigation — Custom Walker, all items from WP Appearance > Menus -->
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<nav
					class="main-navigation"
					id="main-navigation"
					role="navigation"
					aria-label="<?php esc_attr_e( 'Primary Navigation', 'york-alumni' ); ?>"
				>
					<?php
					wp_nav_menu( array(
						'theme_location'  => 'primary',
						'menu_id'         => 'primary-menu',
						'menu_class'      => 'primary-menu',
						'container'       => false,
						'walker'          => new York_Walker_Nav_Menu(),
						'fallback_cb'     => false,
						'items_wrap'      => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
					) );
					?>
				</nav>
			<?php endif; ?>

			<!-- Mobile Hamburger — vanilla JS only, no jQuery -->
			<button
				class="nav-toggle"
				aria-controls="main-navigation"
				aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Toggle Navigation', 'york-alumni' ); ?>"
			>
				<span></span>
				<span></span>
				<span></span>
			</button>

		</div><!-- .header-inner -->
	</div><!-- .container -->
</header><!-- .site-header -->

<main id="main-content" class="site-main" role="main">
