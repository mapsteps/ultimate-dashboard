<?php
/**
 * Subscribe.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\PluginOnboarding\Ajax;

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
	 * The referrer where UDB was installed from.
	 *
	 * @var string
	 */
	private $referrer;

	/**
	 * Class constructor.
	 */
	public function __construct() {

		add_action( 'wp_ajax_udb_plugin_onboarding_subscribe', [ $this, 'handler' ] );

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

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to access this page', 'ultimate-dashboard' ), 401 );
		}

		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// Check if nonce is incorrect.
		if ( ! wp_verify_nonce( $nonce, 'udb_plugin_onboarding_subscribe_nonce' ) ) {
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

		$this->referrer = isset( $_POST['referrer'] ) ? sanitize_text_field( wp_unslash( $_POST['referrer'] ) ) : '';
	}

	/**
	 * Save the data.
	 */
	private function subscribe() {

		// Mailerlite related vars.
		$mailerlite_api_key  = '17ca6c148dc69064b0f4f409776be3b8';
		$mailerlite_group_id = '111311793';
		$mailerlite_api_url  = "https://api.mailerlite.com/api/v2/groups/$mailerlite_group_id/subscribers";

		$mailerlite_subscriber = [
			'email'  => $this->email,
			'name'   => $this->name,
			'fields' => [],
		];

		$response = wp_remote_post(
			$mailerlite_api_url,
			[
				'body'    => wp_json_encode( $mailerlite_subscriber ),
				'headers' => [
					'X-MailerLite-ApiKey' => $mailerlite_api_key,
					'Content-Type'        => 'application/json',
					'Accept'              => 'application/json',
				],
			]
		);

		if ( ! is_wp_error( $response ) ) {
			if ( 'erident' === $this->referrer ) {
				delete_option( 'udb_migration_from_erident' );
			} elseif ( 'kirki' === $this->referrer ) {
				delete_option( 'udb_referred_by_kirki' );
			}

			wp_send_json_success( __( 'Subscription done', 'ultimate - dashboard' ) );
		}

		wp_send_json_error( $response->get_error_message(), 403 );

	}

}
