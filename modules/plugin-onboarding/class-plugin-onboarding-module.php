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
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * The referrer where UDB was installed from.
	 *
	 * @var string
	 */
	public $referrer;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/plugin-onboarding';

	}

	/**
	 * Setup dashboard module.
	 *
	 * @param string $referrer The referrer where UDB was installed from.
	 */
	public function setup( $referrer = '' ) {

		$this->referrer = $referrer;

		/**
		 * We need to remove them on multisite if current site is not a blueprint.
		 * But we don't use singleton pattern because we need to use the referrer.
		 *
		 * @todo Find a better way to do it.
		 */
		add_action( 'admin_menu', array( $this, 'submenu_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

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
		$template( $this->referrer );

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
