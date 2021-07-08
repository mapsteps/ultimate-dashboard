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

		add_action( 'udb_after_general_metabox', array( $this, 'add_settings' ) );

		// The module output.
		require_once __DIR__ . '/class-login-url-output.php';
		Login_Url_Output::init();

	}

	/**
	 * Add settings.
	 */
	public function add_settings() {

		// Login url section.
		add_settings_section( 'udb-login-url-section', __( 'Change Login URL', 'ultimate-dashboard' ), '', 'udb-login-url-settings' );

		// Login url fields.
		add_settings_field( 'new-login-url', __( 'New Login URL', 'ultimate-dashboard' ), array( $this, 'new_login_url_field' ), 'udb-login-url-settings', 'udb-login-url-section' );
		add_settings_field( 'wp-admin-redirect-url', __( 'Redirect Admin Area', 'ultimate-dashboard' ), array( $this, 'wp_admin_redirect_url_field' ), 'udb-login-url-settings', 'udb-login-url-section' );

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

}
