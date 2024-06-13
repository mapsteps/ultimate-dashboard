<?php
/**
 * Get menu & submenu.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminMenu\Ajax;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper;
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
	 * The saved recent menu.
	 *
	 * @var array
	 */
	public $recent_menus = array();

	/**
	 * Get menu & submenu.
	 */
	public function ajax() {

		$nonce         = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		$this->role    = isset( $_POST['role'] ) ? sanitize_text_field( wp_unslash( $_POST['role'] ) ) : '';
		$this->by      = isset( $_POST['by'] ) ? sanitize_text_field( wp_unslash( $_POST['by'] ) ) : '';
		$this->user_id = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;

		if ( ! wp_verify_nonce( $nonce, 'udb_admin_menu_get_menu' ) ) {
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

		/**
		 * This hook is used in the admin menu output in the pro version (multisite & non-multisite).
		 * It's used to remove the output so that the builder is able to get the original output.
		 *
		 * @see wp-content/plugins/ultimate-dashboard-pro/modules/admin-menu/class-admin-menu-output.php
		 * @see wp-content/plugins/ultimate-dashboard-pro/modules/multisite/output/class-ms-admin-menu-output.php
		 */
		do_action( 'udb_ajax_before_get_admin_menu' );

		/**
		 * This hook is used in the free version and in the pro version (the multisite module).
		 * It's used to provide menu and submenu for the builder.
		 *
		 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/class-admin-menu-module.php
		 * @see wp-content/plugins/ultimate-dashboard-pro/modules/multisite/output/class-ms-admin-menu-output.php
		 */
		do_action( 'udb_ajax_get_admin_menu', $this, $this->role );

	}

	/**
	 * Manually load menu & submenu.
	 *
	 * This method is called by `get_admin_menu` method inside modules/admin-menu/admin-menu-module.php file.
	 * The `get_admin_menu` in that file it self is hooked into `udb_ajax_get_admin_menu`action
	 * which will be called by `do_action` in this file (inside `ajax` method above).
	 *
	 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/class-admin-menu-module.php
	 *
	 * Also, when this function is called, `wp_doing_ajax` was set to false
	 * in order to get the reliable $menu & $submenu.
	 *
	 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/inc/not-doing-ajax.php
	 */
	public function load_menu() {

		global $menu, $submenu;

		$wp_menu_file = ABSPATH . 'wp-admin/menu.php';

		if ( ( is_null( $menu ) || is_null( $submenu ) ) && file_exists( $wp_menu_file ) ) {
			global $menu_order, $default_menu_order, $_wp_last_object_menu, $_wp_submenu_nopriv;

			$menu_order         = array();
			$default_menu_order = array();

			require $wp_menu_file;
		}

		/**
		 * Set the wp_doing_ajax back to true.
		 *
		 * The value of `wp_doing_ajax` was set to `false` in ultimate-dashboard/ultimate-dashboard.php file
		 * by calling `udb_admin_menu_not_doing_ajax` function.
		 *
		 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/inc/not-doing-ajax.php
		 */
		add_filter( 'wp_doing_ajax', '__return_true' );

		$this->recent_menus = get_option( 'udb_recent_admin_menu', array() );

		$submenu = $this->fix_submenu_customizer_link( $submenu );

		/**
		 * The call of these 2 functions are necessary
		 * because some plugins are conditionally register their admin menu
		 * and they check for DOING_AJAX constant directly instead of
		 * checking for wp_doing_ajax() function.
		 */
		$this->compare_default_menu_with_recent_menu();
		$this->compare_default_submenu_with_recent_menu();

		// This needs to be called here (after the 2 compares above).
		$submenu = $this->fix_woo_products_submenu( $submenu );

		$this->check_menu_items_capability();

	}

	/**
	 * The process of getting the global $menu & $submenu for the editor/builder happens via ajax request.
	 * It makes the URL of `Customize` submenu (under Appearance menu) become like this:
	 * customize.php?return=%2Fwp-admin%2Fadmin-ajax.php
	 *
	 * That return path should be changed to the current admin menu editor (the builder) path.
	 * If we don't convert it properly, it will cause double "Customize" submenu item.
	 *
	 * @param array $submenu The submenu array.
	 * @return array The modified submenu array.
	 */
	public function fix_submenu_customizer_link( $submenu ) {

		if ( ! isset( $submenu['themes.php'] ) ) {
			return $submenu;
		}

		foreach ( $submenu['themes.php'] as $submenu_index => $submenu_item ) {
			if ( 'customize.php?return=%2Fwp-admin%2Fadmin-ajax.php' === $submenu_item[2] ) {
				$return_path   = wp_get_referer();
				$return_path   = str_replace( site_url(), '', $return_path );
				$return_path   = rawurlencode( $return_path );
				$customize_url = 'customize.php?return=' . $return_path;

				$submenu['themes.php'][ $submenu_index ][2] = $customize_url;

				break;
			}
		}

		return $submenu;
	}

	/**
	 * Fix WooCommerce products submenu for the builder.
	 *
	 * In admin menu editor (in the builder), we have some submenu items that shouldn't be there.
	 * They are under WooCommerce's "Products" menu:
	 * - Product Import
	 * - Product Export
	 *
	 * WooCommerce registers those submenu items in `admin_menu` hook and then remove it in `admin_head` hook.
	 * They only needs the actual page and don't want the submenu items.
	 *
	 * The `udb_recent_admin_menu` is done in the `admin_menu` hook before UDB's admin menu output is implemented.
	 * So we can't use `admin_head` to save the recent menu.
	 * That's why this specific support for WooCommerce is here.
	 *
	 * @param array $submenu The submenu array.
	 * @return array The modified submenu array.
	 */
	public function fix_woo_products_submenu( $submenu ) {

		if ( ! isset( $submenu['edit.php?post_type=product'] ) ) {
			return $submenu;
		}

		foreach ( $submenu['edit.php?post_type=product'] as $submenu_index => $submenu_item ) {
			if ( 'product_importer' === $submenu_item[2] || 'product_exporter' === $submenu_item[2] ) {
				unset( $submenu['edit.php?post_type=product'][ $submenu_index ] );
			}
		}

		return $submenu;

	}

	/**
	 * Format the response by merging default menu & saved menu.
	 *
	 * This method is called by `get_admin_menu` method inside modules/admin-menu/admin-menu-module.php file.
	 * The `get_admin_menu` in that file it self is hooked into `udb_ajax_get_admin_menu`action
	 * which will be called by `do_action` in this file (inside `ajax` method above).
	 *
	 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/class-admin-menu-module.php
	 *
	 * @param string $role The specified role.
	 * @return array $response The formatted response.
	 */
	public function format_response( $role ) {

		global $menu, $submenu;

		$merged_default_menu = $this->merge_default_menu_submenu( $menu, $submenu );
		$merged_default_menu = $this->format_merged_default_menu( $merged_default_menu );

		$saved_menu = get_option( 'udb_admin_menu', array() );
		$saved_menu = ! empty( $saved_menu ) && is_array( $saved_menu ) ? $saved_menu : array();

		if ( 'user_id' === $this->by ) {
			$custom_menu = ! empty( $saved_menu ) && ! empty( $saved_menu[ 'user_id_' . $this->user_id ] ) ? $saved_menu[ 'user_id_' . $this->user_id ] : array();
		} else {
			$custom_menu = ! empty( $saved_menu ) && ! empty( $saved_menu[ $role ] ) ? $saved_menu[ $role ] : array();
		}

		$custom_menu = is_array( $custom_menu ) ? $custom_menu : [];

		if ( empty( $custom_menu ) ) {
			$response = $this->parse_response_without_custom_menu( $merged_default_menu );
		} else {
			$custom_menu = $this->get_new_default_menu_items( $merged_default_menu, $custom_menu );
			$response    = $this->parse_response_with_custom_menu( $merged_default_menu, $custom_menu );
		}

		return $response;

	}

	/**
	 * Check menu items capability.
	 *
	 * This function will be called in this file.
	 * But it's only intended to provide support for 3rd parties with specific condition.
	 * If the `udb_admin_menu_show_ui_capabilities` filter returning empty, this function will stop earlier.
	 * So that we don't waste more resource :).
	 *
	 * Sometimes the menu item's capability is accessible by $role but the `show_ui` argument is set to false.
	 * This makes the menu being rendered in the builder but not being displayed in side menu, which is fine.
	 * The problem was, after the admin menu is saved, that item will be displayed in side menu, but broken.
	 * It was like the issue with LifterLMS menu (Engagement & Order).
	 *
	 * I thought, we need to check the post type's `show_ui` argument.
	 * If the `show_ui` argument is false, then unset it from the global $menu.
	 * So those menu items won't be rendered in the builder.
	 *
	 * But turned out I was wrong.
	 * The `register_post_type` already run when the ajax handler run.
	 * That means, the `show_ui` argument has been decided before we simulate the role.
	 * Which means, checking the `show_ui` argument of the post type doesn't solve the problem.
	 *
	 * The solution for now is, by checking the capability manually.
	 * Defining what capability is needed per-plugin (which is not efficient, but it works).
	 * We provide a filter for this, so that other plugin's author / team
	 * can also add support for UDB admin menu editor.
	 *
	 * How if we haven't defined a capability check for a specific plugin (which has the issue)?
	 * Then it might be displayed in the builder, but don't worry.
	 * Because we already place a fix somewhere
	 * so it wouldn't be displayed in the wp-admin's side menu if it's intended to be not displayed.
	 */
	public function check_menu_items_capability() {
		global $menu;

		// The `show_ui` capability by specific post types.
		$show_ui_capabilities = array();

		/**
		 * Let's provide filter for this.
		 * So other plugin's author / team can add support for UDB admin menu editor.
		 */
		$show_ui_capabilities = apply_filters( 'udb_admin_menu_show_ui_capabilities', $show_ui_capabilities );

		// If the filter is empty (which is default), then stop here.
		if ( empty( $show_ui_capabilities ) ) {
			return;
		}

		foreach ( $menu as $menu_order => $menu_item ) {
			if ( false !== stripos( $menu_item[2], 'edit.php?post_type=' ) ) {
				$explode_full = explode( 'edit.php?post_type=', $menu_item[2] );

				if ( isset( $explode_full[1] ) ) {
					$explode_partial = explode( '&', $explode_full[1] );

					if ( count( $explode_partial ) === 1 ) {
						$post_type_slug = $explode_partial[0];

						foreach ( $show_ui_capabilities as $post_type => $capability ) {
							if ( $post_type_slug === $post_type ) {
								if ( ! current_user_can( $capability ) ) {
									unset( $menu[ $menu_order ] );
								}
							}
						} // End of foreach $show_ui_capabilities.
					} // End of count( $explode_partial ) === 1.
				} // End of isset( $explode_full[1] ).
			} // End of stripos.
		} // End of $menu foreach.
	}

	/**
	 * Compare default global $menu with saved udb_recent_menu.
	 * If some menu exists in recent menu but not in default menu,
	 * then add it to the default menu.
	 *
	 * The `udb_recent_menu` was set in `save_recent_menu` method
	 * in the class-admin-menu-module.php in the free version.
	 *
	 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/class-admin-menu-module.php
	 */
	public function compare_default_menu_with_recent_menu() {

		global $menu;

		$recent_menus = $this->recent_menus;
		$role         = $this->role;

		if ( empty( $recent_menus ) || ! isset( $recent_menus[ $role ] ) || empty( $recent_menus[ $role ] ) ) {
			return;
		}

		if ( ! isset( $recent_menus[ $role ]['menu'] ) || empty( $recent_menus[ $role ]['menu'] ) ) {
			return;
		}

		$recent_menu = $recent_menus[ $role ]['menu'];

		$array_helper = new Array_Helper();

		foreach ( $recent_menu as $menu_index => $menu_item ) {
			$menu_type = empty( $menu_item[0] ) && empty( $menu_item[3] ) ? 'separator' : 'menu';

			$menu_search_key = 'separator' === $menu_type ? 2 : 5; // 2 = slug, 5 = index.
			$value_to_search = $menu_item[ $menu_search_key ];

			$matched_menu_index = $array_helper->find_assoc_array_index_by_value( $menu, $menu_search_key, $value_to_search );
			$matched_menu_item  = false !== $matched_menu_index ? $menu[ $matched_menu_index ] : null;

			if ( ! $matched_menu_item ) {
				$new_menu_index = $this->generate_unique_index( $menu, $menu_index );

				$menu[ $new_menu_index ] = $menu_item;

			}
		}

		ksort( $menu, SORT_NUMERIC );

	}

	/**
	 * Compare default global $submenu with saved udb_recent_menu.
	 * If some submenu exists in recent menu but not in default submenu,
	 * then add it to the default submenu.
	 *
	 * The `udb_recent_menu` was set in `save_recent_menu` method
	 * in the class-admin-menu-module.php in the free version.
	 *
	 * @see wp-content/plugins/ultimate-dashboard/modules/admin-menu/class-admin-menu-module.php
	 */
	public function compare_default_submenu_with_recent_menu() {

		global $submenu;

		$recent_menus = $this->recent_menus;
		$role         = $this->role;

		if ( empty( $recent_menus ) || ! isset( $recent_menus[ $role ] ) || empty( $recent_menus[ $role ] ) ) {
			return;
		}

		if ( ! isset( $recent_menus[ $role ]['submenu'] ) || empty( $recent_menus[ $role ]['submenu'] ) ) {
			return;
		}

		$recent_submenu = $recent_menus[ $role ]['submenu'];

		$array_helper = new Array_Helper();

		foreach ( $recent_submenu as $submenu_key => $submenu_group ) {
			if ( ! isset( $submenu[ $submenu_key ] ) ) {
				$submenu[ $submenu_key ] = $submenu_group;
			} else {
				$submenu_search_key = 2; // 2 = slug.

				foreach ( $submenu_group as $submenu_index => $submenu_item ) {
					$value_to_search = $submenu_item[ $submenu_search_key ];

					$matched_submenu_index = $array_helper->find_assoc_array_index_by_value( $submenu[ $submenu_key ], $submenu_search_key, $value_to_search );
					$matched_submenu_item  = false !== $matched_submenu_index ? $submenu[ $submenu_key ][ $matched_submenu_index ] : null;

					if ( ! $matched_submenu_item ) {
						$new_submenu_index = $this->generate_unique_index( $submenu[ $submenu_key ], $submenu_index );

						$submenu[ $submenu_key ][ $new_submenu_index ] = $submenu_item;

					}
				}

				ksort( $submenu[ $submenu_key ], SORT_NUMERIC );
			}
		}

	}

	/**
	 * Generate unique index from provided $array based on provided $index.
	 *
	 * @param array $arr The provided array.
	 * @param int   $index The provided index.
	 *
	 * @return int
	 */
	public function generate_unique_index( $arr, $index ) {

		if ( isset( $arr[ $index ] ) ) {
			if ( is_string( $index ) ) {
				$index = (float) $index;
			}

			$index += 0.05;
			$index  = (string) $index;

			if ( isset( $arr[ $index ] ) ) {
				$index = $this->generate_unique_index( $arr, $index );
			}
		}

		return $index;

	}

	/**
	 * Merge default menu & submenu into 1 array.
	 *
	 * @param array $menu The default menu.
	 * @param array $submenu The default submenu.
	 *
	 * @return array The merged menu.
	 */
	public function merge_default_menu_submenu( $menu, $submenu ) {

		$merged_menu = array();

		foreach ( $menu as $index => $menu_item ) {
			$cap_allowed = true;

			if ( ! empty( $menu_item[1] ) ) {
				$cap_allowed = current_user_can( $menu_item[1] );
			}

			if ( $cap_allowed ) {
				if ( isset( $submenu[ $menu_item[2] ] ) ) {
					$menu_item['submenu'] = $submenu[ $menu_item[2] ];
				}

				$merged_menu[ $index ] = $menu_item;
			}
		}

		return $merged_menu;

	}

	/**
	 * Format the default menu to our expected format.
	 *
	 * @param array $menu The default menu in a merged format (default menu & submenu merged into 1 array).
	 * @return array $formatted_menus The formatted menu.
	 */
	public function format_merged_default_menu( $menu ) {

		$formatted_menus = array();

		foreach ( $menu as $index => $menu_item ) {
			$menu_type = empty( $menu_item[0] ) && empty( $menu_item[3] ) ? 'separator' : 'menu';

			$formatted_menu = array();

			$content_helper = new Content_Helper();

			$formatted_menu['title'] = $content_helper->strip_tags_content( $menu_item[0] );
			$formatted_menu['url']   = $menu_item[2];
			$formatted_menu['class'] = $menu_item[4];
			$formatted_menu['type']  = $menu_type;

			$formatted_menu['dashicon'] = '';
			$formatted_menu['icon_svg'] = '';

			if ( 'menu' === $menu_type ) {
				$formatted_menu['id'] = $menu_item[5];

				if ( false !== stripos( $menu_item[6], 'dashicons-' ) ) {
					$formatted_menu['icon_type'] = 'dashicon';
					$formatted_menu['dashicon']  = $menu_item[6];
				} else {
					$formatted_menu['icon_type'] = 'icon_svg';
					$formatted_menu['icon_svg']  = $menu_item[6];
				}
			}

			if ( 'menu' === $menu_type && ! empty( $menu_item['submenu'] ) ) {
				$formatted_submenus = array();

				foreach ( $menu_item['submenu'] as $submenu_index => $submenu_item ) {
					$formatted_submenu = array();

					$content_helper = new Content_Helper();

					$formatted_submenu['title'] = $content_helper->strip_tags_content( $submenu_item[0] );
					$formatted_submenu['url']   = $submenu_item[2];

					array_push( $formatted_submenus, $formatted_submenu );
				}

				$formatted_menu['submenu'] = $formatted_submenus;
			}

			array_push( $formatted_menus, $formatted_menu );
		}

		return $formatted_menus;

	}

	/**
	 * Parse response without custom menu (when custom menu is empty).
	 *
	 * @param array $default_menu The well formatted default menu.
	 * @return array The parsed response.
	 */
	public function parse_response_without_custom_menu( $default_menu ) {

		$response = array();

		foreach ( $default_menu as $menu_index => $menu_item ) {
			$new_menu_item = $this->build_custom_menu_item( $menu_item, true );

			array_push( $response, $new_menu_item );
		}

		return $response;

	}

	/**
	 * Build custom menu item from default menu item.
	 *
	 * @param array $default_menu_item The default menu item.
	 * @param bool  $clone_item Whether to totally clone the item.
	 *
	 * @return array The custom menu item.
	 */
	public function build_custom_menu_item( $default_menu_item, $clone_item = false ) {

		$custom_menu_item = array();

		$custom_menu_item['is_hidden'] = 0;
		$custom_menu_item['was_added'] = 0;

		foreach ( $default_menu_item as $menu_item_key => $menu_item_value ) {
			if ( 'submenu' !== $menu_item_key ) {
				$default_menu_item_key = $menu_item_key . '_default';

				if ( $clone_item ) {
					if ( 'type' !== $menu_item_key ) {
						$custom_menu_item[ $default_menu_item_key ] = $menu_item_value;
						$custom_menu_item[ $menu_item_key ]         = '';
					} else {
						$custom_menu_item[ $menu_item_key ] = $menu_item_value;
					}
				} elseif ( 'url' === $menu_item_key || 'id' === $menu_item_key ) {
					$custom_menu_item[ $default_menu_item_key ] = $menu_item_value;
					$custom_menu_item[ $menu_item_key ]         = '';
				} elseif ( 'type' === $menu_item_key ) {
					$custom_menu_item[ $menu_item_key ] = $menu_item_value;
				} else {
					$custom_menu_item[ $menu_item_key ] = '';
				}
			} else {
				$new_submenu = array();

				foreach ( $menu_item_value as $submenu_index => $submenu_item ) {
					if ( ! is_array( $submenu_item ) || empty( $submenu_item ) ) {
						continue;
					}

					$new_submenu_item = array();

					$new_submenu_item['is_hidden'] = 0;
					$new_submenu_item['was_added'] = 0;

					foreach ( $submenu_item as $submenu_item_key => $submenu_item_value ) {
						$default_submenu_item_key = $submenu_item_key . '_default';

						if ( $clone_item ) {
							if ( 'type' !== $menu_item_key ) {
								$new_submenu_item[ $default_submenu_item_key ] = $submenu_item_value;
								$new_submenu_item[ $submenu_item_key ]         = '';
							} else {
								$new_submenu_item[ $submenu_item_key ] = $submenu_item_value;
							}
						} elseif ( 'url' === $submenu_item_key ) {
							$new_submenu_item[ $default_submenu_item_key ] = $submenu_item_value;
							$new_submenu_item[ $submenu_item_key ]         = '';
						} elseif ( 'type' === $submenu_item_key ) {
							$new_submenu_item[ $submenu_item_key ] = $submenu_item_value;
						} else {
							$new_submenu_item[ $submenu_item_key ] = '';
						}
					}

					array_push( $new_submenu, $new_submenu_item );
				}

				$custom_menu_item['submenu'] = $new_submenu;
			}
		}

		return $custom_menu_item;

	}

	/**
	 * Build custom submenu item from default submenu item.
	 *
	 * @param array $default_submenu_item The default submenu item.
	 * @return array The custom submenu item.
	 */
	public function build_custom_submenu_item( $default_submenu_item ) {

		$custom_submenu_item = array();

		$custom_submenu_item['is_hidden'] = 0;
		$custom_submenu_item['was_added'] = 0;

		foreach ( $default_submenu_item as $submenu_item_key => $submenu_item_value ) {
			$default_submenu_item_key = $submenu_item_key . '_default';

			if ( 'url' === $submenu_item_key ) {
				$custom_submenu_item[ $default_submenu_item_key ] = $submenu_item_value;
				$custom_submenu_item[ $submenu_item_key ]         = '';
			} elseif ( 'type' === $submenu_item_key ) {
					$custom_submenu_item[ $submenu_item_key ] = $submenu_item_value;
			} else {
				$custom_submenu_item[ $submenu_item_key ] = '';
			}
		}

		return $custom_submenu_item;

	}

	/**
	 * Parse response with custom menu.
	 *
	 * @param array $formatted_default_menu The well formatted default menu (with their submenu) array.
	 * @param array $custom_menu The custom (role/user based) menu from database.
	 *
	 * @return array The parsed response.
	 */
	public function parse_response_with_custom_menu( $formatted_default_menu, $custom_menu ) {

		$response     = array();
		$array_helper = new Array_Helper();

		foreach ( $custom_menu as $custom_menu_index => $custom_menu_item ) {
			if ( ! is_array( $custom_menu_item ) || empty( $custom_menu_item ) ) {
				continue;
			}

			$parsed_menu_item = array();

			$menu_search_key = 'separator' === $custom_menu_item['type'] ? 'url' : 'id';

			$matched_formatted_default_menu_index = $array_helper->find_assoc_array_index_by_value( $formatted_default_menu, $menu_search_key, $custom_menu_item[ $menu_search_key . '_default' ] );

			$matched_formatted_default_menu_item = false !== $matched_formatted_default_menu_index ? $formatted_default_menu[ $matched_formatted_default_menu_index ] : false;
			$matched_formatted_default_menu_item = is_array( $matched_formatted_default_menu_item ) ? $matched_formatted_default_menu_item : false;

			foreach ( $custom_menu_item as $custom_menu_item_key => $custom_menu_item_value ) {
				if ( 'submenu' !== $custom_menu_item_key ) {
					$default_menu_item_key = $custom_menu_item_key . '_default';

					if ( 'type' !== $custom_menu_item_key && 'is_hidden' !== $custom_menu_item_key && 'was_added' !== $custom_menu_item_key && 'id_default' !== $custom_menu_item_key && 'url_default' !== $custom_menu_item_key ) {
						if ( isset( $matched_formatted_default_menu_item[ $custom_menu_item_key ] ) ) {
							$parsed_menu_item[ $default_menu_item_key ] = $matched_formatted_default_menu_item[ $custom_menu_item_key ];
							$parsed_menu_item[ $custom_menu_item_key ]  = $custom_menu_item_value;
						} elseif ( 1 === absint( $custom_menu_item['was_added'] ) ) {
							if ( isset( $custom_menu_item[ $default_menu_item_key ] ) ) {
								$parsed_menu_item[ $default_menu_item_key ] = $custom_menu_item[ $default_menu_item_key ];
							} else {
								$parsed_menu_item[ $default_menu_item_key ] = $custom_menu_item_value;
							}

							$parsed_menu_item[ $custom_menu_item_key ] = $custom_menu_item_value;
						}
					} else {
						$parsed_menu_item[ $custom_menu_item_key ] = $custom_menu_item_value;
					}
				} else {
					$new_submenu = array();

					$formatted_default_submenu = ! empty( $matched_formatted_default_menu_item['submenu'] ) ? $matched_formatted_default_menu_item['submenu'] : array();
					$formatted_default_submenu = is_array( $formatted_default_submenu ) ? $formatted_default_submenu : [];

					/**
					 * Looping $custom_menu_item_value.
					 * In this block, the $custom_menu_item_value is an array of submenu items.
					 *
					 * @var array $custom_menu_item_value
					 * @var int $custom_submenu_index
					 * @var array $custom_submenu_item
					 */
					foreach ( $custom_menu_item_value as $custom_submenu_index => $custom_submenu_item ) {
						if ( ! isset( $custom_submenu_item['url_default'] ) ) {
							continue;
						}

						$custom_submenu_item['url_default'] = (string) $custom_submenu_item['url_default'];

						$new_submenu_item = array();

						/**
						 * Matched default submenu index.
						 *
						 * @var false|int
						 */
						$default_submenu_index = false;

						if ( ! empty( $formatted_default_submenu ) ) {
							$default_submenu_index = $array_helper->find_assoc_array_index_by_value( $formatted_default_submenu, 'url', $custom_submenu_item['url_default'] );

							if ( false === $default_submenu_index ) {
								// If $default_submenu_index is false and the url_default is using & sign instead of &amp; code.
								if ( false !== stripos( $custom_submenu_item['url_default'], '&' ) && false === stripos( $custom_submenu_item['url_default'], '&amp;' ) ) {
									/**
									 * Submenu item's url_default.
									 *
									 * @var string $submenu_url_default
									 */
									$submenu_url_default = str_ireplace( '&', '&amp;', $custom_submenu_item['url_default'] );

									// Try to look up using &amp; instead of &.
									$default_submenu_index = $array_helper->find_assoc_array_index_by_value( $formatted_default_submenu, 'url', $submenu_url_default );

									/**
									 * If $default_submenu_index is not false (is found),
									 * That means the url value of $default_submenu[$default_submenu_index] is using &amp; code instead of & sign.
									 * In this case, we should also replace $submenu_item['url_default'] to also using &amp; code.
									 */
									if ( false !== $default_submenu_index ) {
										$custom_submenu_item['url_default'] = $submenu_url_default;
									}
								}
							}
						}

						/**
						 * Matched default submenu.
						 *
						 * @var false|array
						 */
						$matched_default_submenu = false !== $default_submenu_index ? $formatted_default_submenu[ $default_submenu_index ] : false;

						/**
						 * If $matched_default_submenu is false, let's try to check in other submenus.
						 * Because we allow moving submenu items across parent menus.
						 */
						if ( false === $matched_default_submenu ) {
							/**
							 * Submenu item's url_default.
							 *
							 * @var string $submenu_url_default
							 */
							$submenu_url_default = $custom_submenu_item['url_default'];

							foreach ( $formatted_default_menu as $formatted_default_menu_loop_index => $looped_formatted_default_menu_item ) {
								if ( ! is_array( $looped_formatted_default_menu_item ) || empty( $looped_formatted_default_menu_item ) ) {
									continue;
								}

								if ( empty( $looped_formatted_default_menu_item['submenu'] ) ) {
									continue;
								}

								$matched_submenu_item_index_under_its_parent = -1;

								foreach ( $looped_formatted_default_menu_item['submenu'] as $looped_formatted_submenu_item_index => $looped_formatted_submenu_item ) {
									if ( ! is_int( $looped_formatted_submenu_item_index ) && ! is_float( $looped_formatted_submenu_item_index ) ) {
										continue;
									}

									if ( ! is_array( $looped_formatted_submenu_item ) || empty( $looped_formatted_submenu_item ) ) {
										continue;
									}

									if ( $looped_formatted_submenu_item['url'] === $submenu_url_default ) {
										$matched_default_submenu = $looped_formatted_submenu_item;

										$matched_submenu_item_index_under_its_parent = $looped_formatted_submenu_item_index;

										break;
									}

									// If condition above doesn't match and the $submenu_url_default is using & sign instead of &amp; code.
									if ( false !== stripos( $submenu_url_default, '&' ) && false === stripos( $submenu_url_default, '&amp;' ) ) {
										/**
										 * Submenu item's url_default.
										 *
										 * @var string $submenu_url_default
										 */
										$submenu_url_default = str_ireplace( '&', '&amp;', $submenu_url_default );

										// Try to look up using &amp; instead of &.
										if ( $looped_formatted_submenu_item['url'] === $submenu_url_default ) {
											$matched_default_submenu = $looped_formatted_submenu_item;

											$matched_submenu_item_index_under_its_parent = $looped_formatted_submenu_item_index;

											/**
											 * If this block is reached, it means $submenu_url_default is using &amp; code instead of & sign.
											 * In this case, we should also replace $submenu_item['url_default'] to also using &amp; code.
											 */
											$custom_submenu_item['url_default'] = $submenu_url_default;

											break;
										}
									}
								}

								/**
								 * If it matches submenu item from other parent menus,
								 * then remove that submenu item from the matched $formatted_default_menu's submenu.
								 */
								if ( $matched_submenu_item_index_under_its_parent > -1 ) {
									unset( $formatted_default_menu[ $formatted_default_menu_loop_index ]['submenu'][ $matched_submenu_item_index_under_its_parent ] );

									break;
								}
							}
						}

						foreach ( $custom_submenu_item as $submenu_item_key => $submenu_item_value ) {
							$default_submenu_item_key = $submenu_item_key . '_default';

							if ( 'type' !== $submenu_item_key && 'is_hidden' !== $submenu_item_key && 'was_added' !== $submenu_item_key && 'url_default' !== $submenu_item_key ) {
								if ( isset( $matched_default_submenu[ $submenu_item_key ] ) ) {
									$new_submenu_item[ $default_submenu_item_key ] = $matched_default_submenu[ $submenu_item_key ];
									$new_submenu_item[ $submenu_item_key ]         = $submenu_item_value;
								} elseif ( 1 === absint( $custom_submenu_item['was_added'] ) ) {
									if ( isset( $custom_submenu_item[ $default_submenu_item_key ] ) ) {
										$new_submenu_item[ $default_submenu_item_key ] = $custom_submenu_item[ $default_submenu_item_key ];
									} else {
										$new_submenu_item[ $default_submenu_item_key ] = $submenu_item_value;
									}

									$new_submenu_item[ $submenu_item_key ] = $submenu_item_value;
								}
							} else {
								$new_submenu_item[ $submenu_item_key ] = $submenu_item_value;
							}
						}

						if ( ! $custom_submenu_item['was_added'] ) {
							if ( $matched_default_submenu ) {
								array_push( $new_submenu, $new_submenu_item );
							}
						} else {
							array_push( $new_submenu, $new_submenu_item );
						} // End of $submenu_item['was_added'] checking.
					} // End of $menu_item_value foreach.

					$parsed_menu_item['submenu'] = $new_submenu;
				}
			} // End of $menu_item foreach.

			if ( ! $custom_menu_item['was_added'] ) {
				if ( $matched_formatted_default_menu_item ) {
					array_push( $response, $parsed_menu_item );
				}
			} else {
				array_push( $response, $parsed_menu_item );
			}
		} // End of $custom_menu foreach.

		return $response;

	}

	/**
	 * Get new items from formatted default menu and add it to our custom menu.
	 *
	 * @see https://stackoverflow.com/questions/3797239/insert-new-item-in-array-on-any-position-in-php
	 *
	 * @param array $formatted_default_menu The well formatted default menu (with their submenu) array.
	 * @param array $custom_menu The custom (role/user based) menu from database.
	 *
	 * @return array The modified custom menu.
	 */
	public function get_new_default_menu_items( $formatted_default_menu, $custom_menu ) {

		$array_helper = new Array_Helper();

		/**
		 * Looping $formatted_default_menu.
		 *
		 * @var array $formatted_default_menu
		 * @var int $formatted_default_menu_index
		 * @var array $formatted_default_menu_item
		 */
		foreach ( $formatted_default_menu as $formatted_default_menu_index => $formatted_default_menu_item ) {
			$menu_search_key = 'separator' === $formatted_default_menu_item['type'] ? 'url' : 'id';

			$matched_custom_menu_index = $array_helper->find_assoc_array_index_by_value( $custom_menu, $menu_search_key . '_default', $formatted_default_menu_item[ $menu_search_key ] );

			/**
			 * Matched custom menu item with default menu item.
			 *
			 * @var false|array $matched_custom_menu_item
			 */
			$matched_custom_menu_item = false !== $matched_custom_menu_index ? $custom_menu[ $matched_custom_menu_index ] : false;

			if ( ! $matched_custom_menu_item ) {
				$custom_menu_item = $this->build_custom_menu_item( $formatted_default_menu_item );

				array_splice( $custom_menu, $formatted_default_menu_index, 0, array( $custom_menu_item ) );
				continue;
			}

			if ( empty( $formatted_default_menu_item['submenu'] ) ) {
				continue;
			}

			$custom_submenu = $matched_custom_menu_item['submenu'];

			/**
			 * Looping $formatted_default_menu_item['submenu'].
			 *
			 * @var array $formatted_default_menu_item['submenu']
			 * @var int $formatted_default_submenu_index
			 * @var array $formatted_default_submenu_item
			 */
			foreach ( $formatted_default_menu_item['submenu'] as $formatted_default_submenu_index => $formatted_default_submenu_item ) {
				$matched_custom_submenu_index = $array_helper->find_assoc_array_index_by_value( $custom_submenu, 'url_default', $formatted_default_submenu_item['url'] );

				if ( false === $matched_custom_submenu_index ) {
					/**
					 * If $matched_custom_submenu_index is false, there's possibility that
					 * the url_default of submenu item we're looking for (inside of the $custom_submenu)
					 * is using &amp; code instead of & sign.
					 *
					 * Please note, some submenu items may have &amp; code instead of & sign.
					 * Such as taxonomy submenu items of a custom post type.
					 *
					 * However, when rendered into the builder,
					 * the &amp; code is converted to & sign automatically (browser behavior maybe?).
					 * That url default is rendered in the url field's placeholder & in the `data-default-url` attribute of the submenu item's `li` tag.
					 *
					 * That means, even though those taxonomy submenu items of a custom post type
					 * are using &amp; code instead of & sign, they will be saved as & sign in the database.
					 *
					 * It will cause a doubled menu items in the array when we try to load it via ajax.
					 * But they will not be doubled in the builder.
					 *
					 * This is because the doubled items will be elimited to 1 item in `parse_response_with_custom_menu` execution.
					 * However, the elimination from doubled items into 1 item in that execution resulting wrong result:
					 * The hidden taxonomy submenu item of custom a post type is not hidden in the builder.
					 * That's why we need this extra handling.
					 */
					if ( false !== stripos( $formatted_default_submenu_item['url'], '&amp;' ) ) {
						$submenu_url_default = str_ireplace( '&amp;', '&', $formatted_default_submenu_item['url'] );

						// Try to look up using & sign instead of &amp; code.
						$matched_custom_submenu_index = $array_helper->find_assoc_array_index_by_value( $custom_submenu, 'url_default', $submenu_url_default );

						/**
						 * If $matched_custom_submenu_index is not false (is found),
						 * That means the url_default of $custom_submenu[ $matched_custom_submenu_index ] is using &amp; code instead of & sign.
						 *
						 * In this case, we should also update our related custom menu to use &amp; code
						 * as related WP default menu is also using it.
						 */
						if ( false !== $matched_custom_submenu_index ) {
							$custom_menu[ $matched_custom_menu_index ]['submenu'][ $matched_custom_submenu_index ]['url_default'] = $formatted_default_submenu_item['url'];
						}
					}
				}

				$matched_custom_submenu_item = false !== $matched_custom_submenu_index ? $custom_submenu[ $matched_custom_submenu_index ] : false;
				$matched_custom_submenu_item = is_array( $matched_custom_submenu_item ) ? $matched_custom_submenu_item : false;

				/**
				 * If $matched_custom_submenu_item is false, let's try to check in other submenus.
				 * Because we allow moving submenu items across parent menus.
				 */
				if ( false === $matched_custom_submenu_item ) {
					$submenu_url_default = $formatted_default_submenu_item['url'];

					foreach ( $custom_menu as $custom_menu_index => $custom_menu_item ) {
						if ( ! is_array( $custom_menu_item ) || empty( $custom_menu_item ) ) {
							continue;
						}

						if ( ! is_array( $custom_menu_item['submenu'] ) || empty( $custom_menu_item['submenu'] ) ) {
							continue;
						}

						/**
						 * Custom submenu.
						 *
						 * @var array $custom_submenu
						 */
						$custom_submenu = $custom_menu_item['submenu'];

						foreach ( $custom_submenu as $looped_custom_submenu_index => $looped_custom_submenu_item ) {
							if ( ! is_array( $looped_custom_submenu_item ) || empty( $looped_custom_submenu_item ) ) {
								continue;
							}

							if ( $looped_custom_submenu_item['url_default'] === $submenu_url_default ) {
								$matched_custom_submenu_item = $looped_custom_submenu_item;

								break;
							}

							// If condition above doesn't match and the $submenu_url_default is using & sign instead of &amp; code.
							if ( false !== stripos( $submenu_url_default, '&amp;' ) ) {
								$submenu_url_default = str_ireplace( '&amp;', '&', $submenu_url_default );

								// Try to look up using & sign instead of &amp; code.
								if ( $looped_custom_submenu_item['url_default'] === $submenu_url_default ) {
									$matched_custom_submenu_item = $looped_custom_submenu_item;

									/**
									 * If this block is reached, it means $submenu_url_default is using &amp; code instead of & sign.
									 *
									 * In this case, we should also update our related custom menu to use &amp; code
									 * as related WP default menu is also using it.
									 */
									$custom_menu[ $custom_menu_index ]['submenu'][ $looped_custom_submenu_index ]['url_default'] = $formatted_default_submenu_item['url'];

									break;
								}
							}
						}
					}
				}

				if ( ! $matched_custom_submenu_item ) {
					$custom_submenu_item = $this->build_custom_submenu_item( $formatted_default_submenu_item );

					array_splice( $custom_menu[ $matched_custom_menu_index ]['submenu'], $formatted_default_submenu_index, 0, array( $custom_submenu_item ) );
				}
			}
		}

		return $custom_menu;

	}

}
