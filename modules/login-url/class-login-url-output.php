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

}
