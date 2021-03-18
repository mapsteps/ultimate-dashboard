<?php
/**
 * Admin Bar module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminBar;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;
use Udb\Helpers\Array_Helper;
use Udb\Base\Base_Module;

/**
 * Class to setup admin menu module.
 */
class Admin_Bar_Module extends Base_Module {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-bar';

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Setup admin menu module.
	 */
	public function setup() {

		add_action( 'admin_menu', array( self::get_instance(), 'submenu_page' ) );
		add_action( 'admin_bar_menu', array( self::get_instance(), 'get_existing_menu' ), 999 );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );

		$this->setup_ajax();

	}

	/**
	 * Setup ajax.
	 */
	public function setup_ajax() {

		require_once __DIR__ . '/ajax/class-get-menu.php';
		require_once __DIR__ . '/ajax/class-get-users.php';
		require_once __DIR__ . '/ajax/class-save-menu.php';

		$get_menu  = new Ajax\Get_Menu();
		$get_users = new Ajax\Get_Users();
		$save_menu = new Ajax\Save_Menu();

		add_action( 'wp_ajax_udb_admin_bar_get_menu', array( $get_menu, 'ajax' ) );
		add_action( 'wp_ajax_udb_admin_bar_get_users', array( $get_users, 'ajax' ) );
		add_action( 'wp_ajax_udb_admin_bar_save_menu', array( $save_menu, 'ajax' ) );

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Admin Bar Editor', 'ultimate-dashboard' ), __( 'Admin Bar Editor', 'ultimate-dashboard' ), apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_admin_bar', array( $this, 'submenu_page_content' ) ); // are we using this filter everywhere?

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		require __DIR__ . '/templates/template.php';

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		$enqueue = require __DIR__ . '/inc/css-enqueue.php';
		$enqueue( $this );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		$enqueue = require __DIR__ . '/inc/js-enqueue.php';
		$enqueue( $this );

	}

	/**
	 * Get existing admin bar menu.
	 *
	 * @param WP_Admin_Bar $admin_bar WP_Admin_Bar instance.
	 */
	public function get_existing_menu( $admin_bar ) {

		Vars::set( 'existing_admin_bar_menu', $admin_bar->get_nodes() );

		// error_log( print_r( $this->to_nested_format( $admin_bar->get_nodes() ), true ) );

	}

	/**
	 * Turn flat admin bar menu array to a nested format (parent -> submenu).
	 *
	 * @param array $flat_array The default format of admin bar menu.
	 * @return array The nested format of admin bar menu.
	 */
	public function to_nested_format( $flat_array ) {
		if ( ! $flat_array ) {
			return array();
		}

		$nested_array = array();

		// First, get the parent menu items.
		foreach ( $flat_array as $node_id => $node ) {
			if ( ! $node->parent || ! isset( $flat_array[ $node->parent ] ) ) {
				$nested_array[ $node_id ] = array(
					'id'                    => $node->id,
					'id_default'            => $node->id,
					'title'                 => '',
					'title_encoded'         => '',
					'title_clean'           => '',
					'title_default'         => $node->title,
					'title_default_encoded' => htmlentities2( $node->title ),
					'title_default_clean'   => wp_strip_all_tags( $node->title ),
					'parent'                => $node->parent,
					'parent_default'        => $node->parent,
					'href'                  => '',
					'href_default'          => $node->href,
					'group'                 => $node->group,
					'group_default'         => $node->group,
					'meta'                  => $node->meta,
					'meta_default'          => $node->meta,
					'was_added'             => 0,
					'disallowed_roles'      => array(),
					'disallowed_users'      => array(),
					'submenu'               => array(),
				);
			}
		}

		// Second, remove collected parent array from flat_array.
		foreach ( $nested_array as $key => $value ) {
			if ( isset( $flat_array[ $key ] ) ) {
				unset( $flat_array[ $key ] );
			}
		}

		// Third, get the submenu items.
		foreach ( $flat_array as $node_id => $node ) {
			if ( isset( $nested_array[ $node->parent ] ) ) {
				$nested_array[ $node->parent ]['submenu'][ $node->id ] = array(
					'id'                    => $node->id,
					'id_default'            => $node->id,
					'title'                 => '',
					'title_encoded'         => '',
					'title_clean'           => '',
					'title_default'         => $node->title,
					'title_default_encoded' => htmlentities2( $node->title ),
					'title_default_clean'   => wp_strip_all_tags( $node->title ),
					'parent'                => $node->parent,
					'parent_default'        => $node->parent,
					'href'                  => '',
					'href_default'          => $node->href,
					'group'                 => $node->group,
					'group_default'         => $node->group,
					'meta'                  => $node->meta,
					'meta_default'          => $node->meta,
					'was_added'             => 0,
					'disallowed_roles'      => array(),
					'disallowed_users'      => array(),
					'submenu'               => array(),
				);

				unset( $flat_array[ $node_id ] );
			}
		}

		// Fourth, get the 2nd depth submenu items.
		if ( ! empty( $flat_array ) ) {
			// Loop the flat_array.
			foreach ( $flat_array as $node_id => $node ) {
				// Loop the nested_array.
				foreach ( $nested_array as $parent_id => $parent_array ) {
					$submenu_lv2_found = false;

					if ( ! empty( $parent_array['submenu'] ) ) {
						// Loop the parent array's submenu.
						foreach ( $parent_array['submenu'] as $submenu_lv1_id => $submenu_lv1_array ) {
							if ( $node->parent === $submenu_lv1_id ) {
								if ( ! isset( $nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'] ) ) {
									$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'] = array();
								}

								$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $node_id ] = array(
									'id'                  => $node->id,
									'id_default'          => $node->id,
									'title'               => '',
									'title_encoded'       => '',
									'title_clean'         => '',
									'title_default'       => $node->title,
									'title_default_encoded' => htmlentities2( $node->title ),
									'title_default_clean' => wp_strip_all_tags( $node->title ),
									'parent'              => $node->parent,
									'parent_default'      => $node->parent,
									'href'                => '',
									'href_default'        => $node->href,
									'group'               => $node->group,
									'group_default'       => $node->group,
									'meta'                => $node->meta,
									'meta_default'        => $node->meta,
									'was_added'           => 0,
									'disallowed_roles'    => array(),
									'disallowed_users'    => array(),
									'submenu'             => array(),
								);

								unset( $flat_array[ $node_id ] );
								$submenu_lv2_found = true;
								break;
							}
						}
					}

					if ( $submenu_lv2_found ) {
						break;
					}
				}
			}
		}

		// Fifth, get the 3rd depth submenu items.
		if ( ! empty( $flat_array ) ) {
			// Loop the flat_array.
			foreach ( $flat_array as $node_id => $node ) {
				// Loop the nested_array.
				foreach ( $nested_array as $parent_id => $parent_array ) {
					$submenu_lv3_found = false;

					if ( ! empty( $parent_array['submenu'] ) ) {
						// Loop the parent array's submenu.
						foreach ( $parent_array['submenu'] as $submenu_lv1_id => $submenu_lv1_array ) {
							if ( ! empty( $submenu_lv1_array['submenu'] ) ) {
								// Loop the submenu level 1's submenu.
								foreach ( $submenu_lv1_array['submenu'] as $submenu_lv2_id => $submenu_lv2_array ) {
									if ( $node->parent === $submenu_lv2_id ) {
										if ( ! isset( $nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'] ) ) {
											$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'] = array();
										}

										$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'][ $node_id ] = array(
											'id'           => $node->id,
											'id_default'   => $node->id,
											'title'        => '',
											'title_encoded' => '',
											'title_clean'  => '',
											'title_default' => $node->title,
											'title_default_encoded' => htmlentities2( $node->title ),
											'title_default_clean' => wp_strip_all_tags( $node->title ),
											'parent'       => $node->parent,
											'parent_default' => $node->parent,
											'href'         => '',
											'href_default' => $node->href,
											'group'        => $node->group,
											'group_default' => $node->group,
											'meta'         => $node->meta,
											'meta_default' => $node->meta,
											'was_added'    => 0,
											'disallowed_roles' => array(),
											'disallowed_users' => array(),
											'submenu'      => array(),
										);

										unset( $flat_array[ $node_id ] );
										$submenu_lv3_found = true;
										break;
									}
								}
							}

							if ( $submenu_lv3_found ) {
								break;
							}
						}
					}

					if ( $submenu_lv3_found ) {
						break;
					}
				}
			}
		}

		return $nested_array;
	}

	/**
	 * Parse saved menu with existing menu.
	 *
	 * @param array $saved_menu The saved menu.
	 * @param array $existing_menu The nested-formatted existing menu.
	 *
	 * @return array The parsed menu.
	 */
	public function parse_menu( $saved_menu, $existing_menu ) {
		$parsed_menu = array();
		$new_items   = $this->get_new_items( $saved_menu, $existing_menu );

		return $parsed_menu;
	}

	/**
	 * Compare the saved menu to existing menu to get the new menu items
	 * from existing menu if they're found.
	 *
	 * @param array $saved_menu The saved menu.
	 * @param array $existing_menu The nested-formatted existing menu.
	 *
	 * @return array The new menu items.
	 */
	public function get_new_items( $saved_menu, $existing_menu ) {
		$new_items = array();

		$non_udb_items = $this->get_non_udb_items( $saved_menu );

		// Existing admin bar menu items (raw, flat-formatted array of WP_Admin_Bar node object).
		$existing_menu_raw = Vars::get( 'existing_admin_bar_menu' );

		// Let's compare $existing_menu_raw to $non_udb_items.
		foreach ( $existing_menu_raw as $node_id => $node ) {
			if ( ! isset( $non_udb_items[ $node_id ] ) ) {
				$new_items[ $node_id ] = array(
					'id'                    => $node_id,
					'id_default'            => $node_id,
					'title'                 => '',
					'title_encoded'         => '',
					'title_clean'           => '',
					'title_default'         => $node->title,
					'title_default_encoded' => htmlentities2( $node->title ),
					'title_default_clean'   => wp_strip_all_tags( $node->title ),
					'parent'                => $node->parent,
					'parent_default'        => $node->parent,
					'href'                  => '',
					'href_default'          => $node->href,
					'group'                 => $node->group,
					'group_default'         => $node->group,
					'meta'                  => $node->meta,
					'meta_default'          => $node->meta,
					'was_added'             => 0,
					'disallowed_roles'      => array(),
					'disallowed_users'      => array(),
					'submenu'               => array(),
				);
			}
		}

		return $new_items;
	}

	/**
	 * Compare the saved menu to existing menu,
	 * check if there's any default item (item that is not added by UDB) in saved menu
	 * that is no longer exist in existing admin bar menu.
	 *
	 * @param array $saved_menu The saved menu.
	 * @param array $existing_menu The nested-formatted existing menu.
	 *
	 * @return array The removed menu items.
	 */
	public function get_removed_items( $saved_menu, $existing_menu ) {
		$removed_items = array();

		$non_udb_items = $this->get_non_udb_items( $saved_menu );

		// Existing admin bar menu items (raw, flat-formatted array of WP_Admin_Bar node object).
		$existing_menu_raw = Vars::get( 'existing_admin_bar_menu' );

		foreach ( $non_udb_items as $menu_id => $menu_array ) {
			if ( ! isset( $existing_menu_raw[ $menu_id ] ) ) {
				$removed_items[ $menu_id ] = array(
					'parent' => $menu_array['parent'],
				);
			}
		}

		return $removed_items;
	}

	/**
	 * Loop over $saved_menu and get menu items which are not added by UDB builder.
	 *
	 * @param array $saved_menu The saved menu.
	 * @return array The non udb menu items.
	 */
	public function get_non_udb_items( $saved_menu ) {
		/**
		 * Menu items which are not added by UDB.
		 * The value of this variable is a flat-formatted array.
		 */
		$non_udb_items = array();

		foreach ( $saved_menu as $menu_id => $menu_array ) {
			if ( ! $menu_array['was_added'] ) {
				$non_udb_items[ $menu_id ] = array(
					'parent' => $menu_array['parent'],
				);
			}

			if ( $menu_array['submenu'] ) {
				// Loop over submenu level 1.
				foreach ( $menu_array['submenu'] as $submenu_lvl_1_id => $submenu_lvl_1_array ) {
					if ( ! $submenu_lvl_1_array['was_added'] ) {
						$non_udb_items[ $submenu_lvl_1_id ] = array(
							'parent' => $submenu_lvl_1_array['parent'],
						);
					}

					if ( $submenu_lvl_1_array['submenu'] ) {
						// Loop over submenu level 2.
						foreach ( $submenu_lvl_1_array['submenu'] as $submenu_lvl_2_id => $submenu_lvl_2_array ) {
							if ( ! $submenu_lvl_2_array['was_added'] ) {
								$non_udb_items[ $submenu_lvl_2_id ] = array(
									'parent' => $submenu_lvl_2_array['parent'],
								);
							}

							if ( $submenu_lvl_2_array['submenu'] ) {
								// Loop over submenu level 3.
								foreach ( $submenu_lvl_2_array['submenu'] as $submenu_lvl_3_id => $submenu_lvl_3_array ) {
									if ( ! $submenu_lvl_3_array['was_added'] ) {
										$non_udb_items[ $submenu_lvl_3_id ] = array(
											'parent' => $submenu_lvl_3_array['parent'],
										);
									}
								} // End of $submenu_lvl_2_array['submenu'] foreach.
							} // End of $submenu_lvl_2_array['submenu'] checking.
						} // End of $submenu_lvl_1_array['submenu'] foreach.
					} // End of $submenu_lvl_1_array['submenu'] checking.
				} // End of $menu_array['submenu'] foreach.
			} // End of $menu_array['submenu'] checking.
		}

		return $non_udb_items;
	}

}
