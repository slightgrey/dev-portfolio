<?php
/**
 * Builds the content payload the React front page reads from window.vincentData.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Parse "First | second" lines into a list of associative arrays.
 *
 * @param string $text  Raw textarea value.
 * @param string $first Key for the left side.
 * @param string $second Key for the right side.
 * @return array<int, array<string, string>>
 */
function vincent_parse_pairs( $text, $first, $second ) {
	$pairs = array();

	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( '' === $parts[0] ) {
			continue;
		}
		$pairs[] = array(
			$first  => $parts[0],
			$second => $parts[1] ?? '',
		);
	}

	return $pairs;
}

/**
 * Inverse of vincent_parse_pairs() for textarea display.
 *
 * @param array  $pairs  List of associative arrays.
 * @param string $first  Key for the left side.
 * @param string $second Key for the right side.
 * @return string
 */
function vincent_pairs_to_text( $pairs, $first, $second ) {
	$lines = array();

	foreach ( $pairs as $pair ) {
		if ( ! is_array( $pair ) || empty( $pair[ $first ] ) ) {
			continue;
		}
		$lines[] = $pair[ $first ] . ( '' !== ( $pair[ $second ] ?? '' ) ? ' | ' . $pair[ $second ] : '' );
	}

	return implode( "\n", $lines );
}

/**
 * Plain text for React, which escapes on render: strips tags and decodes the
 * entities that get_the_title()/get_the_excerpt() add (wptexturize, kses).
 *
 * @param string $html Filtered WordPress output.
 * @return string
 */
function vincent_plain_text( $html ) {
	return html_entity_decode( wp_strip_all_tags( (string) $html ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
}

/**
 * Header block (subtitle / title / text) for a section.
 *
 * @param string $prefix Option key prefix, e.g. "works".
 * @return array<string, string>
 */
function vincent_section_header( $prefix ) {
	return array(
		'subTitle' => (string) vincent_option( $prefix . '_subtitle' ),
		'title'    => (string) vincent_option( $prefix . '_title' ),
		'text'     => (string) vincent_option( $prefix . '_text' ),
	);
}

/**
 * Services ordered by menu_order.
 *
 * @return array
 */
function vincent_get_services() {
	$posts = get_posts(
		array(
			'post_type'      => 'vincent_service',
			'posts_per_page' => 20,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'no_found_rows'  => true,
		)
	);

	return array_map(
		static function ( $post ) {
			$items = get_post_meta( $post->ID, '_vincent_items', true );
			return array(
				'title'       => vincent_plain_text( get_the_title( $post ) ),
				'description' => vincent_plain_text( get_the_excerpt( $post ) ),
				'items'       => is_array( $items ) ? array_values( $items ) : array(),
			);
		},
		$posts
	);
}

/**
 * Projects ordered by menu_order.
 *
 * @return array
 */
function vincent_get_projects() {
	$posts = get_posts(
		array(
			'post_type'      => 'vincent_project',
			'posts_per_page' => 50,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'  => true,
		)
	);

	return array_map(
		static function ( $post ) {
			$frameworks = get_post_meta( $post->ID, '_vincent_frameworks', true );
			$image      = vincent_image( (int) get_post_thumbnail_id( $post ), 'vincent-project' );
			$bg_image   = vincent_image( (int) get_post_meta( $post->ID, '_vincent_bg_image', true ), 'vincent-project' );

			return array(
				'id'          => $post->ID,
				'name'        => vincent_plain_text( get_the_title( $post ) ),
				'description' => vincent_plain_text( get_the_excerpt( $post ) ),
				'href'        => (string) get_post_meta( $post->ID, '_vincent_url', true ),
				'image'       => $image['url'],
				'imageSize'   => array( $image['width'], $image['height'] ),
				'bgImage'     => $bg_image['url'],
				'bgImageSize' => array( $bg_image['width'], $bg_image['height'] ),
				'frameworks'  => is_array( $frameworks ) ? array_values( $frameworks ) : array(),
			);
		},
		$posts
	);
}

/**
 * Image URL plus its pixel size, so the front end can set width/height and avoid layout shift.
 *
 * @param int    $attachment_id Attachment ID (0 for none).
 * @param string $size          Registered image size.
 * @return array{url: string, width: int, height: int}
 */
function vincent_image( $attachment_id, $size ) {
	$src = $attachment_id ? wp_get_attachment_image_src( $attachment_id, $size ) : false;

	return $src
		? array( 'url' => (string) $src[0], 'width' => (int) $src[1], 'height' => (int) $src[2] )
		: array( 'url' => '', 'width' => 0, 'height' => 0 );
}

/**
 * Full payload for the React app. Shape mirrors the original constants file.
 *
 * @return array
 */
function vincent_get_portfolio_data() {
	$about_image = (int) vincent_option( 'about_image' );
	$about_img   = vincent_image( $about_image, 'large' );

	$sections = array();
	foreach ( vincent_option_fields() as $section_key => $section ) {
		if ( vincent_is_page_section( $section ) ) {
			$sections[ $section_key ] = vincent_section_enabled( $section_key );
		}
	}

	$data = array(
		'sections'       => $sections,
		'hero'           => array_merge(
			vincent_section_header( 'hero' ),
			array( 'scroll' => (string) vincent_option( 'hero_scroll' ) )
		),
		'serviceSummary' => array_map(
			static function ( $row ) {
				return array_values( array_filter( array_map( 'trim', explode( '|', $row ) ) ) );
			},
			(array) vincent_option( 'service_summary' )
		),
		'servicesHeader' => vincent_section_header( 'services' ),
		'services'       => $sections['services'] ? vincent_get_services() : array(),
		'about'          => array_merge(
			vincent_section_header( 'about' ),
			array(
				'body'     => (string) vincent_option( 'about_body' ),
				'image'     => $about_img['url'],
				'imageSize' => array( $about_img['width'], $about_img['height'] ),
				'imageAlt'  => $about_image ? (string) get_post_meta( $about_image, '_wp_attachment_image_alt', true ) : '',
			)
		),
		'worksHeader'    => vincent_section_header( 'works' ),
		'projects'       => $sections['works'] ? vincent_get_projects() : array(),
		'cta'            => array(
			'values'  => array_values( (array) vincent_option( 'values_marquee' ) ),
			'quote'   => wp_kses( (string) vincent_option( 'cta_quote' ), vincent_allowed_html() ),
			'marquee' => (string) vincent_option( 'cta_marquee' ),
		),
		'contact'        => array_merge(
			vincent_section_header( 'contact' ),
			array(
				'email'   => (string) vincent_option( 'email' ),
				'phone'   => (string) vincent_option( 'phone' ),
				'marquee' => (string) vincent_option( 'contact_marquee' ),
			)
		),
		'socials'        => array_values( (array) vincent_option( 'socials' ) ),
	);

	/**
	 * Filter the data passed to the React front page.
	 *
	 * @param array $data Portfolio data.
	 */
	return apply_filters( 'vincent_portfolio_data', $data );
}
