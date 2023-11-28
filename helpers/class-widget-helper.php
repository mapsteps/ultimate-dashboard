<?php
/**
 * Widget helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Widget\Widget_Output;

/**
 * Class to setup widget helper.
 */
class Widget_Helper {
	/**
	 * Get all dashboard widgets array.
	 *
	 * Returns all widgets that are registered in a complex array.
	 *
	 * @return array The dashboard widgets.
	 */
	public function get_original() {

		global $wp_meta_boxes;

		if ( ! isset( $wp_meta_boxes['dashboard'] ) || ! is_array( $wp_meta_boxes['dashboard'] ) ) {

			require_once ABSPATH . '/wp-admin/includes/dashboard.php';

			$current_screen = get_current_screen();

			set_current_screen( 'dashboard' );

			remove_action( 'wp_dashboard_setup', array( Widget_Output::get_instance(), 'remove_default_dashboard_widgets' ), 100 );

			do_action( 'udb_before_wp_dashboard_setup' );

			wp_dashboard_setup();

			add_action( 'wp_dashboard_setup', array( Widget_Output::get_instance(), 'remove_default_dashboard_widgets' ), 100 );

			do_action( 'udb_after_wp_dashboard_setup' );

			set_current_screen( $current_screen );

		}

		$widgets = $wp_meta_boxes['dashboard'];

		return $widgets;

	}

	/**
	 * Get actual dashboard widgets.
	 *
	 * Strips down the array above to get the actual dashboard widgets array.
	 *
	 * @return array The dashboard widgets.
	 */
	public function get_all() {

		$widgets      = $this->get_original();
		$flat_widgets = array();

		foreach ( $widgets as $context => $priority ) {

			foreach ( $priority as $data ) {

				foreach ( $data as $id => $widget ) {

					if ( false !== $widget ) {

						$widget['title_stripped'] = wp_strip_all_tags( $widget['title'] );
						$widget['context']        = $context;

					}

					$flat_widgets[ $id ] = $widget;
				}
			}
		}

		$widgets = wp_list_sort( $flat_widgets, array( 'title_stripped' => 'ASC' ), null, true );

		return $widgets;

	}

	/**
	 * Get default widgets.
	 *
	 * From all existing widgets, get the default widgets.
	 *
	 * @return array The default widgets.
	 */
	public function get_default() {

		$widgets = $this->get_all();

		$default_widgets = array(
			'dashboard_primary'         => array(),
			'dashboard_quick_press'     => array(),
			'dashboard_right_now'       => array(),
			'dashboard_activity'        => array(),
			'dashboard_incoming_links'  => array(),
			'dashboard_plugins'         => array(),
			'dashboard_secondary'       => array(),
			'dashboard_recent_drafts'   => array(),
			'dashboard_recent_comments' => array(),
			'dashboard_php_nag'         => array(),
			'dashboard_site_health'     => array(),
		);

		$widgets = array_intersect_key( $widgets, $default_widgets );

		return $widgets;

	}

	/**
	 * Get saved default widgets.
	 *
	 * @return array The saved default widgets.
	 */
	public function get_saved_default() {

		$widgets = $this->get_all();

		if ( get_option( 'udb_settings' ) ) {
			$settings = get_option( 'udb_settings' );
		} else {
			$settings = array();
		}

		$widgets = array_intersect_key( $widgets, $settings );

		return $widgets;

	}

	/**
	 * Get 3rd party widgets.
	 *
	 * From all existing widgets, get the 3rd party widgets.
	 *
	 * @return array The 3rd party widgets.
	 */
	public function get_3rd_party() {

		$widgets = $this->get_all();

		$default_widgets = array(
			'dashboard_primary'         => array(),
			'dashboard_quick_press'     => array(),
			'dashboard_right_now'       => array(),
			'dashboard_activity'        => array(),
			'dashboard_incoming_links'  => array(),
			'dashboard_plugins'         => array(),
			'dashboard_secondary'       => array(),
			'dashboard_recent_drafts'   => array(),
			'dashboard_recent_comments' => array(),
			'dashboard_php_nag'         => array(),
			'dashboard_site_health'     => array(),
		);

		$udb_widgets = array();

		foreach ( $widgets as $key => $value ) {
			if ( 0 === strpos( $key, 'ms-udb' ) ) {
				$udb_widgets[ $key ] = $value;
			}
		}

		$widgets = array_diff_key( $widgets, $udb_widgets, $default_widgets );

		return $widgets;
	}

}
