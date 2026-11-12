<?php
/**
 * Enqueue the Modules page script.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module ) {

	if ( $module->screen()->is_features() ) {

		wp_enqueue_script( 'udb-features', ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/js/feature.js', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

		wp_localize_script(
			'udb-features',
			'udbModules',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'udb_module_nonce_action' ),
				'strings' => array(
					'error'    => __( 'An error occurred while saving.', 'ultimate-dashboard' ),
					'active'   => __( 'Active', 'ultimate-dashboard' ),
					'inactive' => __( 'Inactive', 'ultimate-dashboard' ),
				),
			)
		);

	}

};
