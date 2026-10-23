<?php
/**
 * Project and Service post types with their meta boxes.
 *
 * @package Dev_Portfolio
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', 'vincent_register_post_types' );

/**
 * Register the vincent_project and vincent_service post types and their meta.
 */
function vincent_register_post_types() {
	register_post_type(
		'vincent_project',
		array(
			'labels'       => array(
				'name'          => __( 'Projects', 'dev-portfolio' ),
				'singular_name' => __( 'Project', 'dev-portfolio' ),
				'add_new_item'  => __( 'Add New Project', 'dev-portfolio' ),
				'edit_item'     => __( 'Edit Project', 'dev-portfolio' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'project' ),
			'menu_icon'    => 'dashicons-portfolio',
			'show_in_rest' => true,
			'supports'     => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'vincent_service',
		array(
			'labels'              => array(
				'name'          => __( 'Services', 'dev-portfolio' ),
				'singular_name' => __( 'Service', 'dev-portfolio' ),
				'add_new_item'  => __( 'Add New Service', 'dev-portfolio' ),
				'edit_item'     => __( 'Edit Service', 'dev-portfolio' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-hammer',
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'excerpt', 'page-attributes' ),
		)
	);

	$auth = static function () {
		return current_user_can( 'edit_posts' );
	};

	register_post_meta(
		'vincent_project',
		'_vincent_url',
		array(
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'esc_url_raw',
			'auth_callback'     => $auth,
		)
	);
	register_post_meta(
		'vincent_project',
		'_vincent_bg_image',
		array(
			'type'              => 'integer',
			'single'            => true,
			'sanitize_callback' => 'absint',
			'auth_callback'     => $auth,
		)
	);
	register_post_meta(
		'vincent_project',
		'_vincent_frameworks',
		array(
			'type'          => 'array',
			'single'        => true,
			'auth_callback' => $auth,
		)
	);
	register_post_meta(
		'vincent_service',
		'_vincent_items',
		array(
			'type'          => 'array',
			'single'        => true,
			'auth_callback' => $auth,
		)
	);
}

add_action( 'add_meta_boxes', 'vincent_add_meta_boxes' );

/**
 * Add the project and service detail boxes.
 */
function vincent_add_meta_boxes() {
	add_meta_box( 'vincent_project_details', __( 'Project details', 'dev-portfolio' ), 'vincent_render_project_box', 'vincent_project', 'normal', 'high' );
	add_meta_box( 'vincent_service_details', __( 'Service items', 'dev-portfolio' ), 'vincent_render_service_box', 'vincent_service', 'normal', 'high' );
}

/**
 * Project details meta box.
 *
 * @param WP_Post $post Current post.
 */
function vincent_render_project_box( $post ) {
	wp_nonce_field( 'vincent_save_meta', 'vincent_meta_nonce' );

	$url        = get_post_meta( $post->ID, '_vincent_url', true );
	$bg_image   = (int) get_post_meta( $post->ID, '_vincent_bg_image', true );
	$frameworks = (array) get_post_meta( $post->ID, '_vincent_frameworks', true );
	?>
	<p>
		<label for="vincent_url"><strong><?php esc_html_e( 'Project URL', 'dev-portfolio' ); ?></strong></label><br>
		<input type="url" id="vincent_url" name="vincent_url" class="widefat" value="<?php echo esc_attr( $url ); ?>" placeholder="https://">
	</p>
	<p>
		<label for="vincent_frameworks"><strong><?php esc_html_e( 'Tech stack', 'dev-portfolio' ); ?></strong></label><br>
		<input type="text" id="vincent_frameworks" name="vincent_frameworks" class="widefat" value="<?php echo esc_attr( implode( ', ', array_filter( $frameworks ) ) ); ?>">
		<span class="description"><?php esc_html_e( 'Comma-separated, e.g. React, Node.js, Tailwind CSS', 'dev-portfolio' ); ?></span>
	</p>
	<div>
		<strong><?php esc_html_e( 'Background image (mobile card)', 'dev-portfolio' ); ?></strong>
		<?php vincent_media_field( 'vincent_bg_image', 'vincent_bg_image', $bg_image ); ?>
		<span class="description"><?php esc_html_e( 'The featured image is the screenshot shown in the hover preview.', 'dev-portfolio' ); ?></span>
	</div>
	<p class="description"><?php esc_html_e( 'The excerpt is used as the project description. Use "Order" under Page Attributes to sort.', 'dev-portfolio' ); ?></p>
	<?php
}

/**
 * Service items meta box.
 *
 * @param WP_Post $post Current post.
 */
function vincent_render_service_box( $post ) {
	wp_nonce_field( 'vincent_save_meta', 'vincent_meta_nonce' );

	$items = (array) get_post_meta( $post->ID, '_vincent_items', true );
	?>
	<p>
		<label for="vincent_items"><strong><?php esc_html_e( 'Items', 'dev-portfolio' ); ?></strong></label><br>
		<textarea id="vincent_items" name="vincent_items" class="widefat" rows="5"><?php echo esc_textarea( vincent_pairs_to_text( $items, 'title', 'description' ) ); ?></textarea>
		<span class="description"><?php esc_html_e( 'One per line: Title | description', 'dev-portfolio' ); ?></span>
	</p>
	<p class="description"><?php esc_html_e( 'The excerpt is used as the service description.', 'dev-portfolio' ); ?></p>
	<?php
}

add_action( 'save_post_vincent_project', 'vincent_save_project_meta' );

/**
 * Save project meta.
 *
 * @param int $post_id Post ID.
 */
function vincent_save_project_meta( $post_id ) {
	if ( ! vincent_can_save_meta( $post_id ) ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified in vincent_can_save_meta().
	$url        = isset( $_POST['vincent_url'] ) ? esc_url_raw( wp_unslash( $_POST['vincent_url'] ) ) : '';
	$bg_image   = isset( $_POST['vincent_bg_image'] ) ? absint( $_POST['vincent_bg_image'] ) : 0;
	$frameworks = isset( $_POST['vincent_frameworks'] ) ? sanitize_text_field( wp_unslash( $_POST['vincent_frameworks'] ) ) : '';
	// phpcs:enable

	update_post_meta( $post_id, '_vincent_url', $url );
	update_post_meta( $post_id, '_vincent_bg_image', $bg_image );
	update_post_meta( $post_id, '_vincent_frameworks', array_values( array_filter( array_map( 'trim', explode( ',', $frameworks ) ) ) ) );
}

add_action( 'save_post_vincent_service', 'vincent_save_service_meta' );

/**
 * Save service meta.
 *
 * @param int $post_id Post ID.
 */
function vincent_save_service_meta( $post_id ) {
	if ( ! vincent_can_save_meta( $post_id ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified in vincent_can_save_meta().
	$text = isset( $_POST['vincent_items'] ) ? sanitize_textarea_field( wp_unslash( $_POST['vincent_items'] ) ) : '';

	update_post_meta( $post_id, '_vincent_items', vincent_parse_pairs( $text, 'title', 'description' ) );
}

/**
 * Shared nonce, autosave and capability check for meta box saves.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function vincent_can_save_meta( $post_id ) {
	if ( ! isset( $_POST['vincent_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['vincent_meta_nonce'] ), 'vincent_save_meta' ) ) {
		return false;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}
	return current_user_can( 'edit_post', $post_id );
}

/**
 * Render an attachment-ID field with a media library picker.
 *
 * @param string $id    Input id.
 * @param string $name  Input name.
 * @param int    $value Attachment ID.
 */
function vincent_media_field( $id, $name, $value ) {
	$preview = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';
	?>
	<div class="vincent-media-field" style="margin:.5em 0">
		<img class="vincent-media-preview" src="<?php echo esc_url( $preview ); ?>" alt="" style="max-width:200px;height:auto;display:<?php echo $preview ? 'block' : 'none'; ?>;margin-bottom:.5em">
		<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ? $value : '' ); ?>">
		<button type="button" class="button vincent-media-select"><?php esc_html_e( 'Select image', 'dev-portfolio' ); ?></button>
		<button type="button" class="button-link vincent-media-remove"><?php esc_html_e( 'Remove', 'dev-portfolio' ); ?></button>
	</div>
	<?php
}

add_action( 'admin_enqueue_scripts', 'vincent_admin_assets' );

/**
 * Load the media picker on screens that use vincent_media_field().
 *
 * @param string $hook Admin page hook.
 */
function vincent_admin_assets( $hook ) {
	$screen   = get_current_screen();
	$is_edit  = $screen && 'vincent_project' === $screen->post_type && in_array( $hook, array( 'post.php', 'post-new.php' ), true );
	$is_theme = 'appearance_page_dev-portfolio' === $hook;

	if ( ! $is_edit && ! $is_theme ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script( 'vincent-admin-media', VINCENT_URI . '/assets/admin-media.js', array( 'jquery' ), VINCENT_VERSION, true );
}
