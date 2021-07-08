<?php
/**
 * Login url output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Login_Url;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use WP_Query;
use Udb\Base\Base_Output;

/**
 * Class to setup login url output.
 */
class Login_Url_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * Whether current page is the old login url or not.
	 *
	 * @var bool
	 */
	public $is_old_page = false;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Get instance of the class.
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/login-url';

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup widgets output.
	 */
	public function setup() {

		add_action( 'plugins_loaded', array( $this, 'change_url' ) );
		add_action( 'wp_loaded', array( $this, 'change_url' ) );

	}

	/**
	 * Change url.
	 */
	public function change_url() {

		$settings   = $this->option( 'settings' );
		$login_slug = isset( $settings['login_url_slug'] ) && $settings['login_url_slug'] ? $settings['login_url_slug'] : '';

		if ( ! $login_slug ) {
			return;
		}

		$uri = rawurlencode( $_SERVER['REQUEST_URI'] );

		if ( ! is_multisite() && ( false !== stripos( $uri, 'wp-signup' ) || false !== stripos( $uri, 'wp-activate' ) ) ) {
			return;
		}

		$request      = wp_parse_url( $uri );
		$request_path = isset( $request_path ) ? untrailingslashit( $request['path'] ) : '';

		$using_permalink   = get_option( 'permalink_structure' ) ? true : false;
		$has_new_slug      = isset( $_GET[ $login_slug ] ) && $_GET[ $login_slug ] ? true : false;
		$has_old_slug      = false !== stripos( $uri, 'wp-login.php' ) ? true : false;
		$has_register_slug = false !== stripos( $uri, 'wp-register.php' ) ? true : false;

		global $pagenow;

		if ( ! is_admin() && ( $has_old_slug || site_url( 'wp-login', 'relative' ) === $request_path ) ) {
			// If current page is old login page, set $pagenow to be index.php so it's not login page anymore.
			$pagenow = 'index.php';

			$this->is_old_page      = true;
			$_SERVER['REQUEST_URI'] = $this->maybe_trailingslashit( '/' . str_repeat( '-/', 10 ) );
		} elseif ( home_url( $this->new_login_url(), 'relative' ) === $request_path || ( ! $using_permalink && $has_new_slug ) ) {
			// If current page is new login page, let WordPress know if this is a login page.
			$pagenow = 'wp-login.php';
		} elseif ( ! is_admin() && ( $has_register_slug || site_url( 'wp-register', 'relative' ) === $request_path ) ) {
			$pagenow = 'index.php';

			$this->is_old_page      = true;
			$_SERVER['REQUEST_URI'] = $this->maybe_trailingslashit( '/' . str_repeat( '-/', 10 ) );
		}

	}

	/**
	 * Get new login url.
	 *
	 * @return string
	 */
	public function new_login_url() {

		$settings   = $this->option( 'settings' );
		$login_slug = isset( $settings['login_url_slug'] ) ? $settings['login_url_slug'] : '';
		$login_url  = site_url( $login_slug );

		if ( get_option( 'permalink_structure' ) ) {
			return $this->maybe_trailingslashit( $login_url );
		}

		return $login_url;

	}

	/**
	 * Get the redirect url of old login page.
	 *
	 * @return string
	 */
	public function old_login_redirect_url() {

		$settings      = $this->option( 'settings' );
		$redirect_slug = isset( $settings['old_login_url_redirect_slug'] ) ? $settings['old_login_url_redirect_slug'] : '';
		$redirect_url  = site_url( $redirect_slug );

		if ( get_option( 'permalink_structure' ) ) {
			return $this->maybe_trailingslashit( $redirect_url );
		}

		return $redirect_url;

	}

	/**
	 * Return a string with or without trailing slash based on permalink structure.
	 *
	 * @param string $string The string to return.
	 * @return string
	 */
	public function maybe_trailingslashit( $string ) {

		$permalink_structure = get_option( 'permalink_structure' );
		$use_trailingslash   = '/' === substr( $permalink_structure, -1, 1 ) ? true : false;

		return ( $use_trailingslash ? trailingslashit( $string ) : untrailingslashit( $string ) );

	}

	/**
	 * Set redirects.
	 */
	public function set_redirects() {

		global $pagenow;

		if ( isset( $_GET['action'] ) && 'postpass' === $_GET['action'] && isset( $_GET['post_password'] ) ) {
			return;
		}

		$request      = wp_parse_url( rawurlencode( $_SERVER['REQUEST_URI'] ) );
		$request_path = $request['path'];

		if ( is_admin() && ! is_user_logged_in() && ! wp_doing_ajax() && 'admin-post.php' !== $pagenow && '/wp-admin/options.php' !== $request_path ) {
			wp_safe_redirect( $this->old_login_redirect_url() );
			exit;
		}

		$query_string     = isset( $_SERVER['QUERY_STRING'] ) ? $_SERVER['QUERY_STRING'] : '';
		$add_query_string = $query_string ? '?' . $query_string : '';

		if ( 'wp-login.php' === $pagenow && $request_path !== $this->maybe_trailingslashit( $request_path ) && get_option( 'permalink_structure' ) ) {
			wp_safe_redirect(
				$this->maybe_trailingslashit( $this->old_login_redirect_url() ) . $add_query_string
			);
			exit;
		} elseif ( $this->is_old_page ) {
			$referer  = wp_get_referer();
			$referers = wp_parse_url( $referer );

			$referer_contains_activate_url = false !== stripos( $referer, 'wp-activate.php' ) ? true : false;

			if ( $referer_contains_activate_url && ! empty( $referers['query'] ) ) {
				parse_str( $referers['query'], $referer_queries );

				$signup_key           = $referer_queries['key'];
				$wpmu_activate_signup = wpmu_activate_signup( $signup_key );

				@require_once WPINC . '/ms-functions.php';

				if ( ! empty( $signup_key ) && is_wp_error( $wpmu_activate_signup ) ) {
					if ( 'already_active' === $wpmu_activate_signup->get_error_code() || 'blog_taken' === $wpmu_activate_signup->get_error_code() ) {
						wp_safe_redirect( $this->new_login_url() . $query_string );
						exit;
					}
				}
			}

			$this->wp_template_loader();
		} elseif ( 'wp-login.php' === $pagenow ) {
			$redirect_to           = admin_url();
			$requested_redirect_to = '';

			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$requested_redirect_to = $_REQUEST['redirect_to'];
			}

			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();

				if ( ! isset( $_REQUEST['action'] ) ) {
					wp_safe_redirect( $redirect_to );
					exit;
				}
			}

			@require_once ABSPATH . 'wp-login.php';
			exit;
		}

	}

	/**
	 * Load WordPress template loader.
	 *
	 * @return void
	 */
	public function wp_template_loader() {
		global $pagenow;

		$pagenow = 'index.php';

		if ( ! defined( 'WP_USE_THEMES' ) ) {
			define( 'WP_USE_THEMES', true );
		}

		wp();

		require_once ABSPATH . WPINC . '/template-loader.php';

		exit;

	}

}
