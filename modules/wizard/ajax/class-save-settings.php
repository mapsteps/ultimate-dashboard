<?php
/**
 * Save settings.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Wizard\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class Save_Settings {

	/**
	 * The available settings.
	 *
	 * @var array
	 */
	private $available_settings = [
		'remove_help_tab',
		'remove_screen_options',
		'remove_admin_bar',
	];

	/**
	 * The selected settings.
	 *
	 * @var array
	 */
	private $settings = [];

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_onboarding_wizard_save_general_settings', [ $this, 'handler' ] );

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

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_onboarding_wizard_save_general_settings_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		foreach ( $_POST['settings'] as $index => $setting ) {
			if ( is_string( $setting ) ) {
				$setting = sanitize_text_field( wp_unslash( $setting ) );
				array_push( $this->settings, $setting );
			}
		}
	}

	/**
	 * Save the data.
	 */
	private function save() {

		// Check if the 'udb_settings' option already exists.
		// Retrieve existing settings or an empty array if not present.
		$existing_settings = get_option( 'udb_settings', [] );

		// Initialize an array to hold the selected settings.
		$udb_settings = [];

		// Iterate through the available settings.
		foreach ( $this->available_settings as $available_setting ) {

			// If the setting is selected (exists in $this->settings), save it as 'true'.
			if ( in_array( $available_setting, $this->settings, true ) ) {
				$udb_settings[ $available_setting ] = true;
			} else {
				// If the setting is not selected, remove it from the existing settings.
				if ( isset( $existing_settings[ $available_setting ] ) ) {
					unset( $existing_settings[ $available_setting ] );
				}
			}
		}

		// Merge the new selected settings with existing ones.
		$updated_settings = array_merge( $existing_settings, $udb_settings );

		// Save the updated settings to the 'udb_settings' option.
		update_option( 'udb_settings', $updated_settings );

		wp_send_json_success( __( 'Settings saved', 'ultimate-dashboard' ) );

	}


}
