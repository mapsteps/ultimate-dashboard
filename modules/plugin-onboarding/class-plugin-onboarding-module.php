<?php
/**
 * Plugin onboarding module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\PluginOnboarding;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup plugin onboarding module.
 */
class Plugin_Onboarding_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/plugin-onboarding';

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

		// @todo Please remove these 3 actions on multisite if current site is not a blueprint.
		add_action( 'admin_menu', array( self::get_instance(), 'submenu_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );

		require_once __DIR__ . '/ajax/class-save-modules.php';
		new Ajax\Save_Modules();

		require_once __DIR__ . '/ajax/class-subscribe.php';
		new Ajax\Subscribe();

		require_once __DIR__ . '/ajax/class-skip-discount.php';
		new Ajax\SkipDiscount();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Setup', 'ultimate-dashboard' ), __( 'Setup', 'ultimate-dashboard' ), apply_filters( 'udb_modules_capability', 'manage_options' ), 'udb_plugin_onboarding', array( $this, 'submenu_page_content' ) );

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/plugin-onboarding-template.php';
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

}
