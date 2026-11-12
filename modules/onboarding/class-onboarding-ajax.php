<?php
/**
 * Onboarding AJAX handler.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Onboarding;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Widget_Helper;
use Udb\Helpers\Login_Url_Helper;

/**
 * Saves the onboarding steps. One endpoint, one permission & nonce check, one method per step.
 */
class Onboarding_Ajax {

	/**
	 * The modules a user can switch on/off.
	 *
	 * @var string[]
	 */
	const MODULES = array( 'white_label', 'login_customizer', 'login_redirect', 'admin_pages', 'admin_menu_editor', 'admin_bar_editor' );

	/**
	 * The general settings of the wizard's settings step.
	 *
	 * @var string[]
	 */
	const GENERAL_SETTINGS = array( 'remove_help_tab', 'remove_screen_options' );

	/**
	 * The options other plugins set when they send users here (referrer => option).
	 *
	 * @var string[]
	 */
	const REFERRERS = array(
		'erident' => 'udb_migration_from_erident',
		'kirki'   => 'udb_referred_by_kirki',
	);

	/**
	 * Handle the "udb_onboarding" AJAX request.
	 */
	public static function handle() {

		if ( ! current_user_can( apply_filters( 'udb_modules_capability', 'manage_options' ) ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action', 'ultimate-dashboard' ), 403 );
		}

		check_ajax_referer( 'udb_onboarding', 'nonce' );

		$step = isset( $_POST['step'] ) ? sanitize_key( wp_unslash( $_POST['step'] ) ) : '';

		switch ( $step ) {
			case 'modules':
				self::save_modules();
				break;
			case 'widgets':
				self::save_widgets();
				break;
			case 'settings':
				self::save_settings();
				break;
			case 'login_url':
				self::save_login_url();
				break;
			case 'subscribe':
				self::subscribe();
				break;
			case 'skip_discount':
				self::finish();
				wp_send_json_success();
				break;
		}

		wp_send_json_error( __( 'Invalid request', 'ultimate-dashboard' ), 400 );

	}

	/**
	 * Read a list of strings from $_POST.
	 *
	 * @param string $key The $_POST key.
	 *
	 * @return string[]
	 */
	private static function posted_list( $key ) {

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checked in handle().
		if ( empty( $_POST[ $key ] ) || ! is_array( $_POST[ $key ] ) ) {
			return array();
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Checked in handle(); sanitized below.
		$values = array_filter( wp_unslash( $_POST[ $key ] ), 'is_string' );

		return array_values( array_map( 'sanitize_text_field', $values ) );

	}

	/**
	 * Modules step: every module is saved as on or off.
	 */
	private static function save_modules() {

		$selected = self::posted_list( 'modules' );
		$modules  = array();

		foreach ( self::MODULES as $module ) {
			$modules[ $module ] = in_array( $module, $selected, true ) ? 'true' : 'false';
		}

		update_option( 'udb_modules', $modules );

		wp_send_json_success();

	}

	/**
	 * Widgets step: remove all / individual default widgets.
	 */
	private static function save_widgets() {

		$available = array_merge( array( 'remove-all', 'welcome_panel' ), array_keys( ( new Widget_Helper() )->get_default() ) );
		$selected  = self::posted_list( 'widgets' );
		$settings  = get_option( 'udb_settings', array() );
		$settings  = is_array( $settings ) ? $settings : array();

		foreach ( $available as $widget ) {
			if ( in_array( $widget, $selected, true ) ) {
				$settings[ $widget ] = 1;
			} else {
				unset( $settings[ $widget ] );
			}
		}

		update_option( 'udb_settings', $settings );

		wp_send_json_success();

	}

	/**
	 * Settings step: help tab, screen options, and the roles that don't see the admin bar on the frontend.
	 */
	private static function save_settings() {

		$selected = self::posted_list( 'settings' );
		$settings = get_option( 'udb_settings', array() );
		$settings = is_array( $settings ) ? $settings : array();

		foreach ( self::GENERAL_SETTINGS as $setting ) {
			if ( in_array( $setting, $selected, true ) ) {
				$settings[ $setting ] = 1;
			} else {
				unset( $settings[ $setting ] );
			}
		}

		// Only existing roles, or "all".
		$roles = array_values( array_intersect( self::posted_list( 'roles' ), array_merge( array( 'all' ), array_keys( wp_roles()->roles ) ) ) );

		if ( in_array( 'all', $roles, true ) ) {
			$roles = array( 'all' );
		}

		if ( $roles ) {
			$settings['remove_admin_bar'] = $roles;
		} else {
			unset( $settings['remove_admin_bar'] );
		}

		update_option( 'udb_settings', $settings );

		wp_send_json_success();

	}

	/**
	 * Login URL step.
	 */
	private static function save_login_url() {

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checked in handle().
		$slug  = isset( $_POST['login_url'] ) ? sanitize_text_field( wp_unslash( $_POST['login_url'] ) ) : '';
		$slug  = trim( $slug, '/' );
		$check = ( new Login_Url_Helper() )->validate_login_slug( $slug );

		if ( is_wp_error( $check ) ) {
			wp_send_json_error( $check->get_error_message(), 400 );
		}

		$settings = get_option( 'udb_login_redirect', array() );
		$settings = is_array( $settings ) ? $settings : array();

		$settings['login_url_slug'] = $slug;

		update_option( 'udb_login_redirect', $settings );

		wp_send_json_success();

	}

	/**
	 * Subscribe to the newsletter (via the proxy on ultimatedashboard.io), then finish.
	 */
	private static function subscribe() {

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Checked in handle().
		$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		// phpcs:enable

		if ( '' === $name ) {
			wp_send_json_error( __( 'Please enter your name.', 'ultimate-dashboard' ), 400 );
		}

		if ( ! is_email( $email ) ) {
			wp_send_json_error( __( 'Please enter a valid email address.', 'ultimate-dashboard' ), 400 );
		}

		// Each variant has its own newsletter group.
		$group_id = self::referrer() ? '111311793' : '112818510';

		$response = wp_remote_post(
			'https://ultimatedashboard.io/wp-json/mailerlite-proxy/v1/subscribe',
			array(
				'body'    => wp_json_encode(
					array(
						'email'    => $email,
						'name'     => $name,
						'group_id' => $group_id,
					)
				),
				'headers' => array( 'Content-Type' => 'application/json' ),
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( $response->get_error_message(), 502 );
		}

		$code = (int) wp_remote_retrieve_response_code( $response );

		if ( $code < 200 || $code >= 300 ) {
			$body    = json_decode( wp_remote_retrieve_body( $response ), true );
			$message = is_array( $body ) && ! empty( $body['message'] ) && is_string( $body['message'] ) ? $body['message'] : __( 'Subscription failed', 'ultimate-dashboard' );

			wp_send_json_error( sanitize_text_field( $message ), 502 );
		}

		self::finish();

		wp_send_json_success();

	}

	/**
	 * The plugin the user came from, if that plugin really sent them here.
	 *
	 * @return string "erident", "kirki" or "".
	 */
	private static function referrer() {

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Checked in handle().
		$referrer = isset( $_POST['referrer'] ) ? sanitize_key( wp_unslash( $_POST['referrer'] ) ) : '';

		return isset( self::REFERRERS[ $referrer ] ) && get_option( self::REFERRERS[ $referrer ] ) ? $referrer : '';

	}

	/**
	 * Mark the onboarding as done: the migration variant for its referrer, otherwise the wizard.
	 */
	private static function finish() {

		$referrer = self::referrer();

		if ( $referrer ) {
			delete_option( self::REFERRERS[ $referrer ] );
		} else {
			update_option( 'udb_onboarding_wizard_completed', 1 );
		}

	}

}
