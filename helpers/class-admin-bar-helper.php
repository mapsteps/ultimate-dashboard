<?php
/**
 * Admin bar helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup admin bar helper.
 */
class Admin_Bar_Helper {

	/**
	 * Get the roles to remove the admin bar for.
	 *
	 * @return string[] The roles to remove the admin bar for.
	 */
	public function roles_to_remove() {

		$settings = get_option( 'udb_settings', array() );
		$roles    = ! empty( $settings['remove_admin_bar'] ) ? $settings['remove_admin_bar'] : [];
		$roles    = ! is_array( $roles ) ? [ 'all' ] : $roles;

		return $roles;

	}

	/**
	 * Check if the admin bar should be removed for the user specified by role.
	 *
	 * @param string[] $roles The roles to check. If empty, the current user's roles will be used.
	 *
	 * @return bool
	 */
	public function should_remove_admin_bar( $roles = [] ) {

		if ( empty( $roles ) ) {
			$current_user  = wp_get_current_user();
			$current_roles = $current_user->roles;

			$roles = ! is_array( $current_roles ) ? [] : $current_roles;
		}

		if ( empty( $roles ) ) {
			return false;
		}

		$roles_to_remove = $this->roles_to_remove();

		if ( ! empty( $roles_to_remove ) ) {
			// Remove admin bar for all users if 'all' is set.
			if ( in_array( 'all', $roles_to_remove, true ) ) {
				return true;
			}

			// Otherwise, check if `$roles` var contains a role that should remove the admin bar.
			foreach ( $roles as $role ) {
				if ( in_array( $role, $roles_to_remove, true ) ) {
					return true;
				}
			}
		}

		return false;
	}

}
