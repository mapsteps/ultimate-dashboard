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
	 * @param string $role_key The role key.
	 */
	public function simulate_role( $role_key ) {
		global $current_user;

		$role = get_role( $role_key );

		$current_user->ID      = PHP_INT_MAX;
		$current_user->allcaps = $role->capabilities;
		$current_user->caps    = $role->capabilities;
		$current_user->roles   = array( $role_key );
	}
}
