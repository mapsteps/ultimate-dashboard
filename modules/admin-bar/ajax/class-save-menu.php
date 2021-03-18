<?php
/**
 * Save menu.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminBar\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to handle ajax request to save admin bar.
 */
class Save_Menu {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Save menu.
	 */
	public function save() {
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';

		if ( ! wp_verify_nonce( $nonce, 'udb_admin_bar_save_menu' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ) );
		}

		$_POST['menu'] = json_decode( stripslashes( $_POST['menu'] ), true );

		update_option( 'udb_admin_bar', $_POST['menu'] );

		wp_send_json_success( __( 'Menu updated successfully', 'ultimate-dashboard' ) );
	}

}
