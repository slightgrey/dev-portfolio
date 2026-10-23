<?php
/**
 * Minifies front-end HTML output: strips comments and collapses whitespace.
 *
 * Inline <style> is lightly minified (comments, whitespace). Contents of <script>, <pre> and
 * <textarea> are left untouched. Whitespace runs in markup collapse to a single space (never
 * removed), so rendering is identical. Turned on/off with the "Minify HTML" switch
 * (Appearance → Developer Portfolio → Performance), or force it with the `vincent_minify_html` filter.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

add_action( 'template_redirect', 'vincent_start_html_minify', PHP_INT_MAX );

/**
 * Buffer the page so it can be minified before it is sent.
 */
function vincent_start_html_minify() {
	if (
		is_admin()
		|| wp_doing_ajax()
		|| wp_is_json_request()
		|| ( defined( 'REST_REQUEST' ) && REST_REQUEST )
		|| is_feed()
		|| is_robots()
		|| is_customize_preview()
	) {
		return;
	}

	/**
	 * Whether to minify front-end HTML.
	 *
	 * @param bool $minify Default: the "Minify HTML" switch in Appearance → Developer Portfolio → Performance.
	 */
	if ( ! apply_filters( 'vincent_minify_html', (bool) vincent_option( 'minify_html' ) ) ) {
		return;
	}

	ob_start( 'vincent_minify_html' );
}

/**
 * Output-buffer callback. Returns the original HTML untouched if anything looks off.
 *
 * @param string $html Buffered page.
 * @return string
 */
function vincent_minify_html( $html ) {
	// Only HTML documents (not XML sitemaps, JSON, files…).
	foreach ( headers_list() as $header ) {
		if ( 0 === stripos( $header, 'content-type:' ) && false === stripos( $header, 'text/html' ) ) {
			return $html;
		}
	}
	if ( false === stripos( $html, '<html' ) ) {
		return $html;
	}

	// Inline CSS: drop comments and collapse whitespace. (Inline JS is never rewritten.)
	$out = preg_replace_callback(
		'#(<style\b[^>]*>)(.*?)(</style\s*>)#is',
		static function ( $match ) {
			$css = preg_replace( '#/\*.*?\*/#s', '', $match[2] );
			$css = null === $css ? $match[2] : preg_replace( '/\s+/', ' ', $css );
			$css = null === $css ? $match[2] : preg_replace( '/\s*([{};,])\s*/', '$1', $css );
			return $match[1] . trim( (string) $css ) . $match[3];
		},
		$html
	);
	if ( null === $out ) {
		return $html;
	}

	// Swap whitespace-sensitive blocks for placeholders so they survive untouched.
	$kept = array();
	$out  = preg_replace_callback(
		'#<(script|style|pre|textarea)\b[^>]*>.*?</\1\s*>#is',
		static function ( $match ) use ( &$kept ) {
			$kept[] = $match[0];
			return "\x00" . ( count( $kept ) - 1 ) . "\x00";
		},
		$out
	);

	// Remove comments, keeping IE conditional comments.
	$out = null === $out ? null : preg_replace( '/<!--(?!\[if|<!\[endif).*?-->/s', '', $out );

	// Collapse every whitespace run to one space.
	$out = null === $out ? null : preg_replace( '/\s+/', ' ', $out );

	// A regex failure (e.g. backtrack limit on a huge page) returns null: fall back to the original.
	if ( null === $out ) {
		return $html;
	}

	$out = preg_replace_callback(
		'/\x00(\d+)\x00/',
		static function ( $match ) use ( $kept ) {
			return $kept[ (int) $match[1] ];
		},
		$out
	);

	return null === $out ? $html : trim( $out );
}
