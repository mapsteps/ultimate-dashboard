<?php
/**
 * Setup admin menu output.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Preparing the admin menu output.
 */
function udb_admin_menu_output() {
	global $menu, $submenu;

	$roles = wp_get_current_user()->roles;

	if ( empty( $roles ) ) {
		$user  = new WP_User( get_current_user_id(), '', get_main_site_id() );
		$roles = $user->roles;
	}

	if ( empty( $roles ) ) {
		return;
	}

	$role = $roles[0];

	$saved_menu = get_option( 'udb_admin_menu', array() );
	$role_menu  = isset( $saved_menu[ $role ] ) ? $saved_menu[ $role ] : array();

	if ( ! $role_menu ) {
		return;
	}

	$new_menu       = array();
	$hidden_menu    = array();
	$new_submenu    = array();
	$hidden_submenu = array();

	foreach ( $role_menu as $menu_index => $menu_item ) {
		$new_menu_item       = array();
			$menu_search_key = 'separator' === $menu_item['type'] ? 'url' : 'id';

		if ( 'separator' === $menu_item['type'] ) {
			$menu_finder_index = 2; // The separator url.
		} else {
			$menu_finder_index = 5; // The menu id attribute.
		}

		$default_menu_index   = udb_find_assoc_array_index_by_value( $menu, $menu_finder_index, $menu_item[ $menu_search_key . '_default' ] );
		$matched_default_menu = false !== $default_menu_index ? $menu[ $default_menu_index ] : false;

		if ( ! $menu_item['is_hidden'] ) {
			$menu_title = $menu_item['title'] ? $menu_item['title'] : ( isset( $matched_default_menu[0] ) ? $matched_default_menu[0] : '' );
			$menu_url   = $menu_item['url'] ? $menu_item['url'] : ( isset( $matched_default_menu[2] ) ? $matched_default_menu[2] : '' );
			$menu_class = $menu_item['class'] ? $menu_item['class'] : ( isset( $matched_default_menu[4] ) ? $matched_default_menu[4] : '' );

			array_push( $new_menu_item, $menu_title );
			array_push( $new_menu_item, ( isset( $matched_default_menu[1] ) ? $matched_default_menu[1] : '' ) );
			array_push( $new_menu_item, $menu_url );
			array_push( $new_menu_item, ( isset( $matched_default_menu[3] ) ? $matched_default_menu[3] : '' ) );
			array_push( $new_menu_item, $menu_class );

			if ( 'menu' === $menu_item['type'] ) {
				$menu_id   = $menu_item['id'] ? $menu_item['id'] : ( isset( $matched_default_menu[5] ) ? $matched_default_menu[5] : '' );
				$menu_icon = isset( $matched_default_menu[6] ) ? $matched_default_menu[6] : '';

				if ( $menu_item['icon_type'] && $menu_item[ $menu_item['icon_type'] ] ) {
					$menu_icon = $menu_item[ $menu_item['icon_type'] ];
				}

				array_push( $new_menu_item, $menu_id );
				array_push( $new_menu_item, $menu_icon );
			}

			$default_submenu = isset( $submenu[ $menu_url ] ) ? $submenu[ $menu_url ] : array();

			if ( isset( $menu_item['submenu'] ) && ! empty( $menu_item['submenu'] ) ) {
				$custom_submenu       = array();
				$hidden_submenu_items = array();

				foreach ( $menu_item['submenu'] as $submenu_index => $submenu_item ) {
					$submenu_finder_index = 2; // The submenu url.

					$default_submenu_index   = udb_find_assoc_array_index_by_value( $default_submenu, $submenu_finder_index, $submenu_item['url_default'] );
					$matched_default_submenu = false !== $default_submenu_index ? $default_submenu[ $default_submenu_index ] : false;

					if ( ! $submenu_item['is_hidden'] ) {
						$new_submenu_item = array();

						$submenu_title = $submenu_item['title'] ? $submenu_item['title'] : ( isset( $matched_default_submenu[0] ) ? $matched_default_submenu[0] : '' );
						array_push( $new_submenu_item, $submenu_title );

						$submenu_cap = isset( $matched_default_submenu[1] ) ? isset( $matched_default_submenu[1] ) : '';
						array_push( $new_submenu_item, $submenu_cap );

						$submenu_url = $submenu_item['url'] ? $submenu_item['url'] : ( isset( $matched_default_submenu[2] ) ? $matched_default_submenu[2] : '' );
						array_push( $new_submenu_item, $submenu_url );

						$submenu_page_title = isset( $matched_default_submenu[3] ) ? $matched_default_submenu[3] : '';
						array_push( $new_submenu_item, $submenu_page_title );

						$submenu_class = isset( $matched_default_submenu[4] ) ? $matched_default_submenu[4] : '';

						if ( $submenu_class ) {
							array_push( $new_submenu_item, $matched_default_submenu[4] );
						}

						if ( ! $submenu_item['was_added'] ) {
							if ( $matched_default_submenu ) {
								array_push( $custom_submenu, $new_submenu_item );
							}
						} else {
							array_push( $custom_submenu, $new_submenu_item );
						}
					} else {
						if ( $matched_default_submenu ) {
							array_push( $hidden_submenu_items, $matched_default_submenu );
						}
					}
				} // End of foreach $menu_item['submenu'].

				$new_submenu[ $menu_url ] = $custom_submenu;

				if ( ! empty( $hidden_submenu_items ) ) {
					$hidden_submenu[ $menu_url ] = $hidden_submenu_items;
				}
			} // End of checking $menu_item['submenu'].

			array_push( $new_menu, $new_menu_item );
		} else {
			if ( $matched_default_menu ) {
				array_push( $hidden_menu, $matched_default_menu );
			}
		}
	} // End of foreach $role_menu.

	$new_menu    = udb_admin_menu_get_new_menu_items( $role, $menu, $new_menu, $hidden_menu );
	$new_submenu = udb_admin_menu_get_new_submenu_items( $role, $submenu, $new_submenu, $hidden_submenu );

	$menu    = $new_menu;
	$submenu = $new_submenu;

}
add_action( 'admin_menu', 'udb_admin_menu_output', 30 );

