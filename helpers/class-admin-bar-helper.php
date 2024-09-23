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

		$remove_by_roles[] = 'all';

		if ( isset( $saved_settings['remove_by_roles'] ) ) {
			$remove_by_roles = $saved_settings['remove_by_roles'] ? $saved_settings['remove_by_roles'] : array();
		}

		return array(
			'remove_by_roles' => $remove_by_roles,
		);

	}

}
