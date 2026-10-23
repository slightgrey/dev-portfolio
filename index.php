<?php
/**
 * Fallback for blog, archives and search.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="px-6 md:px-10">
	<h1 class="py-10 text-5xl uppercase md:text-7xl">
		<?php
		if ( is_search() ) {
			/* translators: %s: search query. */
			printf( esc_html__( 'Search: %s', 'dev-portfolio' ), esc_html( get_search_query() ) );
		} elseif ( is_archive() ) {
			echo wp_kses_post( get_the_archive_title() );
		} else {
			single_post_title();
		}
		?>
	</h1>

	<?php if ( have_posts() ) : ?>
		<div class="border-t-2 border-black/80">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'group border-b-2 border-black/80 py-6' ); ?>>
					<a href="<?php the_permalink(); ?>" class="flex items-baseline justify-between gap-6">
						<h2 class="text-[26px] leading-none transition-all duration-500 group-hover:pl-2 lg:text-[32px]"><?php the_title(); ?></h2>
						<time class="shrink-0 text-sm uppercase tracking-widest text-black/50" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</a>
					<div class="mt-3 max-w-3xl font-light text-black/70"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="vincent-pagination mt-10 font-light uppercase tracking-widest">
			<?php the_posts_pagination(); ?>
		</div>
	<?php else : ?>
		<p class="text-xl font-light"><?php esc_html_e( 'Nothing found.', 'dev-portfolio' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
