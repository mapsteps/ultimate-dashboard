<?php
/**
 * JS Enqueue.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_wizard() ) {

		wp_enqueue_script( 'tiny-slider', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/wizard/assets/js/tiny-slider.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		// Plugin onboarding.
		wp_enqueue_script( 'udb-wizard', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/wizard/assets/js/wizard.js', array( 'tiny-slider' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		wp_localize_script(
			'udb-wizard',
			'udbWizard',
			array(
				'adminUrl' => admin_url(),
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'nonces'   => [
					'saveModules'  => wp_create_nonce( 'udb_wizard_save_modules_nonce' ),
					'subscribe'    => wp_create_nonce( 'udb_wizard_subscribe_nonce' ),
					'skipDiscount' => wp_create_nonce( 'udb_wizard_skip_discount_nonce' ),
				],
			)
		);

	}

};
