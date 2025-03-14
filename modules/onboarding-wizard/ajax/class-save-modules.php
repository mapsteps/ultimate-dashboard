<?php
/**
 * Save modules.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\OnboardingWizard\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class Save_Modules {

	/**
	 * The available modules.
	 *
	 * @var array
	 */
	private $available_modules = [
		'white_label',
		'login_customizer',
		'login_redirect',
		'admin_pages',
		'admin_menu_editor',
		'admin_bar_editor',
	];

	/**
	 * The selected modules.
	 *
	 * @var array
	 */
	private $modules = [];

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_onboarding_wizard_save_modules', [ $this, 'handler' ] );

	}

	/**
	 * The request handler.
	 */
	public function handler() {

		$this->validate();
		$this->save();

	}

	/**
	 * Validate the data.
	 */
	private function validate() {

		$capability = apply_filters( 'udb_modules_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ), 401 );
		}

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_onboarding_wizard_save_modules_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		// At least login_customizer is selected :).
		if ( empty( $_POST['modules'] ) || ! is_array( $_POST['modules'] ) ) {
			wp_send_json_error( __( 'No modules selected', 'ultimate-dashboard' ), 401 );
		}

		foreach ( $_POST['modules'] as $index => $module ) {
			if ( is_string( $module ) ) {
				$module = sanitize_text_field( wp_unslash( $module ) );
				array_push( $this->modules, $module );
			}
		}
	}

	/**
	 * Save the data.
	 */
	private function save() {

		$module_statuses = [];

		// Iterate through the available modules and set their statuses.
		foreach ( $this->available_modules as $available_module ) {
			if ( in_array( $available_module, $this->modules, true ) ) {
				$module_statuses[ $available_module ] = 'true';
			} else {
				$module_statuses[ $available_module ] = 'false';
			}
		}

		// Save the modules status.
		update_option( 'udb_modules', $module_statuses );

		// Get the login_redirect value from udb_modules.
		$login_redirect = isset( $module_statuses['login_redirect'] ) ? $module_statuses['login_redirect'] : false;
		$login_redirect = 'true' === $login_redirect ? true : $login_redirect;
		$login_redirect = 'false' === $login_redirect ? false : $login_redirect;

		// Send a JSON response with the login_redirect data.
		wp_send_json_success( [
			'message'        => __( 'Modules saved', 'ultimate-dashboard' ),
			'login_redirect' => boolval( $login_redirect ),
		] );
	}


}
