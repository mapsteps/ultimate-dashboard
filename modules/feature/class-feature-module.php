<?php
/**
 * Feature module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Feature;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Setup;
use Udb\Base\Base_Module;

/**
 * Class to setup dashboard module.
 */
class Feature_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature';

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
	 * Setup dashboard module.
	 */
	public function setup() {

		/**
		 * These 4 actions will be removed on multisite if current site is not a blueprint.
		 */
		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_ajax_udb_handle_module_actions', array( $this, 'handle_module_actions' ) );

		// The module output.
		require_once __DIR__ . '/class-feature-output.php';
		$output = new Feature_Output();
		$output->setup();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Modules', 'ultimate-dashboard' ), __( 'Modules', 'ultimate-dashboard' ), apply_filters( 'udb_modules_capability', 'manage_options' ), 'udb_features', array( $this, 'submenu_page_content' ) );

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/feature-template.php';
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
	 * Activation/deactivation action.
	 */
	public function handle_module_actions() {

		if ( empty( $_POST ) || ! wp_verify_nonce( $_POST['nonce'], 'udb_module_nonce_action' ) ) {
			wp_send_json_error( __( 'Invalid nonce', 'ultimate-dashboard' ) );
		}

		$module        = new Setup();
		$saved_modules = $module->saved_modules();
		$name          = sanitize_key( $_POST['name'] );
		$status        = sanitize_key( $_POST['status'] );

		$saved_modules[ $name ] = $status;

		update_option( 'udb_modules', $saved_modules );

		wp_send_json_success( array( 'message' => __( 'Saved', 'ultimate-dashboard' ) ) );

	}

}
