<?php
/**
 * Get menu & submenu.
 *
 * @package Ultimate Dashboard
 */

namespace Udb\AdminBar\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper;
use Udb\Helpers\User_Helper;
use Udb\Helpers\Array_Helper;

/**
 * Class to get menu & submenu.
 */
class Get_Menu {
	/**
	 * Whether to get menu by role or by user_id.
	 *
	 * @var string
	 */
	public $by = 'role';

	/**
	 * The role value.
	 *
	 * @var string
	 */
	public $role = '';

	/**
	 * The user_id value.
	 *
	 * @var int
	 */
	public $user_id = 0;

	/**
	 * Get menu & submenu.
	 */
	public function ajax() {

		$nonce         = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		$this->role    = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : '';
		$this->by      = isset( $_POST['by'] ) ? sanitize_text_field( $_POST['by'] ) : '';
		$this->user_id = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'udb_admin_bar_get_menu' ) ) {
			wp_send_json_error( __( 'Invalid token', 'ultimate-dashboard' ) );
		}

		if ( ! $this->role && ! $this->user_id ) {
			wp_send_json_error( __( 'User role or id must be specified', 'ultimate-dashboard' ) );
		}

		if ( $this->user_id ) {
			$this->by   = 'user_id';
			$user       = get_userdata( $this->user_id );
			$this->role = $user->roles[0];
		}

		do_action( 'udb_ajax_before_get_admin_bar' );

		$saved_menu_items = get_option( 'udb_admin_bar', array() );

		if ( 'user_id' === $this->by ) {
			$menu_items = isset( $saved_menu_items[ 'user_id_' . $this->user_id ] ) ? $saved_menu_items[ 'user_id_' . $this->user_id ] : array();
		} else {
			$menu_items = isset( $saved_menu_items[ $this->role ] ) ? $saved_menu_items[ $this->role ] : array();
		}

		wp_send_json_success( $menu_items );

	}

}
