<?php
/**
 * Subscribe.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\OnboardingWizard\Ajax;

/**
 * Class to manage ajax request of migration to UDB.
 */
class Subscribe {

	/**
	 * The subscriber name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The subscriber email.
	 *
	 * @var string
	 */
	private $email;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_onboarding_wizard_subscribe', [ $this, 'handler' ] );

	}

	/**
	 * The request handler.
	 */
	public function handler() {

		$this->validate();
		$this->subscribe();

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
		if ( ! wp_verify_nonce( $nonce, 'udb_onboarding_wizard_subscribe_nonce' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ), 401 );
		}

		$this->name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';

		if ( empty( $this->name ) ) {
			wp_send_json_error( __( 'Name field is empty', 'ultimate-dashboard' ), 401 );
		}

		$this->email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';

		if ( empty( $this->email ) ) {
			wp_send_json_error( __( 'Email field is empty', 'ultimate-dashboard' ), 401 );
		}
	}

	/**
	 * Save the data.
	 *
	 * Use secure proxy endpoint instead of direct Mailerlite API.
	 * The proxy is hosted on our secure server to keep the API key private.
	 */
	private function subscribe() {

		$proxy_url = 'https://ultimatedashboard.io/wp-json/mailerlite-proxy/v1/subscribe';
		$group_id  = '112818510';

		$subscriber_data = [
			'email'    => $this->email,
			'name'     => $this->name,
			'group_id' => $group_id,
		];

		$headers = [
			'Content-Type' => 'application/json',
		];

		$response = wp_remote_post(
			$proxy_url,
			[
				'body'    => wp_json_encode( $subscriber_data ),
				'headers' => $headers,
				'timeout' => 15,
			]
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message(), 403 );
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( $response_code >= 200 && $response_code < 300 ) {
			update_option( 'udb_onboarding_wizard_completed', true );
			wp_send_json_success( __( 'Subscription done', 'ultimate-dashboard' ) );
		}

		$error_data = json_decode( $response_body, true );
		$error_message = isset( $error_data['message'] ) ? $error_data['message'] : __( 'Subscription failed', 'ultimate-dashboard' );
		wp_send_json_error( $error_message, $response_code );

	}

}
