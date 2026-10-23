<?php
/**
 * One-time demo content so the front page isn't empty after activation.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_switch_theme', 'vincent_seed_demo_content' );
// Fallback in case the one-shot after_switch_theme request was missed.
add_action( 'admin_init', 'vincent_seed_demo_content' );

/**
 * Create demo services and projects once, only if none exist yet.
 */
function vincent_seed_demo_content() {
	// No capability check: after_switch_theme fires on the first request after activation, which may be a visitor's.
	if ( get_option( 'vincent_seeded' ) ) {
		return;
	}
	update_option( 'vincent_seeded', 1, true );

	$existing = get_posts(
		array(
			'post_type'      => array( 'vincent_service', 'vincent_project' ),
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( $existing ) {
		return;
	}

	$services = array(
		array(
			'title'   => 'WordPress Development',
			'excerpt' => 'Custom themes and plugins built on clean, maintainable code, so your site is fast, secure and easy to edit.',
			'items'   => array(
				array( 'title' => 'Custom Themes', 'description' => '(Block, hybrid and headless)' ),
				array( 'title' => 'Plugin Development', 'description' => '(Custom post types, REST, integrations)' ),
				array( 'title' => 'WooCommerce', 'description' => '(Checkout, payments, shipping)' ),
			),
		),
		array(
			'title'   => 'Frontend Engineering',
			'excerpt' => 'Interactive interfaces with React and modern CSS that feel smooth on every device.',
			'items'   => array(
				array( 'title' => 'React Apps', 'description' => '(SPAs, dashboards, headless front ends)' ),
				array( 'title' => 'Motion Design', 'description' => '(GSAP, scroll effects, WebGL)' ),
				array( 'title' => 'Design Systems', 'description' => '(Tailwind, components, tokens)' ),
			),
		),
		array(
			'title'   => 'Performance & Security',
			'excerpt' => 'Slow or hacked sites destroy trust. I audit, harden and optimise so your site stays fast and safe as it grows.',
			'items'   => array(
				array( 'title' => 'Code Audits', 'description' => '(Refactoring, tech debt cleanup)' ),
				array( 'title' => 'Hardening', 'description' => '(Updates, backups, vulnerability checks)' ),
				array( 'title' => 'Core Web Vitals', 'description' => '(Caching, images, Lighthouse 90+)' ),
			),
		),
		array(
			'title'   => 'Maintenance & Support',
			'excerpt' => 'Ongoing care for live sites: updates, monitoring and small improvements, without surprises.',
			'items'   => array(
				array( 'title' => 'Monitoring', 'description' => '(Uptime, errors, performance)' ),
				array( 'title' => 'Content Help', 'description' => '(Editors, training, migrations)' ),
				array( 'title' => 'Hosting Setup', 'description' => '(Staging, deploys, DNS)' ),
			),
		),
	);

	foreach ( $services as $order => $service ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'vincent_service',
				'post_status'  => 'publish',
				'post_title'   => $service['title'],
				'post_excerpt' => $service['excerpt'],
				'menu_order'   => $order,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_vincent_items', $service['items'] );
		}
	}

	$projects = array(
		array( 'Studio Coffee Shop', array( 'WordPress', 'WooCommerce', 'Tailwind CSS' ) ),
		array( 'Architecture Portfolio', array( 'React', 'GSAP', 'Headless WP' ) ),
		array( 'Travel Booking Platform', array( 'Laravel', 'Vue.js', 'MySQL' ) ),
		array( 'Fitness Membership App', array( 'React', 'Node.js', 'Stripe' ) ),
		array( 'Nonprofit Campaign Site', array( 'WordPress', 'ACF', 'Alpine.js' ) ),
		array( 'Music Label Store', array( 'Next.js', 'WPGraphQL', 'Three.js' ) ),
	);

	foreach ( $projects as $order => $project ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'vincent_project',
				'post_status'  => 'publish',
				'post_title'   => $project[0],
				'post_excerpt' => 'Demo project. Replace the title, excerpt, featured image and tech stack.',
				'menu_order'   => $order,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_vincent_frameworks', $project[1] );
		}
	}
}
