<?php
/**
 * Save settings.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\OnboardingWizard\Ajax;

/**
 * AJAX handler to save settings from the onboarding wizard.
 */
class Save_Settings {

	/**
	 * The available settings.
	 *
	 * @var array
	 */
	private $available_settings = array(
		'remove_help_tab',
		'remove_screen_options',
		'remove_admin_bar',
	);

	/**
	 * The selected settings.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * The selected roles to remove the admin bar for.
	 *
	 * @var array
	 */
	private $selected_roles = array();

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

		$capability = apply_filters( 'udb_modules_capability', 'manage_options' );

		if ( ! current_user_can( $capability ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ), 401 );
		}

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_onboarding_wizard_save_general_settings_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		// If settings exists, it must be an array.
		if ( isset( $_POST['settings'] ) && ! is_array( $_POST['settings'] ) ) {
			wp_send_json_error( __( 'Settings must be an array', 'ultimate-dashboard' ), 400 );
		}

		if ( ! empty( $_POST['settings'] ) ) {
			foreach ( $_POST['settings'] as $setting ) {
				if ( ! is_string( $setting ) ) {
					continue;
				}

				if ( ! in_array( $setting, $this->available_settings, true ) ) {
					continue;
				}

				$this->settings[] = sanitize_text_field( wp_unslash( $setting ) );
			}
		}

		// 'remove_admin_bar' is not part of the 'settings' AJAX payload.
		$this->settings[] = 'remove_admin_bar';

		// If selected roles exists, it must be an array.
		if ( isset( $_POST['selected_roles'] ) && ! is_array( $_POST['selected_roles'] ) ) {
			wp_send_json_error( __( 'Selected roles must be an array', 'ultimate-dashboard' ), 400 );
		}

		if ( ! empty( $_POST['selected_roles'] ) ) {
			foreach ( $_POST['selected_roles'] as $role ) {
				if ( ! is_string( $role ) ) {
					continue;
				}

				$this->selected_roles[] = sanitize_text_field( wp_unslash( $role ) );
			}
		}

	}

	/**
	 * Save the data.
	 */
	private function save() {

		$udb_settings = get_option( 'udb_settings', array() );

		foreach ( $this->available_settings as $available_setting ) {
			if ( in_array( $available_setting, $this->settings, true ) ) {
				if ( 'remove_admin_bar' === $available_setting ) {
					$udb_settings[ $available_setting ] = $this->selected_roles;
				} else {
					$udb_settings[ $available_setting ] = 1;
				}

				continue;
			}

			// If the setting is not selected, remove it from the udb settings.
			if ( isset( $udb_settings[ $available_setting ] ) ) {
				unset( $udb_settings[ $available_setting ] );
			}
		}

		// Save the updated settings.
		update_option( 'udb_settings', $udb_settings );

		wp_send_json_success( __( 'Settings saved', 'ultimate-dashboard' ) );

	}


}
