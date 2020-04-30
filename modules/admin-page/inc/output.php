<?php
/**
 * Implement the admin page's hooked functions.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Setup menu.
 */
function udb_admin_page_setup_menu() {
	udb_admin_page_prepare_menu( 'parent' );
	udb_admin_page_prepare_menu( 'submenu' );
}
add_action( 'admin_menu', 'udb_admin_page_setup_menu' );

/**
 * Register admin page's menu & submenu pages.
 *
 * @param string $menu_type The menu type (parent or submenu).
 */
function udb_admin_page_prepare_menu( $menu_type) {
	$query = new WP_Query(
		array(
			'post_type'      => 'udb_admin_page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'   => 'udb_is_active',
					'value' => 1,
				),
				array(
					'key'   => 'udb_menu_type',
					'value' => $menu_type,
				),
			),
		)
	);

	if ( ! $query->have_posts() ) {
		return;
	}

	$user_roles = wp_get_current_user()->roles;

	while ( $query->have_posts() ) {
		$query->the_post();

		$post_id       = get_the_ID();
		$allowed_roles = get_post_meta( $post_id, 'udb_allowed_roles', true );
		$allowed_roles = '' === $allowed_roles ? array( 'all' ) : $allowed_roles;
		$is_allowed    = false;

		if (in_array( 'all', $allowed_roles, true )) {
			$is_allowed = true;
		} else {
			foreach ( $user_roles as $user_role ) {
				if ( in_array( $user_role, $allowed_roles, true ) ) {
					$is_allowed = true;
					break;
				}
			}
		}

		if ( $is_allowed ) {
			udb_admin_page_add_menu( $post_id, $menu_type );
		}
	}
}

/**
 * Register menu / submenu page based on it's post_id.
 *
 * @param int $post_id The admin page's post_id.
 */
function udb_admin_page_add_menu( $post_id ) {
	global $post;

	$menu_title  = $post->post_title;
	$menu_slug   = $post->post_name;
	$menu_type   = get_post_meta( $post_id, 'udb_menu_type', true );
	$menu_parent = get_post_meta( $post_id, 'udb_menu_parent', true );
	$menu_order  = get_post_meta( $post_id, 'udb_menu_order', true );
	$menu_order  = $menu_order ? absint( $menu_order ) : 10;
	$icon_class  = get_post_meta( $post_id, 'udb_menu_icon', true );

	if ( false !== stripos( $icon_class, 'dashicons ' ) ) {
		$menu_icon = str_ireplace( 'dashicons ', '', $icon_class );
	} else {
		$menu_icon = 'none';
	}

	$screen_id = 'udb_page_' . $menu_slug;

	if ( 'parent' === $menu_type ) {
		add_menu_page(
			$menu_title,
			$menu_title,
			'read',
			$screen_id,
			function () use ( $post_id) {
				udb_render_admin_page( $post_id );
			},
			$menu_icon,
			$menu_order
		);

		if ( 'none' === $menu_icon ) {
			add_action(
				'admin_head',
				function () use ( $menu_slug, $icon_class ) {
					udb_admin_page_add_menu_icon( $menu_slug, $icon_class );
				}
			);

			// Font Awesome.
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '4.7.0' );
		}
	} else {
		add_submenu_page(
			$menu_parent,
			$menu_title,
			$menu_title,
			'read',
			$screen_id,
			function () use ( $post_id) {
				udb_render_admin_page( $post_id );
			},
			$menu_order
		);
	}

	add_action(
		'current_screen',
		function () use ( $post_id, $screen_id) {
			global $current_screen;

			if ( ! is_object( $current_screen ) || ! property_exists( $current_screen, 'id' )) {
				return;
			}

			if ( false === stripos( $current_screen->id, '_' . $screen_id ) ) {
				return;
			}

			if ( get_post_meta( $post_id, 'udb_remove_admin_notices', true ) ) {
				remove_all_actions( 'admin_notices' );
			}

			add_action(
				'admin_print_footer_scripts',
				function () use ( $post_id) {
					echo '<script>';
					echo get_post_meta( $post_id, 'udb_custom_js', true );
					echo '</script>';
				}
			);
		}
	);

}

/**
 * Render admin page.
 *
 * @param int $post_id ID of the post being displayed as admin page.
 */
function udb_render_admin_page( $post_id) {
	require ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/templates/admin-page.php';
}

/**
 * Add FontAwesome menu icon.
 *
 * @param string $menu_slug The menu slug.
 * @param string $icon_class The icon class.
 */
function udb_admin_page_add_menu_icon( $menu_slug, $icon_class ) {
	$unicodes = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'assets/json/fontawesome4-unicodes.json' );
	$unicodes = json_decode( $unicodes, true );
	$unicodes = $unicodes ? $unicodes : array();

	$icon_unicode = isset( $unicodes[ $icon_class ] ) ? $unicodes[ $icon_class ] : '\f006';
	?>

	<style>
	#toplevel_page_udb_page_<?php echo esc_attr( $menu_slug ); ?> .wp-menu-image::before {
		content: "<?php echo esc_attr( $icon_unicode ); ?>";
		font-family: FontAwesome;
	}
	</style>

	<?php
}

/**
 * Prevent admin pages from being accessed from frontend.
 */
function udb_admin_page_restrict_frontend() {
	if ( ! is_singular( 'udb_admin_page' )) {
		return;
	}

	wp_safe_redirect( home_url() );
}
add_action( 'wp', 'udb_admin_page_restrict_frontend' );
