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

		require_once __DIR__ . '/ajax/class-get-users.php';

		$get_users = new Ajax\Get_Users();

		add_action( 'wp_ajax_udb_admin_bar_get_users', array( $get_users, 'ajax' ) );

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
	}

	/**
	 * Turn flat admin bar menu array to a nested format (parent -> submenu).
	 *
	 * @param array $nodes The existing admin bar menu.
	 * @return array Array in expected format.
	 */
	public function nodes_to_array( $nodes ) {
		$udb_array = array();

		foreach ( $nodes as $node_id => $node ) {
			$udb_array[ $node_id ] = array(
				'title'            => '',
				'title_default'    => $node->title,
				'id'               => $node->id,
				'id_default'       => $node->id,
				'parent'           => $node->parent,
				'parent_default'   => $node->parent,
				'href'             => '',
				'href_default'     => $node->href,
				'group'            => $node->group,
				'group_default'    => $node->group,
				'meta'             => '',
				'meta_default'     => $node->meta,
				'was_added'        => 0,
				'disallowed_roles' => array(),
				'disallowed_users' => array(),
			);
		}

		return $udb_array;
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
		$non_udb_items_id = $this->get_non_udb_items_id( $saved_menu );

		$prev_id = '';

		// Get new items which are not inside $saved_menu.
		foreach ( $existing_menu as $menu_id => $menu ) {
			if ( ! in_array( $menu_id, $non_udb_items_id, true ) ) {
				$new_item = array(
					'id'               => $menu_id,
					'id_default'       => $menu_id,
					'title'            => $menu['title'],
					'title_default'    => $menu['title'],
					'parent'           => $menu['parent'],
					'parent_default'   => $menu['parent'],
					'href'             => $menu['href'],
					'href_default'     => $menu['href'],
					'group'            => $menu['group'],
					'group_default'    => $menu['group'],
					'meta'             => $menu['meta'],
					'meta_default'     => $menu['meta'],
					'was_added'        => 0,
					'disallowed_roles' => array(),
					'disallowed_users' => array(),
				);

				if ( empty( $prev_id ) ) {
					$saved_menu = array( $menu_id => $new_item ) + $saved_menu;
				} else {
					$pos = array_search( $prev_id, array_keys( $saved_menu ), true );

					$saved_menu = array_slice( $saved_menu, 0, $pos, true ) +
						array( $menu_id => $new_item ) +
						array_slice( $saved_menu, $pos, count( $saved_menu ) - 1, true );
				}
			}

			$prev_id = $menu_id;
		}

		// Exclude non-udb items from $saved_menu which are no longer exist in $existing_menu.
		foreach ( $non_udb_items_id as $menu_id ) {
			if ( ! isset( $existing_menu[ $menu_id ] ) ) {
				unset( $saved_menu[ $menu_id ] );
			}
		}

		// Bring some defaults from $existing_menu to $saved_menu.
		foreach ( $saved_menu as $menu_id => $menu ) {
			if ( isset( $existing_menu[ $menu_id ] ) ) {
				// Loop over matched $existing_menu item.
				foreach ( $existing_menu[ $menu_id ] as $field_key => $field_value ) {
					if ( ! isset( $menu[ $field_key ] ) ) {
						$saved_menu[ $menu_id ][ $field_key ] = $field_value;
					}
				}
			}
		}

		return $saved_menu;
	}

	/**
	 * Loop over $saved_menu and collect the id of menu items which are not added by UDB builder.
	 *
	 * @param array $saved_menu The saved menu.
	 * @return array The non udb menu items.
	 */
	public function get_non_udb_items_id( $saved_menu ) {
		// Id of menu items which are not added by UDB.
		$non_udb_items_id = array();

		foreach ( $saved_menu as $menu_id => $menu_array ) {
			if ( ! $menu_array['was_added'] ) {
				array_push( $non_udb_items_id, $menu_id );
			}
		}

		return $non_udb_items_id;
	}

	/**
	 * Turn flat admin bar array to a nested format as needed in menu builder.
	 *
	 * @param array $flat_array The flat array format of admin bar menu.
	 * @return array The nested format as needed in menu builder.
	 */
	public function to_builder_format( $flat_array ) {
		if ( ! $flat_array ) {
			return array();
		}

		$nested_array = array();

		// First, get the parent menu items.
		foreach ( $flat_array as $menu_id => $menu ) {
			if ( ! $menu['parent'] || ! isset( $flat_array[ $menu['parent'] ] ) ) {
				$nested_array[ $menu_id ] = $menu;

				$additional = array(
					'title_encoded'         => htmlentities2( $menu['title'] ),
					'title_clean'           => wp_strip_all_tags( $menu['title'] ),
					'title_default_encoded' => htmlentities2( $menu['title_default'] ),
					'title_default_clean'   => wp_strip_all_tags( $menu['title_default'] ),
					'submenu'               => array(),
				);

				$nested_array[ $menu_id ] = array_merge( $nested_array[ $menu_id ], $additional );
			}
		}

		// Second, remove collected parent array from $flat_array.
		foreach ( $nested_array as $key => $value ) {
			if ( isset( $flat_array[ $key ] ) ) {
				unset( $flat_array[ $key ] );
			}
		}

		// Third, get the 1st level submenu items.
		foreach ( $flat_array as $menu_id => $menu ) {
			if ( isset( $nested_array[ $menu['parent'] ] ) ) {
				$nested_array[ $menu['parent'] ]['submenu'][ $menu['id'] ] = $menu;

				$additional = array(
					'title_encoded'         => htmlentities2( $menu['title'] ),
					'title_clean'           => wp_strip_all_tags( $menu['title'] ),
					'title_default_encoded' => htmlentities2( $menu['title_default'] ),
					'title_default_clean'   => wp_strip_all_tags( $menu['title_default'] ),
					'submenu'               => array(),
				);

				$nested_array[ $menu['parent'] ]['submenu'][ $menu['id'] ] = array_merge(
					$nested_array[ $menu['parent'] ]['submenu'][ $menu['id'] ],
					$additional
				);

				unset( $flat_array[ $menu_id ] );
			}
		}

		// Fourth, get the 2nd level submenu items.
		if ( ! empty( $flat_array ) ) {
			// Loop over flat_array.
			foreach ( $flat_array as $menu_id => $menu ) {
				// Loop over nested_array.
				foreach ( $nested_array as $parent_id => $parent_array ) {
					$submenu_lv2_found = false;

					if ( ! empty( $parent_array['submenu'] ) ) {
						// Loop over parent array's submenu.
						foreach ( $parent_array['submenu'] as $submenu_lv1_id => $submenu_lv1_array ) {
							if ( $menu['parent'] === $submenu_lv1_id ) {
								if ( ! isset( $nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'] ) ) {
									$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'] = array();
								}

								$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $menu_id ] = $menu;

								$additional = array(
									'title_encoded'       => htmlentities2( $menu['title'] ),
									'title_clean'         => wp_strip_all_tags( $menu['title'] ),
									'title_default_encoded' => htmlentities2( $menu['title_default'] ),
									'title_default_clean' => wp_strip_all_tags( $menu['title_default'] ),
									'submenu'             => array(),
								);

								$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $menu_id ] = array_merge(
									$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $menu_id ],
									$additional
								);

								unset( $flat_array[ $menu_id ] );
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

		// Fifth, get the 3rd level submenu items.
		if ( ! empty( $flat_array ) ) {
			// Loop over flat_array.
			foreach ( $flat_array as $menu_id => $menu ) {
				// Loop over nested_array.
				foreach ( $nested_array as $parent_id => $parent_array ) {
					$submenu_lv3_found = false;

					if ( ! empty( $parent_array['submenu'] ) ) {
						// Loop over parent array's submenu.
						foreach ( $parent_array['submenu'] as $submenu_lv1_id => $submenu_lv1_array ) {
							if ( ! empty( $submenu_lv1_array['submenu'] ) ) {
								// Loop over submenu level 1's submenu.
								foreach ( $submenu_lv1_array['submenu'] as $submenu_lv2_id => $submenu_lv2_array ) {
									if ( $menu['parent'] === $submenu_lv2_id ) {
										if ( ! isset( $nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'] ) ) {
											$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'] = array();
										}

										$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'][ $menu_id ] = $menu;

										$additional = array(
											'title_encoded' => htmlentities2( $menu['title'] ),
											'title_clean' => wp_strip_all_tags( $menu['title'] ),
											'title_default_encoded' => htmlentities2( $menu['title_default'] ),
											'title_default_clean' => wp_strip_all_tags( $menu['title_default'] ),
											'submenu'     => array(),
										);

										$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'][ $menu_id ] = array_merge(
											$nested_array[ $parent_id ]['submenu'][ $submenu_lv1_id ]['submenu'][ $submenu_lv2_id ]['submenu'][ $menu_id ],
											$additional
										);

										unset( $flat_array[ $menu_id ] );
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

}
