<?php
/**
 * Tool module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Tool;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup tool module.
 */
class Tool_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/tool';

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
	 * Setup tool module.
	 */
	public function setup() {

		/**
		 * These 4 actions will be removed on multisite if current site is not a blueprint.
		 */
		add_action( 'admin_menu', array( self::get_instance(), 'submenu_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );
		add_action( 'admin_init', array( self::get_instance(), 'add_settings' ) );

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Tools', 'ultimate-dashboard' ), __( 'Tools', 'ultimate-dashboard' ), apply_filters( 'udb_tools_capability', 'manage_options' ), 'udb_tools', array( $this, 'submenu_page_content' ) );

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/tools-template.php';
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
	 * Register the export & import form handlers (admin-post.php).
	 *
	 * Kept on "admin_init" under this name: PRO's multisite module unhooks it on non-blueprint sites,
	 * which switches the tools off there. admin-post.php runs "admin_init" before the handlers.
	 */
	public function add_settings() {

		add_action( 'admin_post_udb_tools_export', array( $this, 'process_export' ) );
		add_action( 'admin_post_udb_tools_import', array( $this, 'process_import' ) );

	}

	/**
	 * Whether the current user may use the tools.
	 *
	 * @return bool
	 */
	public function can_use_tools() {

		return current_user_can( 'manage_options' ) && current_user_can( apply_filters( 'udb_tools_capability', 'manage_options' ) );

	}

	/**
	 * The URL of the tools page.
	 *
	 * @return string
	 */
	public function tools_url() {

		return admin_url( 'edit.php?post_type=udb_widgets&page=udb_tools' );

	}

	/**
	 * Render export field.
	 *
	 * @param array $args Unused (kept for backwards compatibility).
	 */
	public function render_export_field( $args = array() ) {

		$field = require __DIR__ . '/templates/fields/export-field.php';
		$field();

	}

	/**
	 * Render import field.
	 *
	 * @param array $args Unused (kept for backwards compatibility).
	 */
	public function render_import_field( $args = array() ) {

		$field = require __DIR__ . '/templates/fields/import-field.php';
		$field();

	}

	/**
	 * Process the export (admin-post.php?action=udb_tools_export): sends the .json file.
	 */
	public function process_export() {

		check_admin_referer( 'udb_tools_export', 'udb_tools_nonce' );

		if ( ! $this->can_use_tools() ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to export these settings.', 'ultimate-dashboard' ), 403 );
		}

		$process = require __DIR__ . '/inc/process-export.php';
		$process();

		// Only reached when there was nothing to export: go back to the tools page with the notice.
		set_transient( 'settings_errors', get_settings_errors(), 30 );

		wp_safe_redirect( add_query_arg( 'settings-updated', 'true', $this->tools_url() ) );
		exit;

	}

	/**
	 * Process the import (admin-post.php?action=udb_tools_import), then go back to the tools page.
	 *
	 * The notices are added with add_settings_error() (PRO adds its own the same way) and shown
	 * on the tools page, like options.php does it.
	 */
	public function process_import() {

		check_admin_referer( 'udb_tools_import', 'udb_tools_nonce' );

		if ( ! $this->can_use_tools() ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to import these settings.', 'ultimate-dashboard' ), 403 );
		}

		// Older versions left an empty "udb_import" option behind on every import.
		delete_option( 'udb_import' );

		$process = require __DIR__ . '/inc/process-import.php';
		$process();

		set_transient( 'settings_errors', get_settings_errors(), 30 );

		wp_safe_redirect( add_query_arg( 'settings-updated', 'true', $this->tools_url() ) );
		exit;

	}

}
