<?php
/**
 * Admin page output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminPage;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use Udb\Helpers\Array_Helper;
use Udb\Helpers\Icon_Helper;
use WP_Post;

/**
 * Class to setup admin page output.
 */
class Admin_Page_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-page';

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup admin page output.
	 */
	public function setup() {

		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'wp', array( $this, 'restrict_frontend' ) );

	}

	/**
	 * Setup menu.
	 */
	public function setup_menu() {

		// This is done separately in the PRO version to handle multisite support.
		if ( ! udb_is_pro_active() ) {
			$parent_pages  = $this->get_posts( 'parent' );
			$submenu_pages = $this->get_posts( 'submenu' );

			if ( ! empty( $parent_pages ) ) {
				$this->prepare_menu( $parent_pages );
			}

			if ( ! empty( $submenu_pages ) ) {
				$this->prepare_menu( $submenu_pages );
			}
		}

		// Hook for the pro version to run setup_menu.
		do_action( 'udb_admin_page_setup_menu', $this );

	}

	/**
	 * Get admin page posts by menu type.
	 *
	 * @param string $menu_type The menu type (parent/ submenu).
	 *
	 * @return array Array of admin page post objects.
	 */
	public function get_posts( $menu_type ) {
		$array_helper = new Array_Helper();

		$posts = get_posts(
			array(
				'post_type'      => 'udb_admin_page',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Only a few admin pages.
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

		foreach ( $posts as &$post ) {
			$post_id = $post->ID;

			$post->menu_type     = get_post_meta( $post_id, 'udb_menu_type', true );
			$post->menu_parent   = get_post_meta( $post_id, 'udb_menu_parent', true );
			$post->menu_order    = get_post_meta( $post_id, 'udb_menu_order', true );
			$post->menu_order    = $post->menu_order ? absint( $post->menu_order ) : 10;
			$post->icon_class    = get_post_meta( $post_id, 'udb_menu_icon', true );
			$post->custom_css    = get_post_meta( $post_id, 'udb_custom_css', true );
			$post->custom_js     = get_post_meta( $post->ID, 'udb_custom_js', true );
			$post->content_type  = get_post_meta( $post_id, 'udb_content_type', true );
			$post->html_content  = get_post_meta( $post_id, 'udb_html_content', true );
			$post->allowed_roles = get_post_meta( $post->ID, 'udb_allowed_roles', true );
			$post->allowed_roles = empty( $post->allowed_roles ) ? array( 'all' ) : $post->allowed_roles;
			$post->allowed_roles = $array_helper->clean_unserialize( $post->allowed_roles, 3 );

			$post->remove_page_title    = (int) get_post_meta( $post_id, 'udb_remove_page_title', true );
			$post->remove_page_margin   = (int) get_post_meta( $post_id, 'udb_remove_page_margin', true );
			$post->remove_admin_notices = get_post_meta( $post_id, 'udb_remove_admin_notices', true );
		}

		unset( $post );

		return $posts;
	}

	/**
	 * Register admin page's menu & submenu pages.
	 *
	 * @param array $posts Array of admin page post object (parent or submenu).
	 * @param bool  $from_multisite Whether the function is called by multisite function.
	 */
	public function prepare_menu( $posts, $from_multisite = false ) {

		$user_roles = wp_get_current_user()->roles;
		$user_roles = apply_filters( 'udb_admin_page_user_roles', $user_roles );

		foreach ( $posts as $post ) {
			$is_allowed = true;
			$is_allowed = apply_filters( 'udb_admin_page_role_is_allowed', $is_allowed, $user_roles, $post );

			if ( $is_allowed ) {
				$this->add_menu( $post, $from_multisite );
			}
		}
	}

	/**
	 * Register menu / submenu page based on its post.
	 *
	 * @param WP_Post $post The admin page post object.
	 * @param bool    $from_multisite Whether the function is called by multisite function.
	 */
	public function add_menu( $post, $from_multisite = false ) {

		// Plain text: HTML in a title (allowed for admins) would leak into the whole admin menu.
		$menu_title  = wp_strip_all_tags( $post->post_title );
		$menu_slug   = $post->post_name;
		$menu_type   = $post->menu_type;
		$menu_parent = $post->menu_parent;
		$menu_order  = $post->menu_order;
		$icon_class  = $post->icon_class;

		$icon_helper = new Icon_Helper();
		$is_svg_icon = $icon_class && ! $icon_helper->is_dashicon( $icon_class );

		// Lucide icons (and old Font Awesome ones) are added with CSS (add_menu_icon()). "none" keeps WordPress from printing an image for them.
		$menu_icon = $is_svg_icon ? 'none' : trim( str_ireplace( 'dashicons ', '', (string) $icon_class ) );

		$screen_id = 'udb_page_' . $menu_slug;

		if ( 'parent' === $menu_type ) {
			add_menu_page(
				$menu_title,
				$menu_title,
				'read',
				$screen_id,
				function () use ( $post, $from_multisite ) {
					$this->render_admin_page( $post, $from_multisite );
				},
				$menu_icon,
				$menu_order
			);

			if ( $is_svg_icon ) {
				add_action(
					'admin_head',
					function () use ( $menu_slug, $icon_class ) {
						$this->add_menu_icon( $menu_slug, $icon_class );
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
				function () use ( $post, $from_multisite ) {
					$this->render_admin_page( $post, $from_multisite );
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

				// Remove screen options.
				add_filter( 'screen_options_show_screen', '__return_false' );

				do_action( 'udb_admin_page_prepare_output', $post, $screen_id );

				add_action(
					'admin_print_footer_scripts',
					function () use ( $post ) {
						do_action( 'udb_admin_page_scripts', $post );
					}
				);
			}
		);

	}

	/**
	 * Render admin page.
	 *
	 * @param WP_Post $post The admin page post object.
	 * @param bool    $from_multisite Whether the function is called by multisite function.
	 */
	public function render_admin_page( $post, $from_multisite = false ) {

		require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-page/templates/admin-page.php';

	}

	/**
	 * Add a Lucide menu icon: drawn with a CSS mask in the menu's own icon color.
	 *
	 * @param string $menu_slug The menu slug.
	 * @param string $icon_class The saved icon: a Lucide name or old Font Awesome classes.
	 */
	public function add_menu_icon( $menu_slug, $icon_class ) {

		// The data URL is fully percent-encoded: no quotes or parentheses that could end the CSS value.
		$mask = 'url(' . ( new Icon_Helper() )->mask_url( $icon_class ) . ')';
		?>

		<style>
		#adminmenu #toplevel_page_udb_page_<?php echo esc_attr( $menu_slug ); ?> div.wp-menu-image::before {
			content: "";
			display: block;
			box-sizing: border-box;
			width: 20px;
			height: 20px;
			margin: 7px auto 0;
			padding: 0;
			background-color: currentColor;
			-webkit-mask: <?php echo esc_attr( $mask ); ?> center / 20px 20px no-repeat;
			mask: <?php echo esc_attr( $mask ); ?> center / 20px 20px no-repeat;
		}
		</style>

		<?php

	}

	/**
	 * Prevent admin pages from being accessed from frontend.
	 */
	public function restrict_frontend() {
		if ( is_user_logged_in() || ! is_singular( 'udb_admin_page' ) ) {
			return;
		}

		wp_safe_redirect( home_url() );
		exit;
	}

}
