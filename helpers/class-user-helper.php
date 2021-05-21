<?php
/**
 * User helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup user helper.
 */
class User_Helper {

	/**
	 * Simulate user with specified role.
	 *
	 * Use this wisely.
	 * If you're sure this function will only run on specific condition,
	 * then you can omit the 2nd & 3rd parameter (keep them `false`).
	 *
	 * @param string $role_key The role key.
	 * @param bool   $change_user_id Whether or not to change the user ID.
	 * @param bool   $change_username Whether or not to change the user_login.
	 */
	public function simulate_role( $role_key, $change_user_id = false, $change_username = false ) {
		global $current_user;

		$role = get_role( $role_key );

		if ( $change_user_id ) {
			$current_user->ID = PHP_INT_MAX;
		}

		if ( $change_username ) {
			$current_user->wp_user_login = $current_user->user_login;
			$current_user->user_login    = 'udb_username_' . wp_rand( 3, 5 );
		}

		$current_user->allcaps = $role->capabilities;
		$current_user->caps    = $role->capabilities;
		$current_user->roles   = array( $role_key );
	}

	/**
	 * Change the username of current user.
	 *
	 * @param string $username The wanted username.
	 */
	public function change_current_username( $username = '' ) {

		global $current_user;

		$current_user->wp_user_login = $current_user->user_login;
		$current_user->user_login    = $username ? $username : 'udb_username_' . wp_rand( 3, 5 );

	}

	/**
	 * Restore the username of current user.
	 */
	public function restore_current_username() {

		global $current_user;

		if ( ! property_exists( $current_user, 'wp_user_login' ) || ! $current_user->wp_user_login ) {
			return;
		}

		$current_user->user_login = $current_user->wp_user_login;

		unset( $current_user->wp_user_login );

	}

}
