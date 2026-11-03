<?php
/**
 * Contact form endpoint for the Neumorphism design: POST /wp-json/vincent/v1/contact
 * (/?rest_route=/vincent/v1/contact with plain permalinks).
 *
 * The recipient is the Contact → E-mail setting (or the admin e-mail). It is only used here, on
 * the server, so it never appears in the page or the JS bundle.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Topics offered in the form's select: the service titles plus "Something else".
 *
 * @return string[]
 */
function vincent_contact_topics() {
	$topics   = wp_list_pluck( vincent_get_services(), 'title' );
	$topics[] = __( 'Something else', 'dev-portfolio' );

	return array_values( array_unique( array_filter( $topics ) ) );
}

add_action( 'rest_api_init', 'vincent_register_contact_route' );

/**
 * Register the route, only while the Neumorphism design (the only one with a form) is active.
 */
function vincent_register_contact_route() {
	if ( 'neumorphism' !== vincent_site_design() ) {
		return;
	}

	register_rest_route(
		'vincent/v1',
		'/contact',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'vincent_handle_contact',
			// Same nonce core checks for cookie auth, so it works for visitors (uid 0) and logged-in users.
			'permission_callback' => static function ( WP_REST_Request $request ) {
				return wp_verify_nonce( (string) $request->get_header( 'X-WP-Nonce' ), 'wp_rest' )
					? true
					: new WP_Error( 'vincent_contact_nonce', __( 'Your session expired. Refresh the page and try again.', 'dev-portfolio' ), array( 'status' => 403 ) );
			},
			'args'                => array(
				'name'            => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => static function ( $value ) {
						$value = trim( sanitize_text_field( (string) $value ) );
						return '' !== $value && mb_strlen( $value ) <= 100
							? true
							: new WP_Error( 'invalid_name', __( 'Please enter your name.', 'dev-portfolio' ) );
					},
				),
				'email'           => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_email',
					'validate_callback' => static function ( $value ) {
						return is_email( sanitize_email( (string) $value ) )
							? true
							: new WP_Error( 'invalid_email', __( 'Please enter a valid e-mail address.', 'dev-portfolio' ) );
					},
				),
				'topic'           => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => static function ( $value ) {
						return in_array( sanitize_text_field( (string) $value ), vincent_contact_topics(), true )
							? true
							: new WP_Error( 'invalid_topic', __( 'Please choose a topic.', 'dev-portfolio' ) );
					},
				),
				'message'         => array(
					'type'              => 'string',
					'required'          => true,
					'sanitize_callback' => 'sanitize_textarea_field',
					'validate_callback' => static function ( $value ) {
						$value = trim( sanitize_textarea_field( (string) $value ) );
						return '' !== $value && mb_strlen( $value ) <= 5000
							? true
							: new WP_Error( 'invalid_message', __( 'Please write a message (up to 5000 characters).', 'dev-portfolio' ) );
					},
				),
				// Honeypot: hidden from people, so anything in it came from a bot.
				'website'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'recaptcha_token' => array(
					'type'              => 'string',
					'default'           => '',
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}

/**
 * Send the message.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function vincent_handle_contact( WP_REST_Request $request ) {
	// Light rate limit: 5 attempts per 10 minutes per IP (hashed, so no raw IPs are stored).
	$ip       = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$rate_key = 'vincent_contact_' . md5( $ip . wp_salt( 'nonce' ) );
	$attempts = (int) get_transient( $rate_key );
	if ( $attempts >= 5 ) {
		return new WP_Error( 'vincent_contact_rate', __( 'Too many messages. Please try again in a few minutes.', 'dev-portfolio' ), array( 'status' => 429 ) );
	}
	set_transient( $rate_key, $attempts + 1, 10 * MINUTE_IN_SECONDS );

	$sent = array(
		'ok'      => true,
		'message' => __( 'Thanks! Your message is on its way. I’ll get back to you soon.', 'dev-portfolio' ),
	);

	// Pretend it worked, so the bot learns nothing.
	if ( '' !== trim( (string) $request->get_param( 'website' ) ) ) {
		return rest_ensure_response( $sent );
	}

	if ( vincent_recaptcha_enabled_for( 'contact' ) ) {
		$verify = vincent_verify_recaptcha( (string) $request->get_param( 'recaptcha_token' ) );
		if ( is_wp_error( $verify ) ) {
			return $verify;
		}
	}

	$name    = trim( (string) $request->get_param( 'name' ) );
	$email   = (string) $request->get_param( 'email' );
	$topic   = (string) $request->get_param( 'topic' );
	$message = trim( (string) $request->get_param( 'message' ) );

	$to = sanitize_email( (string) vincent_option( 'email' ) );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$site = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	/* translators: 1: site name, 2: topic. */
	$subject = sprintf( __( '[%1$s] New enquiry: %2$s', 'dev-portfolio' ), $site, $topic );
	$body    = sprintf(
		"%s: %s\n%s: %s\n%s: %s\n\n%s\n\n--\n%s",
		__( 'Name', 'dev-portfolio' ),
		$name,
		__( 'E-mail', 'dev-portfolio' ),
		$email,
		__( 'Topic', 'dev-portfolio' ),
		$topic,
		$message,
		/* translators: %s: home URL. */
		sprintf( __( 'Sent from the contact form on %s', 'dev-portfolio' ), home_url( '/' ) )
	);
	// Name and e-mail are sanitized single-line values, so they can't add headers. wp_mail() splits
	// Reply-To on commas, so drop those (and other address punctuation) from the display name.
	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', trim( str_replace( array( '"', '<', '>', ',', ';' ), '', $name ) ), $email ),
	);

	if ( ! wp_mail( $to, $subject, $body, $headers ) ) {
		return new WP_Error( 'vincent_contact_mail', __( 'Your message could not be sent right now. Please try again later.', 'dev-portfolio' ), array( 'status' => 500 ) );
	}

	return rest_ensure_response( $sent );
}
