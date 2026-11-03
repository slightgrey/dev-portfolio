<?php
/**
 * Theme options page: Appearance → Developer Portfolio.
 *
 * All values live in a single `vincent_options` array. The page markup uses the
 * `tw:`-prefixed Tailwind build in src/admin.css.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions, grouped by section. Type drives rendering and sanitizing.
 *
 * Types: text, email, url, password, textarea, lines (one item per line), pairs (Name | value per line),
 * html, image, switch, select (needs `choices`: value => label).
 * Optional field keys: help, placeholder, designs. Optional section keys: description, icon (dashicon), link,
 * toggle, designs. `designs` lists the Site designs that use a section or field (default: all); the
 * settings page greys out and locks the rest while another design is selected.
 *
 * @return array<string, array{title: string, fields: array<string, array>}>
 */
function vincent_option_fields() {
	return array(
		// Site-wide settings, not a front-page section: no Shown/Hidden switch.
		'design'          => array(
			'title'       => __( 'Site design', 'dev-portfolio' ),
			'description' => __( 'Which homepage design visitors see. Both designs read the content on this page.', 'dev-portfolio' ),
			'icon'        => 'dashicons-art',
			'toggle'      => false,
			'fields'      => array(
				'site_design' => array(
					'type'    => 'select',
					'label'   => __( 'Homepage design', 'dev-portfolio' ),
					'choices' => array(
						'current'     => __( 'Current', 'dev-portfolio' ),
						'neumorphism' => __( 'Neumorphism', 'dev-portfolio' ),
					),
					'help'    => __( 'Current: the animated dark design. Neumorphism: the soft, light design with a built-in contact form; your e-mail address is then never shown on the site.', 'dev-portfolio' ),
					'default' => 'current',
				),
			),
		),
		'hero'            => array(
			'title'       => __( 'Hero', 'dev-portfolio' ),
			'description' => __( 'The first screen: your name in huge type over the 3D planet.', 'dev-portfolio' ),
			'icon'        => 'dashicons-admin-home',
			'fields'      => array(
				'hero_subtitle' => array( 'type' => 'text', 'designs' => array( 'current' ), 'label' => __( 'Eyebrow', 'dev-portfolio' ), 'help' => __( 'Small spaced-out line above the title.', 'dev-portfolio' ), 'default' => '404 No Bugs Found' ),
				'hero_title'    => array( 'type' => 'text', 'designs' => array( 'current' ), 'label' => __( 'Title', 'dev-portfolio' ), 'help' => __( 'Usually your name. Each word wraps onto its own line on mobile.', 'dev-portfolio' ), 'default' => 'Vincent' ),
				'hero_text'     => array( 'type' => 'textarea', 'label' => __( 'Intro text', 'dev-portfolio' ), 'help' => __( 'Each line animates in separately.', 'dev-portfolio' ), 'default' => "I help growing brands and startups\nstand out with fast, crafted\nwebsites and web apps" ),
				'hero_scroll'   => array( 'type' => 'text', 'designs' => array( 'current' ), 'label' => __( 'Scroll hint', 'dev-portfolio' ), 'help' => __( 'Text circling the scroll-down button. Leave empty to hide the button.', 'dev-portfolio' ), 'default' => 'Scroll to explore' ),
			),
		),
		'service_summary' => array(
			'title'       => __( 'Scrolling words', 'dev-portfolio' ),
			'description' => __( 'Large words that drift sideways as visitors scroll past.', 'dev-portfolio' ),
			'icon'        => 'dashicons-editor-textcolor',
			'designs'     => array( 'current' ),
			'fields'      => array(
				'service_summary' => array( 'type' => 'lines', 'label' => __( 'Rows', 'dev-portfolio' ), 'help' => __( 'One row per line. Separate words in a row with | to add gold dividers.', 'dev-portfolio' ), 'default' => array( 'Architecture', 'Development | Deployment', 'APIs | Frontends | Scalability', 'Databases' ) ),
			),
		),
		'services'        => array(
			'title'       => __( 'Services', 'dev-portfolio' ),
			'description' => __( 'Header above the stacked service cards.', 'dev-portfolio' ),
			'icon'        => 'dashicons-hammer',
			'link'        => array( 'url' => admin_url( 'edit.php?post_type=vincent_service' ), 'label' => __( 'Manage service cards', 'dev-portfolio' ) ),
			'fields'      => array(
				'services_subtitle' => array( 'type' => 'text', 'label' => __( 'Eyebrow', 'dev-portfolio' ), 'default' => 'Behind the scenes, beyond the screen' ),
				'services_title'    => array( 'type' => 'text', 'label' => __( 'Title', 'dev-portfolio' ), 'default' => 'Services' ),
				'services_text'     => array( 'type' => 'textarea', 'label' => __( 'Text', 'dev-portfolio' ), 'help' => __( 'Each line animates in separately.', 'dev-portfolio' ), 'default' => "I build secure, high-performance websites\nwith smooth UX that drive growth,\nnot headaches." ),
			),
		),
		'about'           => array(
			'title'       => __( 'About', 'dev-portfolio' ),
			'description' => __( 'Who you are, with a portrait that reveals on scroll.', 'dev-portfolio' ),
			'icon'        => 'dashicons-admin-users',
			'fields'      => array(
				'about_subtitle' => array( 'type' => 'text', 'label' => __( 'Eyebrow', 'dev-portfolio' ), 'default' => 'Code with purpose, built to scale' ),
				'about_title'    => array( 'type' => 'text', 'label' => __( 'Title', 'dev-portfolio' ), 'default' => 'About' ),
				'about_text'     => array( 'type' => 'textarea', 'designs' => array( 'current' ), 'label' => __( 'Header text', 'dev-portfolio' ), 'help' => __( 'Each line animates in separately.', 'dev-portfolio' ), 'default' => "Passionate about clean architecture,\nI build scalable, high-performance solutions\nfrom prototype to production" ),
				'about_body'     => array( 'type' => 'textarea', 'designs' => array( 'current' ), 'label' => __( 'Body', 'dev-portfolio' ), 'help' => __( 'Shown next to the portrait. Each line animates in separately; emoji are fine.', 'dev-portfolio' ), 'default' => "Obsessed with building fast, intuitive sites, from pixel-perfect React UIs to solid WordPress back ends.\nWhen I'm not shipping:\n⚡ Experimenting with new tools and libraries\n📚 Writing about what I learn\n🎧 Listening to music while the build runs" ),
				'about_image'    => array( 'type' => 'image', 'designs' => array( 'current' ), 'label' => __( 'Portrait', 'dev-portfolio' ), 'help' => __( 'Portrait orientation works best. Without one, a gradient is shown.', 'dev-portfolio' ), 'default' => 0 ),
			),
		),
		'works'           => array(
			'title'       => __( 'Works', 'dev-portfolio' ),
			'description' => __( 'Header above the project list with hover previews.', 'dev-portfolio' ),
			'icon'        => 'dashicons-portfolio',
			'designs'     => array( 'current' ),
			'link'        => array( 'url' => admin_url( 'edit.php?post_type=vincent_project' ), 'label' => __( 'Manage projects', 'dev-portfolio' ) ),
			'fields'      => array(
				'works_subtitle' => array( 'type' => 'text', 'label' => __( 'Eyebrow', 'dev-portfolio' ), 'default' => 'Logic meets aesthetics, seamlessly' ),
				'works_title'    => array( 'type' => 'text', 'label' => __( 'Title', 'dev-portfolio' ), 'default' => 'Works' ),
				'works_text'     => array( 'type' => 'textarea', 'label' => __( 'Text', 'dev-portfolio' ), 'help' => __( 'Each line animates in separately.', 'dev-portfolio' ), 'default' => "Featured projects crafted\nwith care to drive\nresults and impact." ),
			),
		),
		'cta'             => array(
			'title'       => __( 'Call to action', 'dev-portfolio' ),
			'description' => __( 'The pinned quote between two marquees, just before Contact.', 'dev-portfolio' ),
			'icon'        => 'dashicons-format-quote',
			'fields'      => array(
				'values_marquee' => array( 'type' => 'lines', 'label' => __( 'Values marquee', 'dev-portfolio' ), 'help' => __( 'One word or phrase per line.', 'dev-portfolio' ), 'default' => array( 'Innovation', 'Precision', 'Trust', 'Collaboration', 'Excellence' ) ),
				'cta_marquee'    => array( 'type' => 'text', 'designs' => array( 'current' ), 'label' => __( 'Bottom marquee text', 'dev-portfolio' ), 'help' => __( 'Repeated across the outlined marquee.', 'dev-portfolio' ), 'default' => 'contact me' ),
				'cta_quote'      => array( 'type' => 'html', 'label' => __( 'Quote', 'dev-portfolio' ), 'help' => __( 'Allowed tags: <br>, <em>, <strong>, and <span class="text-gold"> for gold words.', 'dev-portfolio' ), 'default' => '“ Let’s build a <br><strong>memorable</strong> &amp; <em>inspiring</em> <br>website <span class="text-gold">together</span> ”' ),
			),
		),
		'contact'         => array(
			'title'       => __( 'Contact', 'dev-portfolio' ),
			'description' => __( 'How people reach you. Also used in the menu overlay and inner-page footer.', 'dev-portfolio' ),
			'icon'        => 'dashicons-email-alt',
			'fields'      => array(
				'contact_subtitle' => array( 'type' => 'text', 'label' => __( 'Eyebrow', 'dev-portfolio' ), 'default' => 'You dream it, I code it' ),
				'contact_title'    => array( 'type' => 'text', 'label' => __( 'Title', 'dev-portfolio' ), 'default' => 'Contact' ),
				'contact_text'     => array( 'type' => 'textarea', 'label' => __( 'Text', 'dev-portfolio' ), 'help' => __( 'Each line animates in separately.', 'dev-portfolio' ), 'default' => "Got a question or a project idea?\nI'd love to hear from you and talk it through." ),
				'email'            => array( 'type' => 'email', 'label' => __( 'E-mail', 'dev-portfolio' ), 'placeholder' => 'you@example.com', 'default' => 'hello@example.com' ),
				'phone'            => array( 'type' => 'text', 'designs' => array( 'current' ), 'label' => __( 'Phone', 'dev-portfolio' ), 'help' => __( 'Leave empty to hide.', 'dev-portfolio' ), 'placeholder' => '+1 555 010 0000', 'default' => '' ),
				'contact_marquee'  => array( 'type' => 'text', 'designs' => array( 'current' ), 'label' => __( 'Marquee text', 'dev-portfolio' ), 'help' => __( 'Repeated along the bottom of the page.', 'dev-portfolio' ), 'default' => 'just imagine, I code' ),
				'socials'          => array( 'type' => 'pairs', 'label' => __( 'Social links', 'dev-portfolio' ), 'help' => __( 'One per line: Name | https://url', 'dev-portfolio' ), 'default' => array( array( 'name' => 'GitHub', 'href' => 'https://github.com/' ), array( 'name' => 'LinkedIn', 'href' => 'https://www.linkedin.com/' ) ) ),
			),
		),
		'neumorphism'     => array(
			'title'       => __( 'Neumorphism design', 'dev-portfolio' ),
			'description' => __( 'Extra text used only by the Neumorphism design. It also uses the hero intro, service cards, section eyebrows, values, contact text and social links above.', 'dev-portfolio' ),
			'icon'        => 'dashicons-admin-appearance',
			'designs'     => array( 'neumorphism' ),
			'toggle'      => false,
			'fields'      => array(
				'nm_logo'           => array( 'type' => 'text', 'label' => __( 'Logo initials', 'dev-portfolio' ), 'default' => 'VL' ),
				'nm_brand'          => array( 'type' => 'text', 'label' => __( 'Brand name', 'dev-portfolio' ), 'default' => 'vince.layte' ),
				'nm_hero_eyebrow'   => array( 'type' => 'text', 'label' => __( 'Hero eyebrow', 'dev-portfolio' ), 'default' => 'Web of D · Code for food' ),
				'nm_blog_url'       => array( 'type' => 'url', 'label' => __( 'Blog URL', 'dev-portfolio' ), 'help' => __( 'Adds a Blog link to the menu. Leave empty to hide it.', 'dev-portfolio' ), 'placeholder' => 'https://', 'default' => '' ),
				'nm_hero_lead'      => array( 'type' => 'textarea', 'label' => __( 'Hero text', 'dev-portfolio' ), 'help' => __( 'Shown under the headline. The headline itself is the Hero intro text.', 'dev-portfolio' ), 'default' => 'WordPress, WooCommerce and React, from custom plugins and headless front ends to performance and security work.' ),
				'nm_stack'          => array( 'type' => 'lines', 'label' => __( 'Stack chips', 'dev-portfolio' ), 'help' => __( 'One per line. Shown in the panel next to the headline.', 'dev-portfolio' ), 'default' => array( 'PHP', 'WordPress', 'React', 'JavaScript', 'APIs', 'Frontends', 'Databases', 'Architecture', 'Development', 'Scalability' ) ),
				'nm_terminal'       => array( 'type' => 'text', 'label' => __( 'Terminal line', 'dev-portfolio' ), 'help' => __( 'Shown after a $ prompt under the stack chips.', 'dev-portfolio' ), 'default' => 'just imagine, I code' ),
				'nm_reply_time'     => array( 'type' => 'text', 'label' => __( 'Reply time', 'dev-portfolio' ), 'help' => __( 'Completes “I usually reply within …” under the contact form. Leave empty to hide.', 'dev-portfolio' ), 'default' => '1–2 business days' ),
				'nm_about_heading'  => array( 'type' => 'text', 'label' => __( 'About heading', 'dev-portfolio' ), 'default' => 'Passionate about clean architecture, from prototype to production.' ),
				'nm_about_text'     => array( 'type' => 'textarea', 'label' => __( 'About text', 'dev-portfolio' ), 'default' => 'Obsessed with building fast, intuitive sites, from pixel-perfect React UIs to solid WordPress back ends. I build scalable, high-performance solutions that hold up as your business grows.' ),
				'nm_hobbies'        => array( 'type' => 'lines', 'label' => __( 'When I’m not shipping', 'dev-portfolio' ), 'help' => __( 'One per line, each with an icon.', 'dev-portfolio' ), 'default' => array( 'Experimenting with new tools and libraries', 'Writing about what I learn', 'Listening to music while the build runs' ) ),
				'nm_footer_tagline' => array( 'type' => 'text', 'label' => __( 'Footer tagline', 'dev-portfolio' ), 'default' => 'Just imagine, I code.' ),
				'nm_footer_name'    => array( 'type' => 'text', 'label' => __( 'Copyright name', 'dev-portfolio' ), 'default' => 'Vince Layte' ),
			),
		),
		'recaptcha'       => array(
			'title'       => __( 'reCAPTCHA', 'dev-portfolio' ),
			'description' => __( 'Spam protection for the Neumorphism contact form, guest comments and the login screens. Only enforced when both keys are set. Locked out? Add define( \'VINCENT_RECAPTCHA_DISABLED\', true ); to wp-config.php.', 'dev-portfolio' ),
			'icon'        => 'dashicons-shield',
			'toggle'      => false,
			'fields'      => array(
				'recaptcha_version'      => array( 'type' => 'select', 'label' => __( 'Version', 'dev-portfolio' ), 'choices' => array( 'v3' => __( 'Invisible (v3)', 'dev-portfolio' ), 'v2' => __( 'Checkbox (v2)', 'dev-portfolio' ) ), 'help' => __( 'Must match the key type you created at google.com/recaptcha/admin.', 'dev-portfolio' ), 'default' => vincent_legacy_recaptcha_option( 'vl_cf_recaptcha_version', 'v3' ) ),
				'recaptcha_site'         => array( 'type' => 'text', 'label' => __( 'Site key', 'dev-portfolio' ), 'default' => vincent_legacy_recaptcha_option( 'vl_cf_recaptcha_site', '' ) ),
				'recaptcha_secret'       => array( 'type' => 'password', 'label' => __( 'Secret key', 'dev-portfolio' ), 'help' => __( 'Leave blank to turn reCAPTCHA off on every form.', 'dev-portfolio' ), 'default' => vincent_legacy_recaptcha_option( 'vl_cf_recaptcha_secret', '' ) ),
				'recaptcha_contact'      => array( 'type' => 'switch', 'label' => __( 'Contact form', 'dev-portfolio' ), 'help' => __( 'Everyone must pass reCAPTCHA to send the Neumorphism contact form.', 'dev-portfolio' ), 'default' => true ),
				'recaptcha_comments'     => array( 'type' => 'switch', 'label' => __( 'Comments', 'dev-portfolio' ), 'help' => __( 'Guests must pass reCAPTCHA to comment. Logged-in users skip the check.', 'dev-portfolio' ), 'default' => '1' === vincent_legacy_recaptcha_option( 'vl_recaptcha_comments', '1' ) ),
				'recaptcha_login'        => array( 'type' => 'switch', 'label' => __( 'Login form', 'dev-portfolio' ), 'help' => __( 'Test it in a private window before logging out.', 'dev-portfolio' ), 'default' => '1' === vincent_legacy_recaptcha_option( 'vl_recaptcha_login', '0' ) ),
				'recaptcha_register'     => array( 'type' => 'switch', 'label' => __( 'Registration form', 'dev-portfolio' ), 'help' => get_option( 'users_can_register' ) ? '' : __( 'Registration is currently off in Settings → General.', 'dev-portfolio' ), 'default' => '1' === vincent_legacy_recaptcha_option( 'vl_recaptcha_register', '0' ) ),
				'recaptcha_lostpassword' => array( 'type' => 'switch', 'label' => __( 'Lost password form', 'dev-portfolio' ), 'default' => '1' === vincent_legacy_recaptcha_option( 'vl_recaptcha_lostpassword', '0' ) ),
			),
		),
		// Site-wide settings, not a front-page section: no Shown/Hidden switch.
		'performance'     => array(
			'title'       => __( 'Performance', 'dev-portfolio' ),
			'description' => __( 'Site-wide output settings. These do not change how the site looks.', 'dev-portfolio' ),
			'icon'        => 'dashicons-performance',
			'toggle'      => false,
			'fields'      => array(
				'minify_html'        => array( 'type' => 'switch', 'label' => __( 'Minify HTML', 'dev-portfolio' ), 'help' => __( 'Removes comments and extra whitespace from front-end pages (View Source shows compact HTML). Turn off while debugging markup.', 'dev-portfolio' ), 'default' => true ),
				'disable_emoji'      => array( 'type' => 'switch', 'label' => __( 'Disable emoji script', 'dev-portfolio' ), 'help' => __( 'Removes WordPress\'s emoji script and styles on every page and in wp-admin. Emoji still display, using the visitor\'s own system emoji.', 'dev-portfolio' ), 'default' => true ),
				'front_block_styles' => array( 'type' => 'switch', 'label' => __( 'Remove block styles on the home page', 'dev-portfolio' ), 'help' => __( 'The home page is built by the theme and uses no block-editor content. Posts and pages keep these styles.', 'dev-portfolio' ), 'default' => true ),
				'clean_head'         => array( 'type' => 'switch', 'label' => __( 'Remove unused <head> tags', 'dev-portfolio' ), 'help' => __( 'Removes the WordPress version tag, RSD, shortlink, oEmbed and REST API discovery links. Feeds, canonical and the REST API itself keep working.', 'dev-portfolio' ), 'default' => true ),
			),
		),
	);
}

