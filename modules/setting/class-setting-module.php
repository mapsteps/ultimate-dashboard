<?php
/**
 * Setting module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Setting;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup setting module.
 */
class Setting_Module extends Base_Module {
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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/setting';

	}

	/**
	 * Setup setting module.
	 */
	public function setup() {

		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'admin_init', array( $this, 'add_settings' ) );

		// The module output.
		require_once __DIR__ . '/class-setting-output.php';
		Setting_Output::init();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {
		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Settings', 'ultimate-dashboard' ), __( 'Settings', 'ultimate-dashboard' ), apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_settings', array( $this, 'submenu_page_content' ) );
	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/settings-template.php';
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
		register_setting( 'udb-settings-group', 'udb_settings' );

		// Widget sections.
		add_settings_section( 'udb-widgets-section', __( 'WordPress Dashboard Widgets', 'ultimate-dashboard' ), '', 'udb-widget-settings' );
		add_settings_section( 'udb-3rd-party-widgets-section', __( '3rd Party Widgets', 'ultimate-dashboard' ), '', 'udb-widget-settings' );

		// We use this hook to place the Page Builder Dashboard feature here.
		do_action( 'udb_after_widget_metabox' );

		// Widget styling section.
		add_settings_section( 'udb-styling-section', __( 'Widget Styling', 'ultimate-dashboard' ), '', 'udb-widget-styling-settings' );

		// General section.
		add_settings_section( 'udb-general-section', __( 'General', 'ultimate-dashboard' ), '', 'udb-general-settings' );

		// Advanced section.
		add_settings_section( 'udb-advanced-section', __( 'Advanced', 'ultimate-dashboard' ), '', 'udb-advanced-settings' );

		// Misc section.
		add_settings_section( 'udb-misc-section', __( 'Misc', 'ultimate-dashboard' ), '', 'udb-misc-settings' );

		// Widget fields.
		add_settings_field( 'remove-all-widgets', __( 'Remove All Widgets', 'ultimate-dashboard' ), array( $this, 'remove_all_widgets_field' ), 'udb-widget-settings', 'udb-widgets-section' );
		add_settings_field( 'remove-individual-widgets', __( 'Remove Individual Widgets', 'ultimate-dashboard' ), array( $this, 'remove_individual_widgets_field' ), 'udb-widget-settings', 'udb-widgets-section' );
		add_settings_field( 'remove-3rd-party-widgets', __( 'Remove 3rd Party Widgets', 'ultimate-dashboard' ), array( $this, 'remove_3rd_party_widgets_field' ), 'udb-widget-settings', 'udb-3rd-party-widgets-section' );

		// Widget styling fields.
		add_settings_field( 'udb-icon-color-field', __( 'Icon/Text Color', 'ultimate-dashboard' ), array( $this, 'icon_color_field' ), 'udb-widget-styling-settings', 'udb-styling-section' );
		add_settings_field( 'udb-headline-color-field', __( 'Headline Color', 'ultimate-dashboard' ), array( $this, 'headline_color_field' ), 'udb-widget-styling-settings', 'udb-styling-section' );

		// General fields.
		add_settings_field( 'remove-help-tab-settings', __( 'Remove Help Tab', 'ultimate-dashboard' ), array( $this, 'remove_help_tab_field' ), 'udb-general-settings', 'udb-general-section' );
		add_settings_field( 'remove-screen-options-settings', __( 'Remove Screen Options Tab', 'ultimate-dashboard' ), array( $this, 'remove_screen_option_tab_field' ), 'udb-general-settings', 'udb-general-section' );
		add_settings_field( 'remove-admin-bar-settings', __( 'Remove Admin Bar from Frontend', 'ultimate-dashboard' ), array( $this, 'remove_admin_bar_field' ), 'udb-general-settings', 'udb-general-section' );
		add_settings_field( 'headline-settings', __( 'Custom Dashboard Headline', 'ultimate-dashboard' ), array( $this, 'headline_text_field' ), 'udb-general-settings', 'udb-general-section' );

		// Advanced fields.
		add_settings_field( 'custom-dashboard-css', __( 'Custom Dashboard CSS', 'ultimate-dashboard' ), array( $this, 'custom_dashboard_css_field' ), 'udb-advanced-settings', 'udb-advanced-section' );
		add_settings_field( 'custom-admin-css', __( 'Custom Admin CSS', 'ultimate-dashboard' ), array( $this, 'custom_admin_css_field' ), 'udb-advanced-settings', 'udb-advanced-section' );

		$remove_fa_description = '<p class="description">' . __( 'Use only if your icons are not displayed correctly.', 'ultimate-dashboard' ) . '</p>';

		// Misc fields.
		add_settings_field( 'remove_font_awesome', __( 'Remove Font Awesome', 'ultimate-dashboard' ) . $remove_fa_description, array( $this, 'remove_fontawesome_field' ), 'udb-misc-settings', 'udb-misc-section' );
		add_settings_field( 'remove-all-settings', __( 'Remove Data on Uninstall', 'ultimate-dashboard' ), array( $this, 'remove_on_uninstall_field' ), 'udb-misc-settings', 'udb-misc-section' );

	}

	/**
	 * Remove all widgets field.
	 */
	public function remove_all_widgets_field() {

		$field = require __DIR__ . '/templates/fields/remove-all-widgets.php';
		$field();

	}

	/**
	 * Remove individual widgets field.
	 */
	public function remove_individual_widgets_field() {

		$field = require __DIR__ . '/templates/fields/remove-individual-widgets.php';
		$field();

	}

	/**
	 * Remove 3rd party widgets field.
	 */
	public function remove_3rd_party_widgets_field() {

		$template = __DIR__ . '/templates/fields/remove-3rd-party-widgets.php';
		$template = apply_filters( 'udb_remove_3rd_party_widgets_field_path', $template );
		$field    = require $template;

		$field();

	}

	/**
	 * Icon color field.
	 */
	public function icon_color_field() {

		$field = require __DIR__ . '/templates/fields/icon-color.php';
		$field();

	}

	/**
	 * Headline color field.
	 */
	public function headline_color_field() {

		$field = require __DIR__ . '/templates/fields/headline-color.php';
		$field();

	}

	/**
	 * Remove help tab field.
	 */
	public function remove_help_tab_field() {

		$field = require __DIR__ . '/templates/fields/remove-help-tab.php';
		$field();

	}

	/**
	 * Remove screen option tab field.
	 */
	public function remove_screen_option_tab_field() {

		$field = require __DIR__ . '/templates/fields/remove-screen-option-tab.php';
		$field();

	}

	/**
	 * Remove admin bar field.
	 */
	public function remove_admin_bar_field() {

		$field = require __DIR__ . '/templates/fields/remove-admin-bar.php';
		$field();

	}

	/**
	 * Headline text field.
	 */
	public function headline_text_field() {

		$field = require __DIR__ . '/templates/fields/headline-text.php';
		$field();

	}

	/**
	 * Custom dashboard css field.
	 */
	public function custom_dashboard_css_field() {

		$field = require __DIR__ . '/templates/fields/custom-dashboard-css.php';
		$field();

	}

	/**
	 * Custom admin css field.
	 */
	public function custom_admin_css_field() {

		$field = require __DIR__ . '/templates/fields/custom-admin-css.php';
		$field();

	}

	/**
	 * Remove Font Awesome field.
	 */
	public function remove_fontawesome_field() {

		$field = require __DIR__ . '/templates/fields/remove-font-awesome.php';
		$field();

	}

	/**
	 * Remove settings on uninstall field.
	 */
	public function remove_on_uninstall_field() {

		$field = require __DIR__ . '/templates/fields/remove-on-uninstall.php';
		$field();

	}

}
