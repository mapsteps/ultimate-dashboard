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

		if ( ! empty( $posts ) && 'parent' === $menu_type && apply_filters( 'udb_font_awesome', true ) ) {
			// Font Awesome.
			wp_enqueue_style( 'font-awesome', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/font-awesome.min.css', array(), '5.14.0' );
			wp_enqueue_style( 'font-awesome-shims', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/v4-shims.min.css', array(), '5.14.0' );
		}

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

		$menu_title  = $post->post_title;
		$menu_slug   = $post->post_name;
		$menu_type   = $post->menu_type;
		$menu_parent = $post->menu_parent;
		$menu_order  = $post->menu_order;
		$icon_class  = $post->icon_class;

		if ( false !== stripos( $icon_class, 'dashicons ' ) ) {
			$menu_icon = str_ireplace( 'dashicons ', '', $icon_class );
		} else {
			$menu_icon = 'font-awesome';
		}

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

			if ( 'font-awesome' === $menu_icon ) {
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
	 * Add FontAwesome menu icon.
	 *
	 * @param string $menu_slug The menu slug.
	 * @param string $icon_class The icon class.
	 */
	public function add_menu_icon( $menu_slug, $icon_class ) {
		$unicodes = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/json/fontawesome5-unicodes.json' );
		$unicodes = json_decode( $unicodes, true );
		$unicodes = is_null( $unicodes ) ? array() : $unicodes;

		// Compatibility.
		$unicodes_fa4 = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/json/fontawesome4-unicodes.json' );
		$unicodes_fa4 = json_decode( $unicodes_fa4, true );
		$unicodes_fa4 = is_null( $unicodes_fa4 ) ? array() : $unicodes_fa4;

		$icon_unicode = '\f013';

		if ( isset( $unicodes[ $icon_class ] ) ) {
			$icon_unicode = $unicodes[ $icon_class ];
		} elseif ( isset( $unicodes_fa4[ $icon_class ] ) ) {
			$icon_unicode = $unicodes_fa4[ $icon_class ];
		}
		?>

		<style>
		#toplevel_page_udb_page_<?php echo esc_attr( $menu_slug ); ?> .wp-menu-image::before {
			content: "<?php echo esc_attr( $icon_unicode ); ?>";

			<?php if ( false !== stripos( $icon_class, 'fab ' ) ) : ?>
				font-family: "Font Awesome 5 Brands";
			<?php else : ?>
				font-family: "Font Awesome 5 Free";
			<?php endif; ?>

			<?php if ( false !== stripos( $icon_class, 'fas ' ) ) : ?>
				font-weight: 900;
			<?php elseif ( false !== stripos( $icon_class, 'far ' ) ) : ?>
				font-weight: 400;
			<?php endif; ?>
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
	}

}
