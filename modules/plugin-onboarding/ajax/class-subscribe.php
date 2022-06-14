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
	}

	/**
	 * Save the data.
	 */
	private function subscribe() {

		require_once ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/plugin-onboarding/vendor/autoload.php';

		// Mailerlite related vars.
		// $mailerlite_api      = '1750aca41d5ce588d3587083d3788e12';
		$mailerlite_api = '';
		// $mailerlite_group_id = '111240296';
		$mailerlite_group_id = '';

		$mailerlite_group_api = ( new \MailerLiteApi\MailerLite( $mailerlite_api ) )->groups();

		$mailerlite_subscriber = [
			'email'  => $this->email,
			'fields' => [
				'name' => $this->name,
			],
		];

		$response = $mailerlite_group_api->addSubscriber( $mailerlite_group_id, $mailerlite_subscriber );

		delete_option( 'udb_migration_from_erident' );

		wp_send_json_success( __( 'Subscription done', 'ultimate-dashboard' ) );

	}

}
