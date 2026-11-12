<?php
/**
 * Onboarding module.
 *
 * One onboarding with two variants:
 * - "wizard":    the Setup Wizard after a fresh install (modules, widgets, settings, login URL, discount).
 * - "migration": the short "Setup" for users who came from another plugin (Erident, Kirki): modules and discount.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Onboarding;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to set up the onboarding.
 */
class Onboarding_Module extends Base_Module {

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * The variant: "wizard" or "migration".
	 *
	 * @var string
	 */
	public $variant = 'wizard';

	/**
	 * The plugin the user came from (migration variant only): "erident" or "kirki".
	 *
	 * @var string
	 */
	public $referrer = '';

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/onboarding';

	}

	/**
	 * Setup the onboarding.
	 *
	 * @param string $variant  The variant: "wizard" or "migration".
	 * @param string $referrer The plugin the user came from (migration variant only).
	 */
	public function setup( $variant = 'wizard', $referrer = '' ) {

		$this->variant  = 'migration' === $variant ? 'migration' : 'wizard';
		$this->referrer = $referrer;

		add_action( 'admin_menu', array( $this, 'submenu_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// Both variants can be loaded in the same request; the AJAX handler is registered once.
		if ( ! has_action( 'wp_ajax_udb_onboarding', array( __NAMESPACE__ . '\\Onboarding_Ajax', 'handle' ) ) ) {
			require_once __DIR__ . '/class-onboarding-ajax.php';
			add_action( 'wp_ajax_udb_onboarding', array( __NAMESPACE__ . '\\Onboarding_Ajax', 'handle' ) );
		}

	}

	/**
	 * The admin page slug of the current variant. Other plugins link to these (Erident to "udb_plugin_onboarding").
	 *
	 * @return string
	 */
	public function page_slug() {

		return 'migration' === $this->variant ? 'udb_plugin_onboarding' : 'udb_onboarding_wizard';

	}

	/**
	 * Add the submenu page.
	 */
	public function submenu_page() {

		$title = 'migration' === $this->variant ? __( 'Setup', 'ultimate-dashboard' ) : __( 'Setup Wizard', 'ultimate-dashboard' );

		add_submenu_page(
			'edit.php?post_type=udb_widgets',
			$title,
			$title,
			apply_filters( 'udb_modules_capability', 'manage_options' ),
			$this->page_slug(),
			array( $this, 'submenu_page_content' ),
			'migration' === $this->variant ? null : 0
		);

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/onboarding-template.php';
		$template( $this->variant, $this->referrer );

	}

	/**
	 * Enqueue the styles & scripts on the onboarding page.
	 */
	public function enqueue() {

		$screen = get_current_screen();

		if ( ! $screen || 'udb_widgets_page_' . $this->page_slug() !== $screen->id ) {
			return;
		}

		wp_enqueue_style( 'heatbox', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/heatbox.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );
		wp_enqueue_style( 'udb-onboarding', $this->url . '/assets/css/onboarding.css', array( 'heatbox' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

		$script_deps = array( 'jquery' );

		if ( 'wizard' === $this->variant ) {
			// The role picker on the settings step.
			wp_enqueue_style( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/select2.min.css', array(), '4.1.0-rc.0' );
			wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), '4.1.0-rc.0', true );

			$script_deps[] = 'select2';
		}

		wp_enqueue_script( 'udb-onboarding', $this->url . '/assets/js/onboarding.js', $script_deps, ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		wp_localize_script(
			'udb-onboarding',
			'udbOnboarding',
			array(
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'adminUrl' => admin_url(),
				'nonce'    => wp_create_nonce( 'udb_onboarding' ),
				'variant'  => $this->variant,
				'referrer' => $this->referrer,
				'pageSlug' => $this->page_slug(),
				'strings'  => array(
					'error'      => __( 'Something went wrong. Please try again.', 'ultimate-dashboard' ),
					'selectRole' => __( 'Select Role', 'ultimate-dashboard' ),
					/* translators: %1$s: The current step, %2$s: The number of steps. */
					'progress'   => __( 'Step %1$s of %2$s', 'ultimate-dashboard' ),
				),
			)
		);

	}

}