/**
 * A reCAPTCHA setting saved by the vincelayte React theme, used as this theme's default so
 * existing keys and switches carry over without re-entering them.
 *
 * @param string $name    Legacy option name.
 * @param string $default Fallback.
 * @return string
 */
function vincent_legacy_recaptcha_option( $name, $default ) {
	return (string) get_option( $name, $default );
}

/**
 * Active homepage design: "current" or "neumorphism".
 *
 * @return string
 */
function vincent_site_design() {
	return 'neumorphism' === vincent_option( 'site_design' ) ? 'neumorphism' : 'current';
}

/**
 * Attribute marking a settings card or field as used by only some designs; empty when every design
 * uses it. The settings page's CSS and script dim and lock it while another design is selected.
 *
 * @param array  $item Section or field definition from vincent_option_fields().
 * @param string $attr Attribute name.
 * @return string
 */
function vincent_design_attr( $item, $attr = 'data-vdp-designs' ) {
	if ( empty( $item['designs'] ) ) {
		return '';
	}
	return sprintf( ' %s="%s"', $attr, esc_attr( implode( ' ', $item['designs'] ) ) );
}

/**
 * "Only used by the … design" note, shown while the item is dimmed.
 *
 * @param array $item Section or field definition.
 */
function vincent_design_note( $item ) {
	if ( empty( $item['designs'] ) ) {
		return;
	}
	$choices = vincent_option_fields()['design']['fields']['site_design']['choices'];
	$names   = array_map(
		static function ( $design ) use ( $choices ) {
			return $choices[ $design ] ?? $design;
		},
		$item['designs']
	);
	?>
	<p class="vdp-design-note tw:m-0 tw:mt-2 tw:items-center tw:gap-1 tw:text-xs tw:font-medium tw:text-amber-700">
		<span class="dashicons dashicons-lock tw:size-4 tw:text-base tw:leading-4" aria-hidden="true"></span>
		<?php
		/* translators: %s: design name, e.g. "Current". */
		printf( esc_html__( 'Only used by the %s design. Your text is kept for when you switch back.', 'dev-portfolio' ), esc_html( implode( ', ', $names ) ) );
		?>
	</p>
	<?php
}

