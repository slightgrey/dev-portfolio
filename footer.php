<?php
/**
 * Closes main and, on inner pages, prints the site footer.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;
?>
</main>
<?php if ( ! is_front_page() ) : ?>
	<footer class="site-footer mt-24 rounded-t-4xl bg-black px-6 py-16 font-light text-white md:px-10">
		<div class="flex flex-col justify-between gap-10 md:flex-row">
			<?php $vincent_email = vincent_option( 'email' ); ?>
			<?php if ( $vincent_email ) : ?>
				<div>
					<p class="uppercase tracking-wider text-white/50"><?php esc_html_e( 'E-mail', 'dev-portfolio' ); ?></p>
					<a class="text-xl tracking-widest hover:text-gold" href="<?php echo esc_url( 'mailto:' . antispambot( $vincent_email ) ); ?>"><?php echo esc_html( antispambot( $vincent_email ) ); ?></a>
				</div>
			<?php endif; ?>
			<div>
				<p class="uppercase tracking-wider text-white/50"><?php esc_html_e( 'Social Media', 'dev-portfolio' ); ?></p>
				<div class="flex flex-wrap gap-x-3">
					<?php foreach ( (array) vincent_option( 'socials' ) as $vincent_social ) : ?>
						<a class="text-sm uppercase leading-loose tracking-widest hover:text-gold" href="<?php echo esc_url( $vincent_social['href'] ?? '' ); ?>" target="_blank" rel="noopener">{ <?php echo esc_html( $vincent_social['name'] ?? '' ); ?> }</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<p class="mt-16 text-sm text-white/40">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	</footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
