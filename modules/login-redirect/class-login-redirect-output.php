<?php
/**
 * Login url output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\LoginRedirect;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use WP_User;
use WP_Error;

/**
 * Class to set up login url output.
 */
class Login_Redirect_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * Whether current page is the old login page or not.
	 *
	 * @var bool
	 */
	public $is_old_login_page = false;

	/**
	 * The new login slug.
	 *
	 * @var string
	 */
	public $new_login_slug = '';

	/**
	 * The wp-admin redirect url slug.
	 *
	 * @var string
	 */
	public $wp_admin_redirect_slug = '';

	/**
	 * The saved permalink structure.
	 *
	 * @var string
	 */
	public $permalink_structure = '';

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/login-redirect';

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		self::get_instance()->setup();

	}

	/**
	 * Setup widgets output.
	 */
	public function setup() {

		// Hooked into `setup_theme` because this module is already loaded inside `plugins_loaded`.
		add_action( 'setup_theme', array( $this, 'setup_hooks' ) );

		// The "peters-login-redirect" plugin uses 999999999, so let's set it higher :).
		add_filter( 'login_redirect', array( $this, 'custom_login_redirect' ), 1000000000, 3 );

	}

	/**
	 * Setup action & filter hooks.
	 *
	 * Scripts below are not placed directly inside `setup` method above
	 * so that we can apply filters from multisite module.
	 */
	public function setup_hooks() {

		$settings = get_option( 'udb_login_redirect', array() );

		$this->new_login_slug = isset( $settings['login_url_slug'] ) ? $settings['login_url_slug'] : '';
		$this->new_login_slug = apply_filters( 'udb_login_slug', $this->new_login_slug );

		$this->wp_admin_redirect_slug = isset( $settings['wp_admin_redirect_slug'] ) ? $settings['wp_admin_redirect_slug'] : '';
		$this->wp_admin_redirect_slug = apply_filters( 'udb_wp_admin_redirect_slug', $this->wp_admin_redirect_slug );

		$this->permalink_structure = get_option( 'permalink_structure' );

		// Protect wp-admin if the slug is set in the setting.
		if ( $this->wp_admin_redirect_slug ) {
			add_action( 'wp_loaded', array( $this, 'protect_wp_admin' ) );
		}

		// Stop if custom login slug is not set.
		if ( ! $this->new_login_slug ) {
			return;
		}

		// Hooked into `init` because we're already inside `setup_theme` hook.
		add_action( 'init', array( $this, 'change_url' ), 9999 );
		add_action( 'wp_loaded', array( $this, 'protect_wp_login' ) );

		add_filter( 'site_url', array( $this, 'site_url' ), 10, 4 );
		add_filter( 'network_site_url', array( $this, 'network_site_url' ), 10, 3 );
		add_filter( 'wp_redirect', array( $this, 'wp_redirect' ), 10, 2 );
		add_filter( 'login_url', array( $this, 'login_url' ), 10, 3 );
		add_filter( 'site_option_welcome_email', array( $this, 'welcome_email' ) );

		// @see https://developer.wordpress.org/reference/functions/wp_redirect_admin_locations/
		remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );

		add_action( 'template_redirect', array( $this, 'redirect_export_data' ) );
		add_filter( 'user_request_action_email_content', array( $this, 'user_request_action_email_content' ), 10, 2 );
		add_filter( 'site_status_tests', array( $this, 'site_status_tests' ) );

	}

	/**
	 * Change url.
	 */
	public function change_url() {

		if ( ! $this->new_login_slug ) {
			return;
		}

		$uri = isset( $_SERVER['REQUEST_URI'] ) ? rawurldecode( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : '';

		$has_signup_slug   = false !== stripos( $uri, 'wp-signup' );
		$has_activate_slug = false !== stripos( $uri, 'wp-activate' );

		if ( ! is_multisite() && ( $has_signup_slug || $has_activate_slug ) ) {
			return;
		}

		$request      = wp_parse_url( $uri );
		$request_path = isset( $request['path'] ) ? untrailingslashit( $request['path'] ) : '';

		$using_permalink = (bool) $this->permalink_structure;
		$has_new_slug    = ! empty( $_GET[ $this->new_login_slug ] );
		$has_old_slug    = false !== stripos( $uri, 'wp-login.php' );

		$has_register_slug = false !== stripos( $uri, 'wp-register.php' );

		global $pagenow;

		if ( ! is_admin() && ( $has_old_slug || site_url( 'wp-login', 'relative' ) === $request_path ) ) {
			// If current page is old login page, set $pagenow to be index.php, so it's not login page anymore.
			$pagenow = 'index.php';

			$this->is_old_login_page = true;
			$_SERVER['REQUEST_URI']  = $this->maybe_trailingslashit( '/' . str_repeat( '-/', 10 ) );
		} elseif ( site_url( $this->new_login_slug, 'relative' ) === $request_path || ( ! $using_permalink && $has_new_slug ) ) {
			// If current page is new login page, let WordPress know if this is a login page.
			$pagenow = 'wp-login.php';
		} elseif ( ! is_admin() && ( $has_register_slug || site_url( 'wp-register', 'relative' ) === $request_path ) ) {
			$pagenow = 'index.php';

			$this->is_old_login_page = true;
			$_SERVER['REQUEST_URI']  = $this->maybe_trailingslashit( '/' . str_repeat( '-/', 10 ) );
		}

	}

	/**
	 * Get new login url.
	 *
	 * @param string|null $scheme Scheme to give the site URL context. Accepts 'http', 'https', 'login', 'login_post', 'admin', 'relative' or null.
	 *
	 * @return string
	 */
	public function new_login_url( $scheme = null ) {

		$login_url = site_url( $this->new_login_slug, $scheme );

		if ( is_multisite() ) {
			$uri = isset( $_SERVER['REQUEST_URI'] ) ? rawurldecode( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : '';

			$has_install_slug = false !== stripos( $uri, '/wp-admin/install.php' );

			$login_url = $has_install_slug ? admin_url() : $login_url;
		}

		if ( $this->permalink_structure ) {
			return $this->maybe_trailingslashit( $login_url );
		}

		return $login_url;

	}

	/**
	 * Get wp-admin redirect url.
	 *
	 * @return string
	 */
	public function wp_admin_redirect_url() {

		$redirect_url = site_url( $this->wp_admin_redirect_slug );

		if ( $this->permalink_structure ) {
			return $this->maybe_trailingslashit( $redirect_url );
		}

		return $redirect_url;

	}

	/**
	 * Return a string with or without trailing slash based on permalink structure.
	 *
	 * @param string $str The string to return.
	 *
	 * @return string
	 */
	public function maybe_trailingslashit( $str ) {

		$use_trailingslash = '/' === substr( $this->permalink_structure, - 1, 1 );

		return ( $use_trailingslash ? trailingslashit( $str ) : untrailingslashit( $str ) );

	}

	/**
	 * Protect wp-admin based on the setting.
	 */
	public function protect_wp_admin() {

		if ( isset( $_GET['action'] ) && 'postpass' === $_GET['action'] && isset( $_POST['post_password'] ) ) {
			return;
		}

		global $pagenow;

		$uri          = isset( $_SERVER['REQUEST_URI'] ) ? rawurldecode( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : '';
		$request      = wp_parse_url( rawurldecode( $uri ) );
		$request_path = isset( $request['path'] ) ? $request['path'] : '';

		if ( is_admin() && ! is_user_logged_in() && ! wp_doing_ajax() && 'admin-post.php' !== $pagenow && '/wp-admin/options.php' !== $request_path ) {
			wp_safe_redirect( $this->wp_admin_redirect_url() );
			exit;
		}

	}

	/**
	 * Protect wp-login.php based on the setting.
	 */
	public function protect_wp_login() {

		if ( isset( $_GET['action'] ) && 'postpass' === $_GET['action'] && isset( $_POST['post_password'] ) ) {
			return;
		}

		global $pagenow;

		$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? rawurldecode( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : '';
		$request      = wp_parse_url( rawurldecode( $request_uri ) );
		$request_path = $request['path'];

		$query_string     = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';
		$add_query_string = $query_string ? '?' . $query_string : '';

		if ( 'wp-login.php' === $pagenow && $request_path !== $this->maybe_trailingslashit( $request_path ) && $this->permalink_structure ) {
			wp_safe_redirect(
				$this->maybe_trailingslashit( $this->new_login_url() ) . $add_query_string
			);
			exit;
		} elseif ( $this->is_old_login_page ) {
			$referer  = wp_get_referer();
			$referers = wp_parse_url( $referer );

			$referer_contains_activate_url = false !== stripos( $referer, 'wp-activate.php' );

			if ( $referer_contains_activate_url && ! empty( $referers['query'] ) ) {
				parse_str( $referers['query'], $referer_queries );

				$signup_key           = $referer_queries['key'];
				$wpmu_activate_signup = wpmu_activate_signup( $signup_key );

				if ( file_exists( WPINC . '/ms-functions.php' ) ) {
					@require_once WPINC . '/ms-functions.php';
				}

				if ( ! empty( $signup_key ) && is_wp_error( $wpmu_activate_signup ) ) {
					if ( 'already_active' === $wpmu_activate_signup->get_error_code() || 'blog_taken' === $wpmu_activate_signup->get_error_code() ) {
						wp_safe_redirect( $this->new_login_url() . $add_query_string );
						exit;
					}
				}
			}

			$this->wp_template_loader();
		} elseif ( 'wp-login.php' === $pagenow ) {
			$redirect_to           = admin_url();
			$requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? sanitize_url( wp_unslash( $_REQUEST['redirect_to'] ) ) : '';

			if ( is_user_logged_in() ) {
				$user = wp_get_current_user();

				if ( ! isset( $_REQUEST['action'] ) ) {
					wp_safe_redirect( $redirect_to );
					exit;
				}
			}

			// Prevent warnings in wp-login.php file by providing these globals.
			global $user_login, $error, $iterim_login, $action;

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

	/**
	 * Filter old login page.
	 *
	 * @param string $url The url to filter.
	 * @param string $scheme Scheme to give the site URL context. Accepts 'http', 'https', 'login', 'login_post', 'admin', 'relative' or null.
	 *
	 * @return string
	 */
	public function filter_old_login_page( $url, $scheme = null ) {

		// Skip wp-login.php?action=postpass from the filtering.
		if ( false !== stripos( $url, 'wp-login.php?action=postpass' ) ) {
			return $url;
		}

		/**
		 * We can't use `wp_get_referer` here because
		 * it will call `wp_validate_referer` and it was causing
		 * fatal error (infinite loop) with Google Site Kit installed.
		 */
		$referer = esc_url( wp_get_raw_referer() );

		$url_contains_old_login_url     = false !== stripos( $url, 'wp-login.php' );
		$referer_contains_old_login_url = false !== stripos( $referer, 'wp-login.php' );

		if ( $url_contains_old_login_url && ! $referer_contains_old_login_url ) {
			if ( is_ssl() ) {
				$scheme = 'https';
			}

			$url_parts = explode( '?', $url );

			if ( isset( $url_parts[1] ) ) {
				parse_str( $url_parts[1], $args );

				if ( isset( $args['login'] ) ) {
					$args['login'] = rawurlencode( $args['login'] );
				}

				$url = add_query_arg( $args, $this->new_login_url( $scheme ) );
			} else {
				$url = $this->new_login_url( $scheme );
			}
		}

		return $url;

	}

	/**
	 * Filter site_url.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/site_url/
	 *
	 * @param string      $url The complete site URL including scheme and path.
	 * @param string      $path Path relative to the site URL. Blank string if no path is specified.
	 * @param string|null $scheme Scheme to give the site URL context. Accepts 'http', 'https', 'login', 'login_post', 'admin', 'relative' or null.
	 * @param int|null    $blog_id Site ID, or null for the current site.
	 *
	 * @return string
	 */
	public function site_url( $url, $path, $scheme, $blog_id ) {

		return $this->filter_old_login_page( $url, $scheme );

	}

	/**
	 * Filter network_site_url.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/network_site_url/
	 *
	 * @param string      $url The complete site URL including scheme and path.
	 * @param string      $path Path relative to the site URL. Blank string if no path is specified.
	 * @param string|null $scheme Scheme to give the site URL context. Accepts 'http', 'https', 'login', 'login_post', 'admin', 'relative' or null.
	 *
	 * @return string
	 */
	public function network_site_url( $url, $path, $scheme ) {

		return $this->filter_old_login_page( $url, $scheme );

	}

	/**
	 * Filter login_url.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/login_url/
	 *
	 * @param string $login_url The login URL. Not HTML-encoded.
	 * @param string $redirect The path to redirect to on login, if supplied.
	 * @param bool   $force_reauth Whether to force reauthorization, even if a cookie is present.
	 *
	 * @return string
	 */
	public function login_url( $login_url, $redirect, $force_reauth ) {

		if ( is_404() ) {
			return '#';
		}

		if ( empty( $redirect ) || ! $force_reauth ) {
			return $login_url;
		}

		$url_parts = explode( '?', $redirect );

		if ( admin_url( 'options.php' ) === $url_parts[0] ) {
			$login_url = admin_url();
		}

		return $login_url;

	}

	/**
	 * Filter wp_redirect.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/wp_redirect/
	 *
	 * @param string $location The path or URL to redirect to.
	 * @param int    $status The HTTP response status code to use.
	 *
	 * @return string
	 */
	public function wp_redirect( $location, $status ) {

		// Exclude wordpress.com login page.
		if ( false !== stripos( $location, 'https://wordpress.com/wp-login.php' ) ) {
			return $location;
		}

		return $this->filter_old_login_page( $location );

	}

	/**
	 * Filter welcome email.
	 * Replace "wp-login.php" strings with new login slug.
	 *
	 * @param string $value The option value.
	 *
	 * @return string
	 */
	public function welcome_email( $value ) {

		return str_ireplace( 'wp-login.php', trailingslashit( $this->new_login_slug ), $value );

	}

	/**
	 * Redirect export data.
	 */
	public function redirect_export_data() {

		if ( ! isset( $_GET ) || ! isset( $_GET['action'] ) || 'confirmaction' !== $_GET['action'] || ! isset( $_GET['request_id'] ) || ! isset( $_GET['confirm_key'] ) ) {
			return;
		}

		$request_id = absint( $_GET['request_id'] );
		$key        = sanitize_text_field( wp_unslash( $_GET['confirm_key'] ) );
		$validation = wp_validate_user_request_key( $request_id, $key );

		if ( ! is_wp_error( $validation ) ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'action'      => 'confirmaction',
						'request_id'  => isset( $_GET['request_id'] ) ? sanitize_text_field( wp_unslash( $_GET['request_id'] ) ) : '',
						'confirm_key' => isset( $_GET['confirm_key'] ) ? sanitize_text_field( wp_unslash( $_GET['confirm_key'] ) ) : '',
					),
					$this->new_login_url()
				)
			);
			exit;
		}

	}

	/**
	 * Filters the text of the email sent when an account action is attempted.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/user_request_action_email_content/
	 *
	 * @param string $email_text Text in the email.
	 * @param array  $email_data Data relating to the account action email.
	 *
	 * @return string
	 */
	public function user_request_action_email_content( $email_text, $email_data ) {

		$confirm_url = str_ireplace( $this->new_login_slug . '/', 'wp-login.php', $email_data['confirm_url'] );
		$confirm_url = esc_url_raw( $confirm_url );

		return str_ireplace( '###CONFIRM_URL###', $confirm_url, $email_text );

	}

	/**
	 * Filters the text of the email sent when an account action is attempted.
	 *
	 * @see https://developer.wordpress.org/reference/hooks/site_status_tests/
	 *
	 * @param array $test_types An associative array, where the $test_type is either direct or async, to declare if the test should run via Ajax calls after page load.
	 *
	 * @return array
	 */
	public function site_status_tests( $test_types ) {

		unset( $test_types['async']['loopback_requests'] );

		return $test_types;

	}

	/**
	 * Filters the login redirect URL.
	 *
	 * @param string           $redirect_to The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user WP_User object if login was successful, WP_Error object otherwise.
	 *
	 * @return string
	 */
	public function custom_login_redirect( $redirect_to, $requested_redirect_to, $user ) {

		$settings = get_option( 'udb_login_redirect', array() );
		$slugs    = isset( $settings['login_redirect_slugs'] ) ? $settings['login_redirect_slugs'] : array();
		$roles    = property_exists( $user, 'roles' ) ? $user->roles : array();

		if ( empty( $roles ) ) {
			return $redirect_to;
		}

		foreach ( $roles as $role ) {
			if ( ! empty( $slugs[ $role ] ) ) {
				return site_url( $slugs[ $role ] );
			}
		}

		return $redirect_to;

	}

}
