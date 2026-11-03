<?php
/**
 * No-JS fallback for the Neumorphism front page. No e-mail address: that design only offers its
 * contact form. Kept out of front-page.php so only src/neumorphism/neumorphism.css scans its classes.
 *
 * @package Dev_Portfolio
 *
 * @var array $args { @type array $data Output of vincent_get_portfolio_data(). }
 */

defined( 'ABSPATH' ) || exit;

$vincent_data = $args['data'];
$vincent_show = $vincent_data['sections'];
$vincent_nm   = $vincent_data['neumorphism'];
?>
<noscript>
	<div class="mx-auto max-w-site px-4 py-16 md:px-6">
		<?php if ( $vincent_show['hero'] ) : ?>
			<p class="nm-label"><?php echo esc_html( $vincent_nm['hero']['eyebrow'] ); ?></p>
			<h1 class="mt-4 text-[40px] leading-[1.1] font-bold md:text-[64px]"><?php echo esc_html( $vincent_nm['hero']['title'] ); ?></h1>
			<p class="mt-6 text-ink-soft"><?php echo esc_html( $vincent_nm['hero']['lead'] ); ?></p>
		<?php endif; ?>

		<?php if ( $vincent_show['services'] ) : ?>
			<?php foreach ( $vincent_data['services'] as $vincent_service ) : ?>
				<h2 class="mt-12 text-2xl font-bold"><?php echo esc_html( $vincent_service['title'] ); ?></h2>
				<p class="mt-2 text-ink-soft"><?php echo esc_html( $vincent_service['description'] ); ?></p>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( $vincent_show['contact'] ) : ?>
			<h2 class="mt-16 text-[28px] font-bold"><?php echo esc_html( $vincent_nm['contact']['title'] ); ?></h2>
			<p class="mt-2 text-ink-soft"><?php esc_html_e( 'Turn on JavaScript to use the contact form, or find me here:', 'dev-portfolio' ); ?></p>
			<ul class="mt-4 flex flex-wrap gap-4">
				<?php foreach ( $vincent_data['socials'] as $vincent_social ) : ?>
					<li><a class="font-semibold underline underline-offset-4" href="<?php echo esc_url( $vincent_social['href'] ?? '' ); ?>"><?php echo esc_html( $vincent_social['name'] ?? '' ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</noscript>
