<?php
/**
 * Save widgets.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Wizard\Ajax;

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

		add_action( 'wp_ajax_udb_wizard_save_widgets', [ $this, 'handler' ] );

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
		if ( ! wp_verify_nonce( $nonce, 'udb_wizard_save_widgets_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		foreach ( $_POST['widgets'] as $index => $widget ) {
			if ( is_string( $widget ) ) {
				$widget = sanitize_text_field( wp_unslash( $widget ) );
				array_push( $this->widgets, $widget );
			}
		}
	}

	/**
	 * Save the data.
	 */
	private function save() {

		// Initialize an array to hold the selected widgets.
		$udb_settings = [];

		// Iterate through the available widgets.
		foreach ( $this->available_widgets as $available_widget ) {

			// If the widget is selected (exists in $this->widgets), save it as 'true'.
			if ( in_array( $available_widget, $this->widgets, true ) ) {
				$udb_settings[ $available_widget ] = true;
			}
		}
		
		// Save the updated settings to the 'udb_settings' option.
		update_option( 'udb_settings', $udb_settings );

		wp_send_json_success( __( 'Widgets saved', 'ultimate-dashboard' ) );

	}


}
