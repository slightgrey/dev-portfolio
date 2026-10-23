<?php
/**
 * Theme supports, menus and image sizes.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'vincent_setup' );

/**
 * Register theme features.
 */
function vincent_setup() {
	load_theme_textdomain( 'dev-portfolio', VINCENT_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	add_image_size( 'vincent-project', 1920, 1080, false );

	register_nav_menus(
		array(
			'primary' => __( 'Primary menu (inner pages)', 'dev-portfolio' ),
		)
	);
}

add_filter( 'body_class', 'vincent_body_class' );

/**
 * Flag the React front page so fallback styles can stay out of its way.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function vincent_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'vincent-app-page';
	}
	return $classes;
}
