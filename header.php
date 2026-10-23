<?php
/**
 * Document head and, on inner pages, the site header.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'dev-portfolio' ); ?></a>
<?php if ( ! is_front_page() ) : ?>
	<header class="site-header flex flex-wrap items-center justify-between gap-6 px-6 py-6 md:px-10">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-2xl uppercase tracking-widest" rel="home">
			<?php
			if ( has_custom_logo() ) {
				echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'medium', false, array( 'class' => 'h-10 w-auto' ) );
			} else {
				bloginfo( 'name' );
			}
			?>
		</a>
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => 'nav',
				'menu_class'     => 'flex flex-wrap gap-x-6 gap-y-2 text-sm font-light uppercase tracking-widest',
				'fallback_cb'    => false,
				'depth'          => 1,
			)
		);
		?>
	</header>
<?php endif; ?>
<main id="content">
