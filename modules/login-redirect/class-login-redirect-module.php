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
	 * Setup login url module.
	 */
	public function setup() {

		add_action( 'init', array( $this, 'setup_hooks' ) );

		// The module output.
		require_once __DIR__ . '/class-login-redirect-output.php';
		Login_Redirect_Output::init();

	}

	/**
	 * Setup functions hooking on init.
	 * In order to get the filter works, we need to add the filter on init hook.
	 */
	public function setup_hooks() {

		$multisite_supported = apply_filters( 'udb_ms_supported', false );
		$is_blueprint        = apply_filters( 'udb_ms_is_blueprint', false );

		// Don't add these actions when we're on a multisite and the current site is not a blueprint.
		if ( $multisite_supported && ! $is_blueprint ) {
			return;
		}

		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
		add_action( 'admin_init', array( $this, 'add_settings' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

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
		register_setting( 'udb-login-redirect-group', 'udb_login_redirect' );

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
