<?php
/**
 * Save widgets.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Onboarding_Wizard\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class Save_Widgets {

	/**
	 * The available widgets.
	 *
	 * @var array
	 */
	private $available_widgets = [
		'remove-all',
		'welcome_panel',
		'dashboard_activity',
		'dashboard_right_now',
		'dashboard_quick_press',
		'dashboard_site_health',
		'dashboard_primary',
	];

	/**
	 * The selected widgets.
	 *
	 * @var array
	 */
	private $widgets = [];

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_onboarding_wizard_save_widgets', [ $this, 'handler' ] );

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

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to access this page', 'ultimate-dashboard' ), 401 );
		}

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_onboarding_wizard_save_widgets_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		// If widgets exists, it must be an array.
		if ( isset( $_POST['widgets'] ) && ! is_array( $_POST['widgets'] ) ) {
			wp_send_json_error( __( 'Widgets must be an array', 'ultimate-dashboard' ), 401 );
		}

		if ( ! empty( $_POST['widgets'] ) ) {
			foreach ( $_POST['widgets'] as $index => $widget ) {
				if ( is_string( $widget ) ) {
					$widget = sanitize_text_field( wp_unslash( $widget ) );
					array_push( $this->widgets, $widget );
				}
			}
		}

	}

	/**
	 * Save the data.
	 */
	private function save() {

		/**
		 * Check if the 'udb_settings' option already exists.
		 * Retrieve the existing settings or an empty array if not present./
		 */
		$existing_settings = get_option( 'udb_settings', [] );

		// Initialize an array to hold the selected widgets.
		$udb_settings = [];

		// Iterate through the available widgets.
		foreach ( $this->available_widgets as $available_widget ) {

			// If the widget is selected (exists in $this->widgets), save it as 'true'.
			if ( in_array( $available_widget, $this->widgets, true ) ) {
				$udb_settings[ $available_widget ] = true;
			} else {
				// If the widget is not selected, unset it from the existing settings.
				if ( isset( $existing_settings[ $available_widget ] ) ) {
					unset( $existing_settings[ $available_widget ] );
				}
			}
		}

		// Merge the new selected widgets with existing settings.
		$updated_settings = array_merge( $existing_settings, $udb_settings );

		// Save the updated settings to the 'udb_settings' option.
		update_option( 'udb_settings', $updated_settings );

		wp_send_json_success( __( 'Widgets saved', 'ultimate-dashboard' ) );

	}


}
