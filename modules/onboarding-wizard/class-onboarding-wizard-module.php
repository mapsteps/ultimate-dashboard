<?php
/**
 * Onboarding Wizard module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\OnboardingWizard;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup onboarding wizard module.
 */
class Onboarding_Wizard_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/onboarding-wizard';

	}

	/**
	 * Setup dashboard module.
	 */
	public function setup() {

		/**
		 * We need to remove them on multisite if current site is not a blueprint.
		 *
		 * @todo Find a better way to do it.
		 */
		add_action( 'admin_menu', array( $this, 'submenu_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		require_once __DIR__ . '/ajax/class-save-modules.php';
		new Ajax\Save_Modules();

		require_once __DIR__ . '/ajax/class-save-widgets.php';
		new Ajax\Save_Widgets();

		require_once __DIR__ . '/ajax/class-save-settings.php';
		new Ajax\Save_Settings();

		require_once __DIR__ . '/ajax/class-save-custom-login-url.php';
		new Ajax\Save_Custom_Login_Url();

		require_once __DIR__ . '/ajax/class-subscribe.php';
		new Ajax\Subscribe();

		require_once __DIR__ . '/ajax/class-skip-discount.php';
		new Ajax\SkipDiscount();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page(
			'edit.php?post_type=udb_widgets',
			__( 'Setup Wizard', 'ultimate-dashboard' ),
			__( 'Setup Wizard', 'ultimate-dashboard' ),
			apply_filters( 'udb_modules_capability', 'manage_options' ),
			'udb_onboarding_wizard',
			array( $this, 'submenu_page_content' ),
			0
		);

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/onboarding-wizard-template.php';
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
