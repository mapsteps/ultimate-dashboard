<?php
/**
 * Implement the admin page's hooked functions.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Setup menu.
 */
function udb_admin_page_setup_menu() {

	if ( apply_filters( 'udb_font_awesome', true ) ) {
		// Font Awesome.
		wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/font-awesome.min.css', array(), '5.14.0' );
		wp_enqueue_style( 'font-awesome-shims', ULTIMATE_DASHBOARD_PLUGIN_URL . 'assets/css/v4-shims.min.css', array(), '5.14.0' );
	}

	$parent_pages  = udb_admin_page_get_posts( 'parent' );
	$submenu_pages = udb_admin_page_get_posts( 'submenu' );

	if ( ! empty( $parent_pages ) ) {
		udb_admin_page_prepare_menu( $parent_pages );
	}

	if ( ! empty( $submenu_pages ) ) {
		udb_admin_page_prepare_menu( $submenu_pages );
	}

}
add_action( 'admin_menu', 'udb_admin_page_setup_menu' );

/**
 * Get admin page posts by menu type.
 *
 * @param string $menu_type The menu type (parent/ submenu).
 * @return array Array of admin page post objects.
 */
function udb_admin_page_get_posts( $menu_type ) {
	$posts = get_posts(
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

	$posts = $posts ? $posts : array();

	foreach ( $posts as &$post ) {
		$post_id = $post->ID;

		$post->menu_type     = get_post_meta( $post_id, 'udb_menu_type', true );
		$post->menu_parent   = get_post_meta( $post_id, 'udb_menu_parent', true );
		$post->menu_order    = get_post_meta( $post_id, 'udb_menu_order', true );
		$post->menu_order    = $post->menu_order ? absint( $post->menu_order ) : 10;
		$post->icon_class    = get_post_meta( $post_id, 'udb_menu_icon', true );
		$post->allowed_roles = get_post_meta( $post_id, 'udb_allowed_roles', true );
		$post->allowed_roles = '' === $post->allowed_roles ? array( 'all' ) : $post->allowed_roles;
		$post->custom_css    = get_post_meta( $post_id, 'udb_custom_css', true );
		$post->custom_js     = get_post_meta( $post_id, 'udb_custom_js', true );
		$post->content_type  = get_post_meta( $post_id, 'udb_content_type', true );
		$post->html_content  = get_post_meta( $post_id, 'udb_html_content', true );

		$post->remove_page_title    = (int) get_post_meta( $post_id, 'udb_remove_page_title', true );
		$post->remove_page_margin   = (int) get_post_meta( $post_id, 'udb_remove_page_margin', true );
		$post->remove_admin_notices = get_post_meta( $post_id, 'udb_remove_admin_notices', true );
	}

	return $posts;
}

/**
 * Register admin page's menu & submenu pages.
 *
 * @param array $posts Array of admin page post object (parent or submenu).
 */
function udb_admin_page_prepare_menu( $posts ) {

	$user_roles = wp_get_current_user()->roles;

	foreach ( $posts as $post ) {
		$is_allowed = false;

		if ( in_array( 'all', $post->allowed_roles, true ) ) {
			$is_allowed = true;
		} else {
			foreach ( $user_roles as $user_role ) {
				if ( in_array( $user_role, $post->allowed_roles, true ) ) {
					$is_allowed = true;
					break;
				}
			}
		}

		if ( $is_allowed ) {
			udb_admin_page_add_menu( $post );
		}
	}
}

/**
 * Register menu / submenu page based on it's post.
 *
 * @param WP_Post $post The admin page post object.
 */
function udb_admin_page_add_menu( $post ) {

	$menu_title  = $post->post_title;
	$menu_slug   = $post->post_name;
	$menu_type   = $post->menu_type;
	$menu_parent = $post->menu_parent;
	$menu_order  = $post->menu_order;
	$icon_class  = $post->icon_class;

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
			function () use ( $post ) {
				udb_render_admin_page( $post );
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
		}
	} else {
		add_submenu_page(
			$menu_parent,
			$menu_title,
			$menu_title,
			'read',
			$screen_id,
			function () use ( $post ) {
				udb_render_admin_page( $post );
			},
			$menu_order
		);
	}

	add_action(
		'current_screen',
		function () use ( $post, $screen_id ) {
			global $current_screen;

			if ( ! is_object( $current_screen ) || ! property_exists( $current_screen, 'id' ) ) {
				return;
			}

			if ( false === stripos( $current_screen->id, '_' . $screen_id ) ) {
				return;
			}

			if ( $post->remove_admin_notices ) {
				remove_all_actions( 'admin_notices' );
			}

			add_action(
				'admin_print_footer_scripts',
				function () use ( $post ) {
					echo '<script>';
					echo $post->custom_js;
					echo '</script>';
				}
			);
		}
	);

}

/**
 * Render admin page.
 *
 * @param WP_Post $post The admin page post object.
 */
function udb_render_admin_page( $post ) {

	require ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/admin-page/templates/admin-page.php';

}

/**
 * Add FontAwesome menu icon.
 *
 * @param string $menu_slug The menu slug.
 * @param string $icon_class The icon class.
 */
function udb_admin_page_add_menu_icon( $menu_slug, $icon_class ) {
	$unicodes = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'assets/json/fontawesome5-unicodes.json' );
	$unicodes = json_decode( $unicodes, true );
	$unicodes = $unicodes ? $unicodes : array();

	// Compatibility.
	$unicodes_fa4 = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'assets/json/fontawesome4-unicodes.json' );
	$unicodes_fa4 = json_decode( $unicodes_fa4, true );
	$unicodes_fa4 = $unicodes_fa4 ? $unicodes_fa4 : array();

	$icon_unicode = '\f013';

	if ( isset( $unicodes[ $icon_class ] ) ) {
		$icon_unicode = $unicodes[ $icon_class ];
	} else {
		if ( isset( $unicodes[ $icon_class ] ) ) {
			$icon_unicode = $unicodes_fa4[ $icon_class ];
		}
	}
	?>

	<style>
	#toplevel_page_udb_page_<?php echo esc_attr( $menu_slug ); ?> .wp-menu-image::before {
		content: "<?php echo esc_attr( $icon_unicode ); ?>";
		font-family: "Font Awesome 5 Free";
	}
	</style>

	<?php
}

/**
 * Prevent admin pages from being accessed from frontend.
 */
function udb_admin_page_restrict_frontend() {
	if ( is_user_logged_in() || ! is_singular( 'udb_admin_page' ) ) {
		return;
	}

	wp_safe_redirect( home_url() );
}
add_action( 'wp', 'udb_admin_page_restrict_frontend' );
