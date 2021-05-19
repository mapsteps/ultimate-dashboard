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
	 * then you can omit the 2nd parameter (keep it `false`).
	 *
	 * @param string $role_key The role key.
	 * @param bool   $change_user_id Whether or not to change the user ID.
	 */
	public function simulate_role( $role_key, $change_user_id = false ) {
		global $current_user;

		$role = get_role( $role_key );

		if ( $change_user_id ) {
			$current_user->ID = PHP_INT_MAX;
		}

		$current_user->allcaps = $role->capabilities;
		$current_user->caps    = $role->capabilities;
		$current_user->roles   = array( $role_key );
	}
}
