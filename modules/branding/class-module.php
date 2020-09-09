<?php
/**
 * Branding module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Branding;

use Udb\Base\Module as Base_Module;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup branding module.
 */
class Module extends Base_Module {
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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/branding';

	}

	/**
	 * Setup branding module.
	 */
	public function setup() {

		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'admin_init', array( $this, 'add_settings' ) );

		// The module output.
		require_once __DIR__ . '/class-output.php';
		$output = new Output();
		$output->setup();

	}

	/**
	 * Branding page.
	 */
	public function submenu_page() {
		add_submenu_page( 'edit.php?post_type=udb_widgets', 'White Label', 'White Label', apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_branding', array( $this, 'submenu_page_content' ) );
	}

	/**
	 * Settings page callback.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/branding-template.php';
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

		// Settings group.
		register_setting( 'udb-branding-group', 'udb_branding' );

		// Settings sections (detailed, general).
		add_settings_section( 'udb-branding-detailed-section', __( 'WordPress Admin Branding', 'ultimate-dashboard' ), '', 'udb-detailed-branding' );
		add_settings_section( 'udb-branding-general-section', __( 'Misc', 'ultimate-dashboard' ), '', 'udb-general-branding' );

		// Detailed section fields.
		add_settings_field( 'udb-branding-enable-field', __( 'Enable', 'ultimate-dashboard' ), array( $this, 'enable_field' ), 'udb-detailed-branding', 'udb-branding-detailed-section' );
		add_settings_field( 'udb-branding-layout-field', __( 'Layout', 'ultimate-dashboard' ), array( $this, 'choose_layout_field' ), 'udb-detailed-branding', 'udb-branding-detailed-section' );
		add_settings_field( 'udb-branding-accent-color-field', __( 'Accent Color', 'ultimate-dashboard' ), array( $this, 'accent_color_field' ), 'udb-detailed-branding', 'udb-branding-detailed-section' );
		add_settings_field( 'udb-branding-admin-bar-logo-image-field', __( 'Admin Bar Logo', 'ultimate-dashboard' ), array( $this, 'admin_bar_logo_field' ), 'udb-detailed-branding', 'udb-branding-detailed-section' );
		add_settings_field( 'udb-branding-admin-bar-logo-url-field', __( 'Admin Bar Logo URL', 'ultimate-dashboard' ), array( $this, 'admin_bar_logo_url_field' ), 'udb-detailed-branding', 'udb-branding-detailed-section' );

		// General section fields.
		add_settings_field( 'udb-branding-footer-text-field', __( 'Footer Text', 'ultimate-dashboard' ), array( $this, 'footer_text_field' ), 'udb-general-branding', 'udb-branding-general-section' );
		add_settings_field( 'udb-branding-version-text-field', __( 'Version Text', 'ultimate-dashboard' ), array( $this, 'version_text_field' ), 'udb-general-branding', 'udb-branding-general-section' );

	}

	/**
	 * Enable branding field.
	 */
	public function enable_field() {

		$field = require __DIR__ . '/templates/fields/enable.php';
		$field();

	}

	/**
	 * Choose layout field.
	 */
	public function choose_layout_field() {

		$field = require __DIR__ . '/templates/fields/choose-layout.php';
		$field();

	}

	/**
	 * Accent color field.
	 */
	public function accent_color_field() {

		$field = require __DIR__ . '/templates/fields/accent-color.php';
		$field();

	}

	/**
	 * Admin bar logo field.
	 */
	public function admin_bar_logo_field() {

		$field = require __DIR__ . '/templates/fields/admin-bar-logo.php';
		$field();

	}

	/**
	 * Admin bar logo url field.
	 */
	public function admin_bar_logo_url_field() {

		$field = require __DIR__ . '/templates/fields/admin-bar-logo-url.php';
		$field();

	}

	/**
	 * Footer text field.
	 */
	public function footer_text_field() {

		$field = require __DIR__ . '/templates/fields/footer-text.php';
		$field();

	}

	/**
	 * Version text field.
	 */
	public function version_text_field() {

		$field = require __DIR__ . '/templates/fields/version-text.php';
		$field();

	}

}
