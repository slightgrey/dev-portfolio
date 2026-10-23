<?php
/**
 * Not found.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="flex min-h-[60vh] flex-col justify-center px-6 md:px-10">
	<p class="text-sm font-light uppercase tracking-[0.5rem]"><?php esc_html_e( 'Page not found', 'dev-portfolio' ); ?></p>
	<h1 class="banner-text-responsive mt-12 uppercase">404</h1>
	<p class="mt-12 text-2xl font-light uppercase">
		<a class="underline decoration-gold underline-offset-8" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'dev-portfolio' ); ?></a>
	</p>
</div>
<?php
get_footer();
