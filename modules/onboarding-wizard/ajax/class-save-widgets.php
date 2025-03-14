<?php
/**
 * Save widgets.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\OnboardingWizard\Ajax;

use Udb\Helpers\Widget_Helper;

/**
 * Class to manage ajax request of migration to UDB.
 */
class Save_Widgets {

	/**
	 * The available widgets.
	 *
	 * @var string[]
	 */
	private $available_widgets = array(
		'remove-all',
		'welcome_panel',
	);

	/**
	 * The selected widgets.
	 *
	 * @var string[]
	 */
	private $widgets = array();

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

		$widgets = ( new Widget_Helper() )->get_default();

		$widget_keys = array_keys( $widgets );

		$this->available_widgets = array_merge( $this->available_widgets, $widget_keys );
		$this->available_widgets = array_unique( $this->available_widgets );
		$this->available_widgets = array_values( $this->available_widgets );

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
		if ( ! wp_verify_nonce( $nonce, 'udb_onboarding_wizard_save_widgets_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		// If widgets exists, it must be an array.
		if ( isset( $_POST['widgets'] ) && ! is_array( $_POST['widgets'] ) ) {
			wp_send_json_error( __( 'Widgets must be an array', 'ultimate-dashboard' ), 401 );
		}

		if ( ! empty( $_POST['widgets'] ) ) {
			foreach ( $_POST['widgets'] as $widget ) {
				if ( ! is_string( $widget ) ) {
					continue;
				}

				$this->widgets[] = sanitize_text_field( wp_unslash( $widget ) );
			}
		}

	}

	/**
	 * Save the data.
	 */
	private function save() {

		$settings = get_option( 'udb_settings', array() );

		foreach ( $this->available_widgets as $available_widget ) {
			if ( in_array( $available_widget, $this->widgets, true ) ) {
				$settings[ $available_widget ] = 1;
				continue;
			}

			if ( isset( $settings[ $available_widget ] ) ) {
				unset( $settings[ $available_widget ] );
			}
		}

		update_option( 'udb_settings', $settings );

		wp_send_json_success( __( 'Widgets saved', 'ultimate-dashboard' ) );

	}


}
