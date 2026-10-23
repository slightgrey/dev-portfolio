<?php
/**
 * Developer Portfolio theme bootstrap.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

define( 'VINCENT_VERSION', '1.1.0' );
define( 'VINCENT_DIR', get_template_directory() );
define( 'VINCENT_URI', get_template_directory_uri() );

require VINCENT_DIR . '/inc/setup.php';
require VINCENT_DIR . '/inc/post-types.php';
require VINCENT_DIR . '/inc/options.php';
require VINCENT_DIR . '/inc/data.php';
require VINCENT_DIR . '/inc/vite.php';
require VINCENT_DIR . '/inc/seed.php';
require VINCENT_DIR . '/inc/minify.php';
require VINCENT_DIR . '/inc/cleanup.php';