/**
 * Whether a settings card is a front-page section with a Shown/Hidden switch.
 *
 * @param array $section Section definition from vincent_option_fields().
 * @return bool
 */
function vincent_is_page_section( $section ) {
	return $section['toggle'] ?? true;
}

/**
 * Get one option with its default as fallback.
 *
 * @param string $key Option key.
 * @return mixed
 */
function vincent_option( $key ) {
	static $defaults = null;

	if ( null === $defaults ) {
		$defaults = array();
		foreach ( vincent_option_fields() as $section ) {
			foreach ( $section['fields'] as $field_key => $field ) {
				$defaults[ $field_key ] = $field['default'];
			}
		}
	}

	$options = get_option( 'vincent_options', array() );

	return array_key_exists( $key, (array) $options ) ? $options[ $key ] : ( $defaults[ $key ] ?? '' );
}

/**
 * Whether a front-page section is switched on. Stored as `show_<section key>`; defaults to on.
 *
 * @param string $section Section key from vincent_option_fields(), e.g. "works".
 * @return bool
 */
function vincent_section_enabled( $section ) {
	$options = (array) get_option( 'vincent_options', array() );
	$key     = 'show_' . $section;

	return ! array_key_exists( $key, $options ) || (bool) $options[ $key ];
}

/**
 * Tags allowed in the "html" field type.
 *
 * @return array
 */