/**
 * Get new items from menu
 *
 * @param string $role The specified role.
 * @param array  $menu The old menu.
 * @param array  $custom_menu The custom menu.
 * @param array  $hidden_menu The hidden menu.
 *
 * @return array The modified custom menu.
 */
function udb_admin_menu_get_new_menu_items( $role, $menu, $custom_menu, $hidden_menu ) {
	ksort( $menu );

	$prev_custom_index = 0;

	foreach ( $menu as $menu_index => $menu_item ) {
		$menu_type = empty( $menu_item[0] ) && empty( $menu_item[3] ) ? 'separator' : 'menu';

		if ( 'separator' === $menu_type ) {
			$menu_finder_index = 2; // The separator url.
		} else {
			$menu_finder_index = 5; // The menu id attribute.
		}

		$custom_menu_index   = udb_find_assoc_array_index_by_value( $custom_menu, $menu_finder_index, $menu_item[ $menu_finder_index ] );
		$matched_custom_menu = false !== $custom_menu_index ? $custom_menu[ $custom_menu_index ] : false;

		$current_custom_index = false !== $custom_menu_index ? $custom_menu_index : $prev_custom_index + 1;
		$prev_custom_index    = $current_custom_index;

		if ( false === $matched_custom_menu ) {
			$hidden_menu_index = udb_find_assoc_array_index_by_value( $hidden_menu, $menu_finder_index, $menu_item[ $menu_finder_index ] );

			if ( false === $hidden_menu_index ) {
				array_splice( $custom_menu, $current_custom_index, 0, array( $menu_item ) );
			}
		}
	}

	return $custom_menu;
}

/**
 * Get new items from submenu
 *
 * @param string $role The specified role.
 * @param array  $submenu The old submenu.
 * @param array  $custom_submenu The custom submenu.
 * @param array  $hidden_submenu The hidden submenu.
 *
 * @return array The modified custom submenu.
 */
function udb_admin_menu_get_new_submenu_items( $role, $submenu, $custom_submenu, $hidden_submenu ) {
	foreach ( $submenu as $submenu_key => $submenu_item ) {
		$matched_custom_submenu = isset( $custom_submenu[ $submenu_key ] ) ? $custom_submenu[ $submenu_key ] : false;

		if ( ! $matched_custom_submenu ) {
			$custom_submenu[ $submenu_key ] = $submenu_item;
		} else {
			ksort( $submenu_item );

			$prev_custom_index = 0;

			foreach ( $submenu_item as $submenu_order => $submenu_values ) {
				$submenu_finder_index = 2; // The submenu url.

				$custom_submenu_index = udb_find_assoc_array_index_by_value( $matched_custom_submenu, $submenu_finder_index, $submenu_values[ $submenu_finder_index ] );
				$current_custom_index = false !== $custom_submenu_index ? $custom_submenu_index : $prev_custom_index + 1;
				$prev_custom_index    = $current_custom_index;

				if ( false === $custom_submenu_index ) {
					$is_hidden = false;

					if ( isset( $hidden_submenu[ $submenu_key ] ) ) {
						$hidden_submenu_index = udb_find_assoc_array_index_by_value( $hidden_submenu[ $submenu_key ], $submenu_finder_index, $submenu_values[ $submenu_finder_index ] );

						if ( false !== $hidden_submenu_index ) {
							$is_hidden = true;
						}
					}

					if ( ! $is_hidden ) {
						array_splice( $custom_submenu[ $submenu_key ], $current_custom_index, 0, array( $submenu_values ) );
					}
				}
			}
		}
	}

	return $custom_submenu;
}
