<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_wizard() ) {

		wp_enqueue_script( 'tiny-slider', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/plugin-onboarding/assets/js/tiny-slider.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Select2 JS.
		wp_enqueue_script( 'select2', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Onboarding Wizard JS.
		wp_enqueue_script( 'udb-onboarding-wizard', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/onboarding-wizard/assets/js/onboarding-wizard.js', array( 'tiny-slider', 'select2' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		wp_localize_script(
			'udb-onboarding-wizard',
			'udbOnboardingWizard',
			array(
				'adminUrl' => admin_url(),
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'nonces'   => [
					'saveModules'         => wp_create_nonce( 'udb_onboarding_wizard_save_modules_nonce' ),
					'saveWidgets'         => wp_create_nonce( 'udb_onboarding_wizard_save_widgets_nonce' ),
					'saveGeneralSettings' => wp_create_nonce( 'udb_onboarding_wizard_save_general_settings_nonce' ),
					'saveCustomLoginUrl'  => wp_create_nonce( 'udb_onboarding_wizard_save_custom_login_url_nonce' ),
					'subscribe'           => wp_create_nonce( 'udb_onboarding_wizard_subscribe_nonce' ),
					'skipDiscount'        => wp_create_nonce( 'udb_onboarding_wizard_skip_discount_nonce' ),
				],
			)
		);

	}

};
