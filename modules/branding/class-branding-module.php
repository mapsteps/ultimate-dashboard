<?php
/**
 * Branding module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Branding;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Color_Helper;
use Udb\Base\Base_Module;

/**
 * Class to setup branding module.
 */
class Branding_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/branding';

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
	 * Setup branding module.
	 */
	public function setup() {

		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'admin_head', array( self::get_instance(), 'instant_preview' ), 130 );

		add_action( 'admin_init', array( $this, 'add_settings' ) );

		// The module output.
		require_once __DIR__ . '/class-branding-output.php';
		$output = new Branding_Output();
		$output->setup();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'White Label', 'ultimate-dashboard' ), __( 'White Label', 'ultimate-dashboard' ), apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_branding', array( $this, 'submenu_page_content' ) );

	}

	/**
	 * Submenu page content.
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
	 * Instant preview style tags.
	 */
	public function instant_preview() {

		if ( ! $this->screen()->is_branding() ) {
			return;
		}

		require __DIR__ . '/templates/instant-preview.php';

	}

	/**
	 * Print color in rgba format from hex color.
	 *
	 * @param string     $hex_color Color in hex format.
	 * @param int|string $opacity The alpha opacity part of an rgba color.
	 */
	public function print_rgba_from_hex( $hex_color, $opacity ) {

		$color_helper = new Color_Helper();

		$rgb = $color_helper->hex_to_rgb( $hex_color );

		$rgba_string = 'rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', ' . $opacity . ')';

		echo esc_attr( $rgba_string );

	}

	/**
	 * Add settings.
	 */
	public function add_settings() {

		// Register setting.
		register_setting( 'udb-branding-group', 'udb_branding', array( 'sanitize_callback' => array( $this, 'sanitize_branding_settings' ) ) );

		// Sections.
		add_settings_section( 'udb-branding-section', __( 'WordPress Admin Branding', 'ultimate-dashboard' ), '', 'udb-branding-settings' );
		add_settings_section( 'udb-darkmode-section', __( 'Dark Mode (Experimental)', 'ultimate-dashboard' ), '', 'udb-darkmode-settings' );
		add_settings_section( 'udb-admin-colors-section', __( 'WordPress Admin Colors', 'ultimate-dashboard' ), '', 'udb-admin-colors-settings' );
		add_settings_section( 'udb-admin-logo-section', __( 'WordPress Admin Logo', 'ultimate-dashboard' ), '', 'udb-admin-logo-settings' );
		add_settings_section( 'udb-branding-misc-section', __( 'Misc', 'ultimate-dashboard' ), '', 'udb-branding-misc-settings' );

		// Branding fields.
		add_settings_field( 'udb-branding-enable-field', __( 'Enable', 'ultimate-dashboard' ), array( $this, 'enable_field' ), 'udb-branding-settings', 'udb-branding-section' );
		add_settings_field( 'udb-branding-layout-field', __( 'Layout', 'ultimate-dashboard' ), array( $this, 'choose_layout_field' ), 'udb-branding-settings', 'udb-branding-section' );

		// Darkmode fields.
		add_settings_field( 'wp-admin-darkmode', __( 'WP Admin', 'ultimate-dashboard' ), array( $this, 'wp_admin_darkmode_field' ), 'udb-darkmode-settings', 'udb-darkmode-section' );
		add_settings_field( 'block-editor-darkmode', __( 'Block Editor', 'ultimate-dashboard' ), array( $this, 'block_editor_darkmode_field' ), 'udb-darkmode-settings', 'udb-darkmode-section' );

		// Admin colors fields.
		add_settings_field( 'udb-accent-color-field', __( 'Accent Color', 'ultimate-dashboard' ), array( $this, 'accent_color_field' ), 'udb-admin-colors-settings', 'udb-admin-colors-section' );
		add_settings_field( 'udb-menu-item-color-field', __( 'Menu Item Color', 'ultimate-dashboard' ), array( $this, 'menu_item_color_field' ), 'udb-admin-colors-settings', 'udb-admin-colors-section' );
		add_settings_field( 'udb-admin-bar-bg-color-field', __( 'Admin Bar Bg Color', 'ultimate-dashboard' ), array( $this, 'admin_bar_color_field' ), 'udb-admin-colors-settings', 'udb-admin-colors-section' );
		add_settings_field( 'udb-admin-menu-bg-color-field', __( 'Admin Menu Bg Color', 'ultimate-dashboard' ), array( $this, 'admin_menu_bg_color_field' ), 'udb-admin-colors-settings', 'udb-admin-colors-section' );
		add_settings_field( 'udb-admin-submenu-bg-color-field', __( 'Admin Submenu Bg Color', 'ultimate-dashboard' ), array( $this, 'admin_submenu_bg_color_field' ), 'udb-admin-colors-settings', 'udb-admin-colors-section' );

		add_settings_field( 'udb-branding-admin-bar-logo-image-field', __( 'Admin Bar Logo', 'ultimate-dashboard' ), array( $this, 'admin_bar_logo_field' ), 'udb-admin-logo-settings', 'udb-admin-logo-section' );
		add_settings_field( 'udb-branding-admin-bar-logo-url-field', __( 'Admin Bar Logo URL', 'ultimate-dashboard' ), array( $this, 'admin_bar_logo_url_field' ), 'udb-admin-logo-settings', 'udb-admin-logo-section' );
		add_settings_field( 'udb-branding-block-editor-logo-image-field', __( 'Block-Editor Logo', 'ultimate-dashboard' ), array( $this, 'block_editor_logo_field' ), 'udb-admin-logo-settings', 'udb-admin-logo-section' );

		// Misc fields.
		add_settings_field( 'udb-branding-footer-text-field', __( 'Footer Text', 'ultimate-dashboard' ), array( $this, 'footer_text_field' ), 'udb-branding-misc-settings', 'udb-branding-misc-section' );
		add_settings_field( 'udb-branding-version-text-field', __( 'Version Text', 'ultimate-dashboard' ), array( $this, 'version_text_field' ), 'udb-branding-misc-settings', 'udb-branding-misc-section' );

		do_action( 'udb_branding_setting_fields' );

	}

	/**
	 * Sanitize branding settings.
	 *
	 * @param mixed $input The input data to sanitize.
	 * @return array The sanitized settings array.
	 */
	public function sanitize_branding_settings( $input ) {

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

		if ( isset( $input['footer_text'] ) ) {
			$sanitized['footer_text'] = wp_kses_post( $input['footer_text'] );
		}

		if ( isset( $input['version_text'] ) ) {
			$sanitized['version_text'] = wp_kses_post( $input['version_text'] );
		}

		// Allow PRO version or other extensions to add their own sanitization.
		$sanitized = apply_filters( 'udb_branding_sanitize_settings', $sanitized, $input );

		return $sanitized;

	}

	/**
	 * Enable branding field.
	 */
	public function enable_field() {

		$template = __DIR__ . '/templates/fields/enable.php';
		$template = apply_filters( 'udb_branding_enable_feature_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Choose layout field.
	 */
	public function choose_layout_field() {

		$template = __DIR__ . '/templates/fields/choose-layout.php';
		$template = apply_filters( 'udb_branding_choose_layout_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * WP Admin darkmode field.
	 */
	public function wp_admin_darkmode_field() {

		$template = __DIR__ . '/templates/fields/wp-admin-darkmode.php';
		$template = apply_filters( 'udb_branding_wp_admin_darkmode_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Block editor (Gutenberg) darkmode field.
	 */
	public function block_editor_darkmode_field() {

		$template = __DIR__ . '/templates/fields/block-editor-darkmode.php';
		$template = apply_filters( 'udb_branding_block_editor_darkmode_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Accent color field.
	 */
	public function accent_color_field() {

		$template = __DIR__ . '/templates/fields/accent-color.php';
		$template = apply_filters( 'udb_branding_accent_color_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Admin bar bg color field.
	 */
	public function admin_bar_color_field() {

		$template = __DIR__ . '/templates/fields/admin-bar-bg-color.php';
		$template = apply_filters( 'udb_branding_admin_bar_bg_color_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Admin menu bg color field.
	 */
	public function admin_menu_bg_color_field() {

		$template = __DIR__ . '/templates/fields/admin-menu-bg-color.php';
		$template = apply_filters( 'udb_branding_admin_menu_bg_color_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Admin submenu bg color field.
	 */
	public function admin_submenu_bg_color_field() {

		$template = __DIR__ . '/templates/fields/admin-submenu-bg-color.php';
		$template = apply_filters( 'udb_branding_admin_submenu_bg_color_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Admin menu item color field.
	 */
	public function menu_item_color_field() {

		$template = __DIR__ . '/templates/fields/menu-item-color.php';
		$template = apply_filters( 'udb_branding_menu_item_color_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Admin menu item active color field.
	 */
	public function menu_item_active_color_field() {

		$template = __DIR__ . '/templates/fields/menu-item-active-color.php';
		$template = apply_filters( 'udb_branding_menu_item_active_color_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Admin bar logo field.
	 */
	public function admin_bar_logo_field() {

		$template = __DIR__ . '/templates/fields/admin-bar-logo.php';
		$template = apply_filters( 'udb_branding_admin_bar_logo_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Admin bar logo url field.
	 */
	public function admin_bar_logo_url_field() {

		$template = __DIR__ . '/templates/fields/admin-bar-logo-url.php';
		$template = apply_filters( 'udb_branding_admin_bar_logo_url_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Gutenberg block editor logo field.
	 */
	public function block_editor_logo_field() {

		$template = __DIR__ . '/templates/fields/block-editor-logo.php';
		$template = apply_filters( 'udb_branding_block_editor_logo_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Footer text field.
	 */
	public function footer_text_field() {

		$template = __DIR__ . '/templates/fields/footer-text.php';
		$field    = require $template;

		$field();

	}

	/**
	 * Version text field.
	 */
	public function version_text_field() {

		$template = __DIR__ . '/templates/fields/version-text.php';
		$field    = require $template;

		$field();

	}

}
