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
	 * Get admin bar settings.
	 *
	 * @return array The admin bar settings.
	 */
	public function get_admin_bar_settings() {

		$saved_settings  = get_option( 'admin_bar_settings', array() );
		$remove_by_roles = array();

		if ( isset( $saved_settings['remove_by_roles'] ) ) {
			$remove_by_roles = $saved_settings['remove_by_roles'] ? $saved_settings['remove_by_roles'] : array();
		}

		return array(
			'remove_by_roles' => $remove_by_roles,
		);

	}

	/**
	 * Check if the admin bar should be removed for the current user.
	 *
	 * @return bool
	 */
	public function should_remove_admin_bar() {
		// Get the admin bar settings
		$admin_bar_settings = $this->get_admin_bar_settings();

		// Get the current user's roles
		$user       = wp_get_current_user();
		$user_roles = (array) $user->roles;

		// Check if there are roles set to remove the admin bar
		if ( isset( $admin_bar_settings['remove_by_roles'] ) ) {
			$roles_to_remove = $admin_bar_settings['remove_by_roles'];

			// Remove admin bar for all users if 'all' is set
			if ( in_array( 'all', $roles_to_remove ) ) {
				return true;
			}

			// Otherwise, check if the current user has a role that should remove the admin bar
			foreach ( $user_roles as $role ) {
				if ( in_array( $role, $roles_to_remove ) ) {
					return true;
				}
			}
		}

		// Return false if no conditions matched
		return false;
	}

}
