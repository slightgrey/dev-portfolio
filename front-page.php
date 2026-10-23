<?php
/**
 * Front page: mount point for the React portfolio, with a no-JS fallback.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();

$vincent_data = vincent_get_portfolio_data();
$vincent_show = $vincent_data['sections'];
?>
<div id="vincent-app"></div>
<noscript>
	<div class="px-6 py-16 md:px-10">
		<?php if ( $vincent_show['hero'] ) : ?>
			<p class="text-sm uppercase tracking-[0.5rem]"><?php echo esc_html( $vincent_data['hero']['subTitle'] ); ?></p>
			<h1 class="banner-text-responsive mt-10 uppercase"><?php echo esc_html( $vincent_data['hero']['title'] ); ?></h1>
			<p class="mt-10 text-2xl font-light uppercase"><?php echo nl2br( esc_html( $vincent_data['hero']['text'] ), false ); ?></p>
		<?php endif; ?>

		<?php if ( $vincent_show['services'] ) : ?>
			<h2 class="mt-20 text-5xl uppercase"><?php echo esc_html( $vincent_data['servicesHeader']['title'] ); ?></h2>
			<?php foreach ( $vincent_data['services'] as $vincent_service ) : ?>
				<h3 class="mt-8 text-3xl"><?php echo esc_html( $vincent_service['title'] ); ?></h3>
				<p class="text-xl font-light"><?php echo esc_html( $vincent_service['description'] ); ?></p>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( $vincent_show['about'] ) : ?>
			<h2 class="mt-20 text-5xl uppercase"><?php echo esc_html( $vincent_data['about']['title'] ); ?></h2>
			<p class="mt-6 text-xl font-light"><?php echo nl2br( esc_html( $vincent_data['about']['body'] ), false ); ?></p>
		<?php endif; ?>

		<?php if ( $vincent_show['works'] ) : ?>
			<h2 class="mt-20 text-5xl uppercase"><?php echo esc_html( $vincent_data['worksHeader']['title'] ); ?></h2>
			<ul class="mt-6 text-2xl">
				<?php foreach ( $vincent_data['projects'] as $vincent_project ) : ?>
					<li>
						<?php if ( $vincent_project['href'] ) : ?>
							<a href="<?php echo esc_url( $vincent_project['href'] ); ?>"><?php echo esc_html( $vincent_project['name'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $vincent_project['name'] ); ?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $vincent_show['contact'] ) : ?>
			<h2 class="mt-20 text-5xl uppercase"><?php echo esc_html( $vincent_data['contact']['title'] ); ?></h2>
			<?php if ( $vincent_data['contact']['email'] ) : ?>
				<p class="mt-6 text-2xl"><a href="<?php echo esc_url( 'mailto:' . $vincent_data['contact']['email'] ); ?>"><?php echo esc_html( $vincent_data['contact']['email'] ); ?></a></p>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</noscript>
<?php
get_footer();
