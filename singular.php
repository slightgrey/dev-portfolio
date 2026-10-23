<?php
/**
 * Single posts, pages and projects.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'px-6 md:px-10' ); ?>>
		<header class="py-10">
			<?php if ( is_singular( 'post' ) ) : ?>
				<p class="text-sm font-light uppercase tracking-[0.5rem]"><?php echo esc_html( get_the_date() ); ?></p>
			<?php endif; ?>
			<h1 class="mt-6 text-5xl uppercase md:text-7xl"><?php the_title(); ?></h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="mb-12 overflow-hidden rounded-3xl">
				<?php the_post_thumbnail( 'vincent-project', array( 'class' => 'h-auto w-full object-cover' ) ); ?>
			</figure>
		<?php endif; ?>

		<?php if ( is_singular( 'vincent_project' ) ) : ?>
			<?php
			$vincent_frameworks = (array) get_post_meta( get_the_ID(), '_vincent_frameworks', true );
			$vincent_url        = get_post_meta( get_the_ID(), '_vincent_url', true );
			?>
			<div class="mb-10 flex flex-wrap gap-x-5 text-sm uppercase leading-loose">
				<?php foreach ( array_filter( $vincent_frameworks ) as $vincent_framework ) : ?>
					<span><?php echo esc_html( $vincent_framework ); ?></span>
				<?php endforeach; ?>
			</div>
			<div class="vincent-prose max-w-3xl text-xl font-light"><?php the_excerpt(); ?></div>
			<?php if ( $vincent_url ) : ?>
				<p class="mt-8"><a class="text-2xl uppercase underline decoration-gold underline-offset-8" href="<?php echo esc_url( $vincent_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit project', 'dev-portfolio' ); ?> ↗</a></p>
			<?php endif; ?>
		<?php else : ?>
			<div class="vincent-prose max-w-3xl text-lg font-light">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
		<?php endif; ?>

		<?php
		if ( comments_open() || get_comments_number() ) {
			echo '<div class="vincent-prose mt-16 max-w-3xl">';
			comments_template();
			echo '</div>';
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
