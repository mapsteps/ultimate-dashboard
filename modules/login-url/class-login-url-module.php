<?php
/**
 * Login url module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Login_Url;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup login url module.
 */
class Login_Url_Module extends Base_Module {
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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/login-url';

	}

	/**
	 * Setup login url module.
	 */
	public function setup() {

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'udb_after_general_metabox', array( $this, 'add_settings' ) );

		// The module output.
		require_once __DIR__ . '/class-login-url-output.php';
		Login_Url_Output::init();

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