function vincent_allowed_html() {
	return array(
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'span'   => array( 'class' => true ),
	);
}

add_action( 'admin_menu', 'vincent_add_options_page' );

/**
 * Register the options page under Appearance.
 */
function vincent_add_options_page() {
	add_theme_page(
		__( 'Developer Portfolio', 'dev-portfolio' ),
		__( 'Developer Portfolio', 'dev-portfolio' ),
		'edit_theme_options',
		'dev-portfolio',
		'vincent_render_options_page'
	);
}

add_action( 'admin_init', 'vincent_register_settings' );

/**
 * Register the setting. Fields are rendered by vincent_render_options_page(), not the Settings API tables.
 */
function vincent_register_settings() {
	register_setting(
		'vincent_options',
		'vincent_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'vincent_sanitize_options',
			'default'           => array(),
		)
	);
}

// The page uses edit_theme_options, so options.php must accept that capability too.
add_filter(
	'option_page_capability_vincent_options',
	static function () {
		return 'edit_theme_options';
	}
);

add_action( 'admin_enqueue_scripts', 'vincent_options_page_assets' );

/**
 * Tailwind build for the options page only.
 *
 * @param string $hook Admin page hook.
 */
function vincent_options_page_assets( $hook ) {
	if ( 'appearance_page_dev-portfolio' === $hook ) {
		vincent_vite_style( 'vincent-admin', 'src/admin.css' );
	}
}

/**
 * Render one field's control.
 *
 * @param string $key   Option key.
 * @param array  $field Field definition.
 */
