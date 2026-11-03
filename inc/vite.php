<?php
/**
 * Loads Vite assets: from the dev server when `hot` exists, otherwise from dist/.vite/manifest.json.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dev server URL written by vite.config.js, or null in production.
 *
 * @return string|null
 */
function vincent_vite_dev_url() {
	static $url = false;

	if ( false === $url ) {
		$hot = VINCENT_DIR . '/hot';
		$url = is_readable( $hot ) ? untrailingslashit( trim( (string) file_get_contents( $hot ) ) ) : null; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	return $url ? $url : null;
}

/**
 * Parsed build manifest.
 *
 * @return array
 */
function vincent_vite_manifest() {
	static $manifest = null;

	if ( null === $manifest ) {
		$path     = VINCENT_DIR . '/dist/.vite/manifest.json';
		$manifest = is_readable( $path ) ? (array) json_decode( (string) file_get_contents( $path ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	return $manifest;
}

/**
 * CSS files for a manifest entry, including those of statically imported chunks.
 *
 * @param string $entry Manifest key, e.g. "src/main.jsx".
 * @param array  $seen  Visited keys (recursion guard).
 * @return string[]
 */
function vincent_vite_css( $entry, &$seen = array() ) {
	$manifest = vincent_vite_manifest();

	if ( isset( $seen[ $entry ] ) || empty( $manifest[ $entry ] ) ) {
		return array();
	}
	$seen[ $entry ] = true;

	$css = $manifest[ $entry ]['css'] ?? array();
	foreach ( $manifest[ $entry ]['imports'] ?? array() as $import ) {
		$css = array_merge( $css, vincent_vite_css( $import, $seen ) );
	}

	return array_unique( $css );
}

/**
 * Enqueue a CSS entry (e.g. "src/site.css") from the dev server or the build.
 *
 * @param string $handle Style handle.
 * @param string $entry  Manifest key / source path.
 */
function vincent_vite_style( $handle, $entry ) {
	$dev = vincent_vite_dev_url();
	if ( $dev ) {
		wp_enqueue_style( $handle, $dev . '/' . $entry, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- dev server.
		return;
	}

	$manifest = vincent_vite_manifest();
	if ( ! empty( $manifest[ $entry ]['file'] ) ) {
		wp_enqueue_style( $handle, VINCENT_URI . '/dist/' . $manifest[ $entry ]['file'], array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- hashed filename.
	}
}

/**
 * Front-page entry for the active design. Each design is its own bundle and stylesheet, so only
 * one is ever loaded and their styles can't affect each other.
 *
 * @return string Manifest key / source path.
 */
function vincent_app_entry() {
	return 'neumorphism' === vincent_site_design() ? 'src/neumorphism/main.jsx' : 'src/main.jsx';
}

add_action( 'wp_enqueue_scripts', 'vincent_enqueue_assets' );

/**
 * Front page gets the React app; every other template gets the fallback stylesheet.
 */
function vincent_enqueue_assets() {
	if ( ! is_front_page() ) {
		vincent_vite_style( 'vincent-site', 'src/site.css' );
		return;
	}

	if ( vincent_vite_dev_url() ) {
		// Dev tags are printed directly in vincent_vite_dev_tags().
		return;
	}

	$manifest = vincent_vite_manifest();
	$dist     = VINCENT_URI . '/dist/';
	$entry    = vincent_app_entry();

	if ( empty( $manifest[ $entry ] ) ) {
		return;
	}
	foreach ( vincent_vite_css( $entry ) as $i => $file ) {
		wp_enqueue_style( 'vincent-app-' . $i, $dist . $file, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- hashed filename.
	}
	wp_enqueue_script_module( 'vincent-app', $dist . $manifest[ $entry ]['file'], array(), null );

	// Both designs share chunks (React), so fetch them alongside the entry instead of after it.
	$preload = array();
	foreach ( $manifest[ $entry ]['imports'] ?? array() as $import ) {
		if ( ! empty( $manifest[ $import ]['file'] ) ) {
			$preload[] = $dist . $manifest[ $import ]['file'];
		}
	}
	if ( $preload ) {
		add_action(
			'wp_head',
			static function () use ( $preload ) {
				foreach ( $preload as $url ) {
					printf( '<link rel="modulepreload" href="%s">' . "\n", esc_url( $url ) );
				}
			},
			2
		);
	}
}

add_action( 'wp_head', 'vincent_vite_dev_tags', 1 );

/**
 * Print Vite client, React Refresh preamble and entry when the dev server is running.
 */
function vincent_vite_dev_tags() {
	$dev = vincent_vite_dev_url();
	if ( ! $dev ) {
		return;
	}

	if ( ! is_front_page() ) {
		return;
	}

	$refresh = esc_url( $dev . '/@react-refresh' );
	?>
<script type="module">
import RefreshRuntime from '<?php echo $refresh; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>';
RefreshRuntime.injectIntoGlobalHook(window);
window.$RefreshReg$ = () => {};
window.$RefreshSig$ = () => (type) => type;
window.__vite_plugin_react_preamble_installed__ = true;
</script>
<script type="module" src="<?php echo esc_url( $dev . '/@vite/client' ); ?>"></script>
<script type="module" src="<?php echo esc_url( $dev . '/' . vincent_app_entry() ); ?>"></script>
	<?php
}

add_filter( 'body_class', 'vincent_design_body_class' );

/**
 * Mark the front page with the active design, so the Neumorphism stylesheet can style <body>.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function vincent_design_body_class( $classes ) {
	if ( is_front_page() && 'neumorphism' === vincent_site_design() ) {
		$classes[] = 'design-neumorphism';
	}
	return $classes;
}

add_action( 'wp_head', 'vincent_print_portfolio_data', 0 );

/**
 * Expose content to React. Printed before any module so it is available when the app boots.
 */
function vincent_print_portfolio_data() {
	if ( ! is_front_page() ) {
		return;
	}

	wp_print_inline_script_tag(
		'window.vincentData = ' . wp_json_encode( vincent_get_portfolio_data(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ) . ';',
		array( 'id' => 'vincent-data' )
	);
}
