<?php
/**
 * Login url module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\LoginRedirect;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup login url module.
 */
class Login_Redirect_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/login-redirect';

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
	 * Setup login url module.
	 */
	public function setup() {

		/**
		 * These 4 actions will be removed on multisite if current site is not a blueprint.
		 */
		add_action( 'admin_menu', array( self::get_instance(), 'submenu_page' ) );
		add_action( 'admin_init', array( self::get_instance(), 'add_settings' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );

		// The module output.
		require_once __DIR__ . '/class-login-redirect-output.php';
		Login_Redirect_Output::init();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {
		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Login Redirect', 'ultimate-dashboard' ), __( 'Login Redirect', 'ultimate-dashboard' ), apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_login_redirect', array( $this, 'submenu_page_content' ) );
	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/login-redirect-template.php';
		$template();

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		$enqueue = require __DIR__ . '/inc/css-enqueue.php';
		$enqueue( $this );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		$enqueue = require __DIR__ . '/inc/js-enqueue.php';
		$enqueue( $this );

	}

	/**
	 * Add settings.
	 */
	public function add_settings() {

		// Register setting.
		register_setting( 'udb-login-redirect-group', 'udb_login_redirect', array( 'sanitize_callback' => array( $this, 'sanitize_login_redirect_settings' ) ) );

		$login_redirect_title = '<span class="udb-login-redirect--title-text">' . __( 'Redirect After Login', 'ultimate-dashboard' ) . '</span>';
		$login_redirect_title = apply_filters( 'udb_login_redirect_title', $login_redirect_title );

		// Login url section.
		add_settings_section( 'udb-login-url-section', __( 'Change Login URL', 'ultimate-dashboard' ), '', 'udb-login-url-settings' );
		add_settings_section( 'udb-login-redirect-section', $login_redirect_title, '', 'udb-login-redirect-settings' );

		// Login url fields.
		add_settings_field( 'new-login-url', __( 'New Login URL', 'ultimate-dashboard' ), array( $this, 'new_login_url_field' ), 'udb-login-url-settings', 'udb-login-url-section' );
		add_settings_field( 'wp-admin-redirect-url', __( 'Redirect Admin Area', 'ultimate-dashboard' ), array( $this, 'wp_admin_redirect_url_field' ), 'udb-login-url-settings', 'udb-login-url-section' );

		// Login redirect fields.
		add_settings_field( 'login-redirect-url', __( 'Select Role(s)', 'ultimate-dashboard' ), array( $this, 'login_redirect_url_field' ), 'udb-login-redirect-settings', 'udb-login-redirect-section' );

	}

	/**
	 * Sanitize login redirect settings.
	 *
	 * @param mixed $input The input data to sanitize.
	 * @return array The sanitized settings array.
	 */
	public function sanitize_login_redirect_settings( $input ) {

		if ( ! is_array( $input ) ) {
			return array();
		}

		// This is the ideal, but will break compatibility with the pro version.
		// $sanitized = array();

		/**
		 * The sanitized data.
		 *
		 * ! This is needed for backwards compatibility with the pro version,
		 * because the pro version doesn't have the filter before version 3.10.5
		 *
		 * @var array
		 */
		$sanitized = $input;

		if ( isset( $input['login_url_slug'] ) ) {
			$sanitized['login_url_slug'] = sanitize_text_field( $input['login_url_slug'] );
		}

		if ( isset( $input['wp_admin_redirect_slug'] ) ) {
			$sanitized['wp_admin_redirect_slug'] = sanitize_text_field( $input['wp_admin_redirect_slug'] );
		}

		if ( isset( $input['login_redirect_slugs'] ) && is_array( $input['login_redirect_slugs'] ) ) {
			$sanitized['login_redirect_slugs'] = array();

			foreach ( $input['login_redirect_slugs'] as $role_key => $redirect_slug ) {
				$sanitized_role_key = sanitize_key( $role_key );
				$sanitized['login_redirect_slugs'][ $sanitized_role_key ] = sanitize_text_field( $redirect_slug );
			}
		}

		// Allow PRO version or other extensions to add their own sanitization.
		$sanitized = apply_filters( 'udb_login_redirect_sanitize_settings', $sanitized, $input );

		return $sanitized;

	}

	/**
	 * New login url field.
	 */
	public function new_login_url_field() {

		$field = require __DIR__ . '/templates/fields/new-login-url.php';
		$field();

	}

	/**
	 * Redirect old login url field.
	 */
	public function wp_admin_redirect_url_field() {

		$field = require __DIR__ . '/templates/fields/wp-admin-redirect-url.php';
		$field();

	}

	/**
	 * Login redirect url field.
	 */
	public function login_redirect_url_field() {

		$field = require __DIR__ . '/templates/fields/login-redirect-url.php';
		$field();

	}

}