function vincent_render_option_field( $key, $field ) {
	$id          = 'vincent_' . $key;
	$name        = 'vincent_options[' . $key . ']';
	$value       = vincent_option( $key );
	$placeholder = $field['placeholder'] ?? '';
	$input       = 'vdp-input tw:block tw:w-full tw:max-w-none tw:rounded-xl tw:border tw:border-black/10 tw:bg-stone-50 tw:px-4 tw:py-2.5 tw:text-sm tw:text-neutral-900 tw:shadow-none tw:transition tw:placeholder:text-neutral-400 tw:hover:border-black/20 tw:focus:border-neutral-900 tw:focus:bg-white tw:focus:shadow-none tw:focus:ring-4 tw:focus:ring-gold/25';
	$mono        = $input . ' tw:font-mono tw:text-[13px] tw:leading-6';

	switch ( $field['type'] ) {
		case 'textarea':
			$rows = max( 3, substr_count( (string) $value, "\n" ) + 2 );
			printf( '<textarea id="%s" name="%s" rows="%d" class="%s">%s</textarea>', esc_attr( $id ), esc_attr( $name ), (int) $rows, esc_attr( $input . ' tw:leading-6' ), esc_textarea( $value ) );
			break;

		case 'lines':
			$text = implode( "\n", (array) $value );
			printf( '<textarea id="%s" name="%s" rows="%d" class="%s">%s</textarea>', esc_attr( $id ), esc_attr( $name ), (int) max( 4, count( (array) $value ) + 1 ), esc_attr( $mono ), esc_textarea( $text ) );
			break;

		case 'pairs':
			$text = vincent_pairs_to_text( (array) $value, 'name', 'href' );
			printf( '<textarea id="%s" name="%s" rows="%d" class="%s" placeholder="GitHub | https://github.com/you">%s</textarea>', esc_attr( $id ), esc_attr( $name ), (int) max( 4, count( (array) $value ) + 1 ), esc_attr( $mono ), esc_textarea( $text ) );
			break;

		case 'html':
			printf( '<textarea id="%s" name="%s" rows="3" class="%s">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_attr( $mono ), esc_textarea( $value ) );
			?>
			<div class="vdp-quote-preview tw:mt-3 tw:rounded-2xl tw:bg-primary tw:px-6 tw:py-8 tw:text-center tw:font-display tw:text-2xl tw:leading-snug tw:font-light tw:text-black">
				<?php echo wp_kses( (string) $value, vincent_allowed_html() ); ?>
			</div>
			<p class="tw:mt-1 tw:text-[11px] tw:uppercase tw:tracking-wider tw:text-neutral-400"><?php esc_html_e( 'Preview of the saved quote', 'dev-portfolio' ); ?></p>
			<?php
			break;

		case 'image':
			vincent_render_image_field( $id, $name, (int) $value );
			break;

		case 'switch':
			// Same look as the section switches, but a different class so it doesn't fade its card.
			?>
			<span class="tw:relative tw:inline-flex tw:select-none tw:items-center tw:gap-3">
				<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" class="vdp-switch tw:peer tw:absolute tw:inset-0 tw:z-10 tw:m-0 tw:size-full tw:cursor-pointer tw:opacity-0" <?php checked( (bool) $value ); ?>>
				<span class="tw:block tw:h-6 tw:w-11 tw:rounded-full tw:bg-neutral-300 tw:transition tw:peer-checked:bg-black tw:peer-focus-visible:ring-4 tw:peer-focus-visible:ring-gold/40" aria-hidden="true"></span>
				<span class="tw:absolute tw:top-1 tw:left-1 tw:size-4 tw:rounded-full tw:bg-white tw:shadow tw:transition tw:peer-checked:translate-x-5 tw:peer-checked:bg-gold" aria-hidden="true"></span>
				<span class="tw:hidden tw:w-10 tw:text-xs tw:font-semibold tw:uppercase tw:tracking-wider tw:text-neutral-900 tw:peer-checked:inline"><?php esc_html_e( 'On', 'dev-portfolio' ); ?></span>
				<span class="tw:inline tw:w-10 tw:text-xs tw:font-semibold tw:uppercase tw:tracking-wider tw:text-neutral-400 tw:peer-checked:hidden"><?php esc_html_e( 'Off', 'dev-portfolio' ); ?></span>
			</span>
			<?php
			break;

		case 'select':
			printf( '<select id="%s" name="%s" class="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $input ) );
			foreach ( $field['choices'] as $choice => $choice_label ) {
				printf( '<option value="%s" %s>%s</option>', esc_attr( $choice ), selected( $value, $choice, false ), esc_html( $choice_label ) );
			}
			echo '</select>';
			break;

		case 'password':
			printf( '<input type="password" id="%s" name="%s" value="%s" autocomplete="off" class="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), esc_attr( $input ) );
			break;

		case 'url':
			printf( '<input type="url" id="%s" name="%s" value="%s" placeholder="%s" class="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), esc_attr( $placeholder ), esc_attr( $input ) );
			break;

		case 'email':
			printf( '<input type="email" id="%s" name="%s" value="%s" placeholder="%s" class="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), esc_attr( $placeholder ), esc_attr( $input ) );
			break;

		default:
			printf( '<input type="text" id="%s" name="%s" value="%s" placeholder="%s" class="%s">', esc_attr( $id ), esc_attr( $name ), esc_attr( $value ), esc_attr( $placeholder ), esc_attr( $input ) );
	}
}

/**
 * Image picker card for the options page (JS in assets/admin-media.js).
 *
 * @param string $id    Input id.
 * @param string $name  Input name.
 * @param int    $value Attachment ID.
 */
function vincent_render_image_field( $id, $name, $value ) {
	$preview = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';
	?>
	<div class="vincent-media-field tw:flex tw:flex-wrap tw:items-center tw:gap-5">
		<div class="tw:relative tw:h-40 tw:w-32 tw:shrink-0 tw:overflow-hidden tw:rounded-2xl tw:bg-gradient-to-br tw:from-DarkLava tw:to-black tw:ring-1 tw:ring-black/5">
			<img class="vincent-media-preview tw:absolute tw:inset-0 tw:h-full tw:w-full tw:object-cover" src="<?php echo esc_url( $preview ); ?>" alt="" <?php echo $preview ? '' : 'hidden'; ?>>
			<span class="vincent-media-empty" <?php echo $preview ? 'hidden' : ''; ?>>
				<span class="tw:absolute tw:inset-0 tw:flex tw:items-center tw:justify-center tw:text-[11px] tw:uppercase tw:tracking-widest tw:text-white/50"><?php esc_html_e( 'No image', 'dev-portfolio' ); ?></span>
			</span>
		</div>
		<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ? $value : '' ); ?>">
		<div class="tw:flex tw:flex-col tw:items-start tw:gap-2">
			<button type="button" class="vincent-media-select tw:inline-flex tw:cursor-pointer tw:items-center tw:gap-2 tw:rounded-full tw:border-0 tw:bg-neutral-900 tw:px-5 tw:py-2 tw:text-xs tw:font-medium tw:uppercase tw:tracking-wider tw:text-white tw:transition tw:hover:bg-black tw:hover:text-gold">
				<span class="dashicons dashicons-format-image" aria-hidden="true"></span><?php esc_html_e( 'Choose image', 'dev-portfolio' ); ?>
			</button>
			<button type="button" class="vincent-media-remove tw:cursor-pointer tw:border-0 tw:bg-transparent tw:p-0 tw:text-xs tw:text-neutral-500 tw:underline tw:underline-offset-4 tw:hover:text-red-600"><?php esc_html_e( 'Remove', 'dev-portfolio' ); ?></button>
		</div>
	</div>
	<?php
}

/**
 * On/off switch for a section. Styled with peer-checked, so it needs no JS.
 *
 * @param string $section_key Section key.
 * @param string $title       Section title, for the accessible label.
 */
function vincent_render_section_toggle( $section_key, $title ) {
	$id = 'vincent_show_' . $section_key;
	?>
	<label for="<?php echo esc_attr( $id ); ?>" class="tw:relative tw:inline-flex tw:cursor-pointer tw:select-none tw:items-center tw:gap-3">
		<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="vincent_options[show_<?php echo esc_attr( $section_key ); ?>]" value="1" class="vdp-toggle tw:peer tw:sr-only" <?php checked( vincent_section_enabled( $section_key ) ); ?>>
		<span class="tw:block tw:h-6 tw:w-11 tw:rounded-full tw:bg-neutral-300 tw:transition tw:peer-checked:bg-black tw:peer-focus-visible:ring-4 tw:peer-focus-visible:ring-gold/40" aria-hidden="true"></span>
		<span class="tw:absolute tw:top-1 tw:left-1 tw:size-4 tw:rounded-full tw:bg-white tw:shadow tw:transition tw:peer-checked:translate-x-5 tw:peer-checked:bg-gold" aria-hidden="true"></span>
		<span class="tw:hidden tw:w-14 tw:text-xs tw:font-semibold tw:uppercase tw:tracking-wider tw:text-neutral-900 tw:peer-checked:inline"><?php esc_html_e( 'Shown', 'dev-portfolio' ); ?></span>
		<span class="tw:inline tw:w-14 tw:text-xs tw:font-semibold tw:uppercase tw:tracking-wider tw:text-neutral-400 tw:peer-checked:hidden"><?php esc_html_e( 'Hidden', 'dev-portfolio' ); ?></span>
		<span class="screen-reader-text">
			<?php
			/* translators: %s: section title. */
			printf( esc_html__( 'Show the %s section on the front page', 'dev-portfolio' ), esc_html( $title ) );
			?>
		</span>
	</label>
	<?php
}

/**
 * Sanitize the whole options array according to field types.
 *
 * @param mixed $input Raw input.
 * @return array
 */
function vincent_sanitize_options( $input ) {
	$input = is_array( $input ) ? $input : array();
	$clean = array();

	foreach ( vincent_option_fields() as $section_key => $section ) {
		// Unchecked checkboxes are not posted, so a missing key means "off".
		if ( vincent_is_page_section( $section ) ) {
			$clean[ 'show_' . $section_key ] = ! empty( $input[ 'show_' . $section_key ] );
		}

		foreach ( $section['fields'] as $key => $field ) {
			$raw = $input[ $key ] ?? '';

			// WordPress runs this callback twice when the option is first created, so the
			// second pass receives already-sanitized arrays. Turn them back into text.
			if ( is_array( $raw ) ) {
				$raw = 'pairs' === $field['type'] ? vincent_pairs_to_text( $raw, 'name', 'href' ) : implode( "\n", array_filter( $raw, 'is_scalar' ) );
			}
			$raw = (string) $raw;

			switch ( $field['type'] ) {
				case 'textarea':
					$clean[ $key ] = sanitize_textarea_field( $raw );
					break;

				case 'html':
					$clean[ $key ] = wp_kses( $raw, vincent_allowed_html() );
					break;

				case 'lines':
					$clean[ $key ] = array_values( array_filter( array_map( 'trim', explode( "\n", sanitize_textarea_field( $raw ) ) ) ) );
					break;

				case 'pairs':
					$pairs = vincent_parse_pairs( sanitize_textarea_field( $raw ), 'name', 'href' );
					foreach ( $pairs as &$pair ) {
						$pair['href'] = esc_url_raw( $pair['href'] );
					}
					unset( $pair );
					$clean[ $key ] = $pairs;
					break;

				case 'image':
					$clean[ $key ] = absint( $raw );
					break;

				case 'switch':
					// Unchecked = not posted = "" (off). A second sanitize pass sees "1" for true.
					$clean[ $key ] = '' !== $raw && '0' !== $raw;
					break;

				case 'email':
					$clean[ $key ] = sanitize_email( $raw );
					break;

				case 'url':
					$clean[ $key ] = esc_url_raw( $raw );
					break;

				case 'select':
					$clean[ $key ] = array_key_exists( $raw, $field['choices'] ) ? $raw : $field['default'];
					break;

				default:
					$clean[ $key ] = sanitize_text_field( $raw );
			}
		}
	}

	return $clean;
}

/**
 * Where the front-end assets currently come from, for the header status pill.
 *
 * @return array{label: string, tone: string}
 */
function vincent_asset_status() {
	if ( vincent_vite_dev_url() ) {
		return array( 'label' => __( 'Dev server running', 'dev-portfolio' ), 'tone' => 'tw:bg-sky-400' );
	}
	if ( vincent_vite_manifest() ) {
		return array( 'label' => __( 'Production build', 'dev-portfolio' ), 'tone' => 'tw:bg-emerald-400' );
	}
	return array( 'label' => __( 'Not built: run npm run build', 'dev-portfolio' ), 'tone' => 'tw:bg-red-500' );
}

/**
 * Options page markup.
 */
function vincent_render_options_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$sections = vincent_option_fields();
	$status   = vincent_asset_status();
	$stats    = array(
		array(
			'label' => __( 'Projects', 'dev-portfolio' ),
			'count' => (int) wp_count_posts( 'vincent_project' )->publish,
			'url'   => admin_url( 'edit.php?post_type=vincent_project' ),
		),
		array(
			'label' => __( 'Services', 'dev-portfolio' ),
			'count' => (int) wp_count_posts( 'vincent_service' )->publish,
			'url'   => admin_url( 'edit.php?post_type=vincent_service' ),
		),
		array(
			'label' => __( 'Social links', 'dev-portfolio' ),
			'count' => count( (array) vincent_option( 'socials' ) ),
			'url'   => '#vdp-contact',
		),
	);
	$wide     = array( 'textarea', 'lines', 'pairs', 'html', 'image' );
	?>
	<div class="wrap vdp-admin tw:max-w-6xl tw:pr-5 tw:font-sans tw:text-neutral-900">
		<header class="tw:relative tw:mt-4 tw:overflow-hidden tw:rounded-3xl tw:bg-black tw:px-8 tw:py-10 tw:text-white tw:md:px-12">
			<div class="tw:pointer-events-none tw:absolute tw:-top-40 tw:-right-24 tw:size-80 tw:rounded-full tw:border tw:border-gold/40" aria-hidden="true"></div>
			<div class="tw:pointer-events-none tw:absolute tw:-top-16 tw:right-16 tw:size-32 tw:rounded-full tw:bg-gradient-to-br tw:from-neutral-600 tw:to-neutral-900" aria-hidden="true"></div>
			<div class="tw:pointer-events-none tw:absolute tw:top-12 tw:right-12 tw:size-6 tw:rounded-full tw:bg-gradient-to-br tw:from-amber-200 tw:to-gold" aria-hidden="true"></div>

			<div class="tw:relative tw:flex tw:flex-col tw:gap-8 tw:lg:flex-row tw:lg:items-end tw:lg:justify-between">
				<div>
					<p class="tw:m-0 tw:text-[11px] tw:font-medium tw:uppercase tw:tracking-[0.4em] tw:text-white/50"><?php esc_html_e( 'Theme settings', 'dev-portfolio' ); ?></p>
					<h1 class="tw:m-0 tw:mt-4 tw:p-0 tw:font-display tw:text-5xl tw:font-light tw:uppercase tw:leading-none tw:text-white tw:md:text-6xl"><?php esc_html_e( 'Developer Portfolio', 'dev-portfolio' ); ?></h1>
					<p class="tw:m-0 tw:mt-4 tw:max-w-xl tw:text-sm tw:leading-6 tw:text-white/60"><?php esc_html_e( 'Text and contact details for the animated front page. Changes go live as soon as you save.', 'dev-portfolio' ); ?></p>
				</div>
				<div class="tw:flex tw:flex-wrap tw:items-center tw:gap-3">
					<span class="tw:inline-flex tw:items-center tw:gap-2 tw:rounded-full tw:bg-white/10 tw:px-4 tw:py-2 tw:text-xs tw:text-white/80">
						<span class="tw:size-2 tw:rounded-full <?php echo esc_attr( $status['tone'] ); ?>" aria-hidden="true"></span>
						<?php echo esc_html( $status['label'] ); ?>
					</span>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener" class="tw:inline-flex tw:items-center tw:gap-2 tw:rounded-full tw:bg-gold tw:px-5 tw:py-2.5 tw:text-xs tw:font-semibold tw:uppercase tw:tracking-wider tw:text-black tw:no-underline tw:transition tw:hover:bg-white tw:hover:text-black tw:focus:shadow-none">
						<?php esc_html_e( 'View site', 'dev-portfolio' ); ?> <span class="dashicons dashicons-external tw:size-4 tw:text-base tw:leading-4" aria-hidden="true"></span>
					</a>
				</div>
			</div>

			<div class="tw:relative tw:mt-10 tw:grid tw:grid-cols-3 tw:gap-px tw:overflow-hidden tw:rounded-2xl tw:bg-white/10">
				<?php foreach ( $stats as $stat ) : ?>
					<a href="<?php echo esc_url( $stat['url'] ); ?>" class="tw:group tw:block tw:bg-black/60 tw:px-5 tw:py-4 tw:text-white tw:no-underline tw:transition tw:hover:bg-white/5 tw:focus:shadow-none">
						<span class="tw:block tw:font-display tw:text-3xl tw:font-light"><?php echo esc_html( number_format_i18n( $stat['count'] ) ); ?></span>
						<span class="tw:mt-1 tw:block tw:text-[11px] tw:uppercase tw:tracking-widest tw:text-white/50 tw:group-hover:text-gold"><?php echo esc_html( $stat['label'] ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		</header>

		<hr class="wp-header-end">
		<?php settings_errors(); ?>

		<div class="tw:mt-8 tw:grid tw:gap-8 tw:lg:grid-cols-[220px_minmax(0,1fr)]">
			<style>
				<?php foreach ( array_keys( $sections ) as $section_key ) : ?>
					.vdp-admin:has(#vincent_show_<?php echo esc_attr( $section_key ); ?>:not(:checked)) .vdp-nav-<?php echo esc_attr( $section_key ); ?> { opacity: .4; }
				<?php endforeach; ?>
				/* Settings the selected Site design doesn't use: dimmed here, locked by the script after the form. */
				.vdp-design-note { display: none; }
				<?php foreach ( array_keys( vincent_option_fields()['design']['fields']['site_design']['choices'] ) as $vincent_design ) : ?>
					.vdp-admin:has(#vincent_site_design option[value="<?php echo esc_attr( $vincent_design ); ?>"]:checked) [data-vdp-designs]:not([data-vdp-designs~="<?php echo esc_attr( $vincent_design ); ?>"]),
					.vdp-admin:has(#vincent_site_design option[value="<?php echo esc_attr( $vincent_design ); ?>"]:checked) [data-vdp-nav-designs]:not([data-vdp-nav-designs~="<?php echo esc_attr( $vincent_design ); ?>"]) { opacity: .45; }
					.vdp-admin:has(#vincent_site_design option[value="<?php echo esc_attr( $vincent_design ); ?>"]:checked) [data-vdp-designs]:not([data-vdp-designs~="<?php echo esc_attr( $vincent_design ); ?>"]) .vdp-design-note { display: flex; }
				<?php endforeach; ?>
			</style>
			<nav class="tw:hidden tw:lg:block" aria-label="<?php esc_attr_e( 'Settings sections', 'dev-portfolio' ); ?>">
				<ol class="tw:sticky tw:top-[calc(var(--wp-admin--admin-bar--height,32px)+1.5rem)] tw:m-0 tw:flex tw:list-none tw:flex-col tw:gap-1 tw:p-0">
					<?php $i = 0; ?>
					<?php foreach ( $sections as $section_key => $section ) : ?>
						<li class="tw:m-0">
							<a href="#vdp-<?php echo esc_attr( $section_key ); ?>"<?php echo vincent_design_attr( $section, 'data-vdp-nav-designs' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?> class="vdp-nav-<?php echo esc_attr( $section_key ); ?> tw:group tw:flex tw:items-center tw:gap-3 tw:rounded-xl tw:px-3 tw:py-2.5 tw:text-sm tw:text-neutral-600 tw:no-underline tw:transition tw:hover:bg-white tw:hover:text-black tw:hover:shadow-sm tw:focus:shadow-none">
								<span class="tw:font-mono tw:text-[11px] tw:text-neutral-400 tw:group-hover:text-gold"><?php echo esc_html( str_pad( (string) ++$i, 2, '0', STR_PAD_LEFT ) ); ?></span>
								<?php echo esc_html( $section['title'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ol>
			</nav>

			<form action="options.php" method="post" class="tw:flex tw:min-w-0 tw:flex-col tw:gap-6">
				<?php settings_fields( 'vincent_options' ); ?>

				<?php $i = 0; ?>
				<?php foreach ( $sections as $section_key => $section ) : ?>
					<section id="vdp-<?php echo esc_attr( $section_key ); ?>"<?php echo vincent_design_attr( $section ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?> class="tw:overflow-hidden tw:rounded-3xl tw:bg-white tw:shadow-sm tw:ring-1 tw:ring-black/5">
						<div class="tw:flex tw:flex-wrap tw:items-start tw:justify-between tw:gap-4 tw:border-b tw:border-black/5 tw:px-6 tw:py-5 tw:md:px-8">
							<div class="tw:flex tw:items-start tw:gap-4">
								<span class="tw:flex tw:size-10 tw:shrink-0 tw:items-center tw:justify-center tw:rounded-xl tw:bg-black tw:text-gold">
									<span class="dashicons <?php echo esc_attr( $section['icon'] ?? 'dashicons-admin-generic' ); ?>" aria-hidden="true"></span>
								</span>
								<div>
									<h2 class="tw:m-0 tw:p-0 tw:font-display tw:text-xl tw:font-normal tw:text-neutral-900">
										<span class="tw:mr-1 tw:font-mono tw:text-xs tw:text-neutral-400"><?php echo esc_html( str_pad( (string) ++$i, 2, '0', STR_PAD_LEFT ) ); ?></span>
										<?php echo esc_html( $section['title'] ); ?>
									</h2>
									<?php if ( ! empty( $section['description'] ) ) : ?>
										<p class="tw:m-0 tw:mt-1 tw:text-sm tw:text-neutral-500"><?php echo esc_html( $section['description'] ); ?></p>
									<?php endif; ?>
									<?php vincent_design_note( $section ); ?>
								</div>
							</div>
							<div class="tw:flex tw:flex-wrap tw:items-center tw:gap-4">
								<?php if ( ! empty( $section['link'] ) ) : ?>
									<a href="<?php echo esc_url( $section['link']['url'] ); ?>" class="tw:inline-flex tw:items-center tw:gap-1 tw:rounded-full tw:border tw:border-black/10 tw:px-4 tw:py-1.5 tw:text-xs tw:font-medium tw:text-neutral-700 tw:no-underline tw:transition tw:hover:border-black tw:hover:text-black tw:focus:shadow-none">
										<?php echo esc_html( $section['link']['label'] ); ?> <span aria-hidden="true">→</span>
									</a>
								<?php endif; ?>
								<?php if ( vincent_is_page_section( $section ) ) : ?>
									<?php vincent_render_section_toggle( $section_key, $section['title'] ); ?>
								<?php endif; ?>
							</div>
						</div>

						<div class="vdp-fields tw:grid tw:gap-x-6 tw:gap-y-6 tw:px-6 tw:py-6 tw:transition-opacity tw:md:grid-cols-2 tw:md:px-8">
							<?php foreach ( $section['fields'] as $key => $field ) : ?>
								<div<?php echo vincent_design_attr( $field ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper. ?> class="<?php echo in_array( $field['type'], $wide, true ) ? 'tw:md:col-span-2' : ''; ?>">
									<label for="vincent_<?php echo esc_attr( $key ); ?>" class="tw:mb-2 tw:block tw:text-xs tw:font-semibold tw:uppercase tw:tracking-wider tw:text-neutral-700">
										<?php echo esc_html( $field['label'] ); ?>
									</label>
									<?php vincent_render_option_field( $key, $field ); ?>
									<?php if ( ! empty( $field['help'] ) ) : ?>
										<p class="tw:m-0 tw:mt-2 tw:text-xs tw:text-neutral-500"><?php echo esc_html( $field['help'] ); ?></p>
									<?php endif; ?>
									<?php vincent_design_note( $field ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach; ?>

				<div class="tw:sticky tw:bottom-4 tw:z-10 tw:flex tw:flex-wrap tw:items-center tw:justify-between tw:gap-4 tw:rounded-2xl tw:bg-black/90 tw:px-6 tw:py-4 tw:text-white tw:shadow-xl tw:backdrop-blur">
					<p class="tw:m-0 tw:text-sm tw:text-white/70"><?php esc_html_e( 'Saving updates the live front page immediately.', 'dev-portfolio' ); ?></p>
					<button type="submit" name="submit" class="tw:cursor-pointer tw:rounded-full tw:border-0 tw:bg-gold tw:px-8 tw:py-3 tw:text-xs tw:font-semibold tw:uppercase tw:tracking-[0.2em] tw:text-black tw:transition tw:hover:bg-white">
						<?php esc_html_e( 'Save changes', 'dev-portfolio' ); ?>
					</button>
				</div>
			</form>
		</div>
	</div>
	<?php
	// Lock (inert) the settings the selected design doesn't use. Inert fields are still submitted, so
	// their saved values are kept for when the design is switched back.
	wp_print_inline_script_tag(
		<<<'JS'
(() => {
	const select = document.getElementById("vincent_site_design");
	if (!select) return;
	const sync = () => {
		document.querySelectorAll("[data-vdp-designs]").forEach((el) => {
			el.inert = !el.dataset.vdpDesigns.split(" ").includes(select.value);
		});
	};
	select.addEventListener("change", sync);
	sync();
})();
JS
	);
}
