<?php
/**
 * Admin Bar module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminBar;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;
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
	 * Frontend admin bar menu items.
	 *
	 * @var array
	 */
	public $frontend_items = array();

	/**
	 * Frontend admin bar menu in udb expected format.
	 *
	 * @var array
	 */
	public $frontend_menu = array();

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-bar';

	}

	/**
	 * Initialize frontend items.
	 * Called after init hook to ensure translations are available.
	 *
	 * This was created by looking at wp-toolbar-editor plugin's code.
	 * These items can be checked in wp-includes/admin-bar.php file.
	 */
	public function init_frontend_items() {

		if ( ! empty( $this->frontend_items ) ) {
			// Already initialized.
			return;
		}

		$this->frontend_items = array(
			array(
				'parent' => 'top-secondary',
				'id'     => 'search',
				'title'  => '',
				'meta'   => array(
					'class'    => 'admin-bar-search',
					'tabindex' => -1,
				),
			),

			array(
				'parent' => false,
				'after'  => 'site-name',
				'id'     => 'customize',
				'title'  => __( 'Customize', 'ultimate-dashboard' ),
				'href'   => '',
				'meta'   => array(
					'class' => 'hide-if-no-customize',
				),
			),

			array(
				'parent' => false,
				'after'  => 'new-content',
				'id'     => 'edit',
				'title'  => __( 'Edit', 'ultimate-dashboard' ) . ' {post_type}',
				'href'   => '',
			),

			array(
				'parent' => 'site-name',
				'id'     => 'dashboard',
				'title'  => __( 'Dashboard', 'ultimate-dashboard' ),
				'href'   => admin_url(),
			),

			array(
				'parent' => 'site-name',
				'after'  => 'dashboard',
				'id'     => 'appearance',
				'title'  => '',
				'href'   => '',
				'group'  => true,
			),

			array(
				'parent' => 'appearance',
				'id'     => 'themes',
				'title'  => __( 'Themes', 'ultimate-dashboard' ),
				'href'   => admin_url( 'themes.php' ),
			),

			array(
				'parent' => 'appearance',
				'after'  => 'themes',
				'id'     => 'widgets',
				'title'  => __( 'Widgets', 'ultimate-dashboard' ),
				'href'   => admin_url( 'widgets.php' ),
			),

			array(
				'parent' => 'appearance',
				'after'  => 'widgets',
				'id'     => 'menus',
				'title'  => __( 'Menus', 'ultimate-dashboard' ),
				'href'   => admin_url( 'nav-menus.php' ),
			),
		);

		$this->frontend_menu = $this->frontend_items_to_array();

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
		add_action( 'wp_before_admin_bar_render', array( self::get_instance(), 'get_existing_menu' ), 999999 );
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

		require_once __DIR__ . '/ajax/class-save-remove-by-roles.php';
		new Ajax\Save_Remove_By_Roles();

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Admin Bar Editor', 'ultimate-dashboard' ), __( 'Admin Bar Editor', 'ultimate-dashboard' ), apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_admin_bar', array( $this, 'submenu_page_content' ) );

	}

	/**
	 * Submenu page content.
	 */
	public function submenu_page_content() {

		$template = require __DIR__ . '/templates/template.php';
		$template( $this );

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
	 */
	public function get_existing_menu() {

		global $wp_admin_bar;

		Vars::set( 'existing_admin_bar_menu', $wp_admin_bar->get_nodes() );

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
				'title'          => '',
				'title_default'  => $node->title,
				'id'             => $node->id,
				'id_default'     => $node->id,
				'parent'         => $node->parent,
				'parent_default' => $node->parent,
				'href'           => '',
				'href_default'   => $node->href,
				'group'          => $node->group,
				'group_default'  => $node->group,
				'meta'           => $node->meta,
				'meta_default'   => $node->meta,
				'was_added'      => 0,
				'is_hidden'      => 0,
				/**
				 * These properties are not being used currently.
				 * But leave it here because in the future, if requested, it would be used for
				 * "hide menu item for specific role(s) & user(s)" functionality (inside dropdowns).
				 */
				// 'disallowed_roles' => array(),
				// 'disallowed_users' => array(),
			);
		}

		return $udb_array;
	}

	/**
	 * Convert frontend items to array in expected format.
	 *
	 * @return array Array in expected format.
	 */
	public function frontend_items_to_array() {
		$this->init_frontend_items(); // Ensure items are initialized.

		$udb_array = array();

		foreach ( $this->frontend_items as $item_data ) {
			$item_id = $item_data['id'];

			$udb_array[ $item_id ] = array(
				'title'          => '',
				'title_default'  => $item_data['title'],
				'id'             => $item_data['id'],
				'id_default'     => $item_data['id'],
				'parent'         => $item_data['parent'],
				'parent_default' => $item_data['parent'],
				'href'           => '',
				'href_default'   => isset( $item_data['href'] ) ? $item_data['href'] : '',
				'group'          => isset( $item_data['group'] ) ? $item_data['group'] : false,
				'group_default'  => isset( $item_data['group'] ) ? $item_data['group'] : false,
				'meta'           => isset( $item_data['meta'] ) ? $item_data['meta'] : array(),
				'meta_default'   => isset( $item_data['meta'] ) ? $item_data['meta'] : array(),
				'was_added'      => 0,
				'is_hidden'      => 0,
				'frontend_only'  => 1,
				/**
				 * These properties are not being used currently.
				 * But leave it here because in the future, if requested, it would be used for
				 * "hide menu item for specific role(s) & user(s)" functionality (inside dropdowns).
				 */
				// 'disallowed_roles' => array(),
				// 'disallowed_users' => array(),
			);

			if ( isset( $item_data['after'] ) ) {
				$udb_array[ $item_id ]['after'] = $item_data['after'];
			}
		}

		return $udb_array;
	}

	/**
	 * Parse saved menu with existing menu.
	 *
	 * @param array $saved_menu The saved menu.
	 * @param array $existing_menu The nested-formatted existing menu.
	 * @param bool  $target This function is being called either for the builder or the output.
	 *              Possible vaule is 'builder' and 'output'.
	 *
	 * @return array The parsed menu.
	 */
	public function parse_menu( $saved_menu, $existing_menu, $target = 'builder' ) {
		$non_udb_items_id = $this->get_non_udb_items_id( $saved_menu );

		$prev_id = '';

		// Get new items from $existing_menu which are not inside $saved_menu.
		foreach ( $existing_menu as $menu_id => $menu ) {
			if ( ! in_array( $menu_id, $non_udb_items_id, true ) ) {
				$new_item = array(
					'id'             => $menu_id,
					'id_default'     => $menu_id,
					'title'          => $menu['title'],
					'title_default'  => $menu['title'],
					'parent'         => $menu['parent'],
					'parent_default' => $menu['parent'],
					'href'           => $menu['href'],
					'href_default'   => $menu['href'],
					'group'          => $menu['group'],
					'group_default'  => $menu['group'],
					'meta'           => $menu['meta'],
					'meta_default'   => $menu['meta'],
					'was_added'      => 0,
					'is_hidden'      => 0,
					/**
					 * These properties are not being used currently.
					 * But leave it here because in the future, if requested, it would be used for
					 * "hide menu item for specific role(s) & user(s)" functionality (inside dropdowns).
					 */
					// 'disallowed_roles' => array(),
					// 'disallowed_users' => array(),
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
			if ( 'output' === $target ) {
				if ( ! isset( $existing_menu[ $menu_id ] ) ) {
					unset( $saved_menu[ $menu_id ] );
				}
			} elseif ( ! isset( $existing_menu[ $menu_id ] ) && ! isset( $this->frontend_menu[ $menu_id ] ) ) {
					unset( $saved_menu[ $menu_id ] );
			}
		}

		// Reset some item's property's value (such as title) so that it will use the existing item's value.
		if ( 'output' === $target ) {
			if ( isset( $saved_menu['edit'] ) ) {
				$saved_menu['edit']['title']         = '';
				$saved_menu['edit']['title_default'] = '';
			}
		}

		// Bring some defaults from $existing_menu to $saved_menu.
		foreach ( $saved_menu as $menu_id => $menu ) {
			if ( isset( $existing_menu[ $menu_id ] ) ) {
				// Loop over matched $existing_menu item.
				foreach ( $existing_menu[ $menu_id ] as $field_key => $field_value ) {
					if ( ! isset( $menu[ $field_key ] ) ) {
						$saved_menu[ $menu_id ][ $field_key ] = $field_value;
					} elseif ( 'output' === $target ) {
						if ( empty( $menu[ $field_key ] ) && ! empty( $field_value ) ) {
							$saved_menu[ $menu_id ][ $field_key ] = $field_value;
						}
					}
				}
			}
		}

		// Compare saved menu's default values to existing menu's default values.
		foreach ( $saved_menu as $menu_id => $menu ) {
			if ( isset( $existing_menu[ $menu_id ] ) ) {
				// Loop over matched $saved_menu item.
				foreach ( $menu as $field_key => $field_value ) {
					if ( false !== stripos( $field_key, '_default' ) ) {
						if ( isset( $existing_menu[ $menu_id ][ $field_key ] ) && $field_value !== $existing_menu[ $menu_id ][ $field_key ] ) {
							$saved_menu[ $menu_id ][ $field_key ] = $existing_menu[ $menu_id ][ $field_key ];
						}
					}
				}
			}
		}

		/**
		 * The "menu-toggle" has been removed from the admin bar buider.
		 * Now after parsing it, its position is not at the beginning of the array.
		 * Let's bring it back to the correct position (as first item of the array).
		 */
		if ( isset( $saved_menu['menu-toggle'] ) ) {
			unset( $saved_menu['menu-toggle'] );
		}

		if ( isset( $existing_menu['menu-toggle'] ) ) {
			$saved_menu = array( 'menu-toggle' => $existing_menu['menu-toggle'] ) + $saved_menu;
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
	 * Loop over $saved_menu and collect the id of menu items
	 * which are not added by UDB builder and frontend only.
	 *
	 * @param array $saved_menu The saved menu.
	 * @return array The non udb menu items.
	 */
	public function get_non_udb_items_id_fontend_only( $saved_menu ) {
		// Id of menu items which are not added by UDB & frontend only.
		$non_udb_items_id = array();

		foreach ( $saved_menu as $menu_id => $menu_array ) {
			if ( ! $menu_array['was_added'] && isset( $menu_array['frontend_only'] ) && $menu_array['frontend_only'] ) {
				array_push( $non_udb_items_id, $menu_id );
			}
		}

		return $non_udb_items_id;
	}

	/**
	 * Parse frontend items with saved menu.
	 *
	 * @param array $saved_menu The saved menu.
	 * @return array
	 */
	public function parse_frontend_items( $saved_menu ) {
		$this->init_frontend_items(); // Ensure items are initialized.

		$non_udb_items_id = $this->get_non_udb_items_id_fontend_only( $saved_menu );

		$prev_id = '';

		$uninserted_items = array();

		// Get new items from $this->frontend_menu which are not inside $saved_menu.
		foreach ( $this->frontend_menu as $menu_id => $menu ) {
			if ( ! in_array( $menu_id, $non_udb_items_id, true ) ) {
				$new_item = $menu;

				if ( isset( $new_item['after'] ) ) {
					unset( $new_item['after'] );
				}

				if ( isset( $menu['after'] ) && $menu['after'] ) {
					if ( isset( $saved_menu[ $menu['after'] ] ) ) {
						$pos = array_search( $menu['after'], array_keys( $saved_menu ), true );
						++$pos;

						$saved_menu = array_slice( $saved_menu, 0, $pos, true ) +
							array( $menu_id => $new_item ) +
							array_slice( $saved_menu, $pos, count( $saved_menu ) - 1, true );
					} else {
						$uninserted_items[ $menu_id ] = $new_item;
					}
				} elseif ( empty( $prev_id ) ) {
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

		$saved_menu = $this->insert_uninserted_items( $saved_menu, $uninserted_items, 10 );

		return $saved_menu;
	}

	/**
	 * Insert un-inserted items to saved menu.
	 *
	 * @param array $saved_menu The saved menu.
	 * @param array $uninserted_items The uninserted items.
	 * @param int   $total_loop Max number of the the loop.
	 *
	 * @return array
	 */
	public function insert_uninserted_items( $saved_menu, $uninserted_items, $total_loop = 1 ) {

		for ( $i = 0; $i < $total_loop; $i++ ) {
			$remaining_items = array();

			// Get new items from $uninserted_items which are not inside $saved_menu.
			foreach ( $uninserted_items as $menu_id => $menu ) {
				$new_item = $menu;

				if ( isset( $saved_menu[ $menu['after'] ] ) ) {
					$pos = array_search( $menu['after'], array_keys( $saved_menu ), true );
					++$pos;

					$saved_menu = array_slice( $saved_menu, 0, $pos, true ) +
					array( $menu_id => $new_item ) +
					array_slice( $saved_menu, $pos, count( $saved_menu ) - 1, true );
				} else {
					$remaining_items[ $menu_id ] = $new_item;
				}
			}

			if ( empty( $remaining_items ) ) {
				break;
			} else {
				$saved_menu = $this->insert_uninserted_items( $saved_menu, $remaining_items );
			}
		}

		return $saved_menu;
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

		if ( isset( $flat_array['menu-toggle'] ) ) {
			unset( $flat_array['menu-toggle'] );
		}

		// First, create new site-name item for frontend as "site-name-frontend".
		$site_name_frontend = array(
			'title'          => '',
			'title_default'  => $flat_array['site-name']['title_default'],
			'id'             => 'site-name-frontend',
			'id_default'     => 'site-name-frontend',
			'parent'         => false,
			'parent_default' => false,
			'href'           => '',
			'href_default'   => admin_url(),
			'group'          => false,
			'group_default'  => false,
			'meta'           => array(),
			'meta_default'   => array(),
			'was_added'      => 0,
			'is_hidden'      => 0,
			'frontend_only'  => 1,
			/**
			 * These properties are not being used currently.
			 * But leave it here because in the future, if requested, it would be used for
			 * "hide menu item for specific role(s) & user(s)" functionality (inside dropdowns).
			 */
			// 'disallowed_roles' => array(),
			// 'disallowed_users' => array(),
		);

		$pos = array_search( 'site-name', array_keys( $flat_array ), true );
		++$pos;

		// Then place "site-name-frontend" after "site-name".
		$flat_array = array_slice( $flat_array, 0, $pos, true ) +
			array( 'site-name-frontend' => $site_name_frontend ) +
			array_slice( $flat_array, $pos, count( $flat_array ) - 1, true );

		$nested_array = array();

		/**
		 * Second, collect frontend only items which have "site-name" as the default parent,
		 * change their parent to "site-name-frontend".
		 */
		foreach ( $flat_array as $menu_id => $menu ) {
			if ( isset( $menu['frontend_only'] ) && $menu['frontend_only'] && isset( $menu['parent'] ) && $menu['parent'] && 'site-name' === $menu['parent_default'] ) {
				$flat_array[ $menu_id ]['parent'] = 'site-name-frontend';
			}
		}

		// Third, get the parent menu items.
		foreach ( $flat_array as $menu_id => $menu ) {
			if ( ! isset( $menu['parent'] ) || ! $menu['parent'] || ! isset( $flat_array[ $menu['parent'] ] ) ) {
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

		// Fourth, remove collected parent array from $flat_array.
		foreach ( $nested_array as $key => $value ) {
			if ( isset( $flat_array[ $key ] ) ) {
				unset( $flat_array[ $key ] );
			}
		}

		// Fifth, get the 1st level submenu items.
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

		// Sixth, get the 2nd level submenu items.
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

		// Seventh, get the 3rd level submenu items.
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

	/**
	 * Remove by role tab field.
	 */
	public function remove_by_role_field_tab() {

		return require __DIR__ . '/templates/fields/remove-by-role-tab.php';

	}
}
