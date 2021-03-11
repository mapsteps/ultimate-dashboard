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

		$get_menu  = new Ajax\Get_Menu();
		$get_users = new Ajax\Get_Users();

		add_action( 'wp_ajax_udb_admin_bar_get_menu', array( $get_menu, 'ajax' ) );
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
					'id'             => $node->id,
					'id_default'     => $node->id,
					'title'          => $node->title,
					'title_default'  => $node->title,
					'parent'         => $node->parent,
					'parent_default' => $node->parent,
					'href'           => $node->href,
					'href_default'   => $node->href,
					'group'          => $node->group,
					'group_default'  => $node->group,
					'meta'           => $node->meta,
					'meta_default'   => $node->meta,
					'submenu'        => array(),
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
					'id'             => $node->id,
					'id_default'     => $node->id,
					'title'          => $node->title,
					'title_default'  => $node->title,
					'parent'         => $node->parent,
					'parent_default' => $node->parent,
					'href'           => $node->href,
					'href_default'   => $node->href,
					'group'          => $node->group,
					'group_default'  => $node->group,
					'meta'           => $node->meta,
					'meta_default'   => $node->meta,
					'submenu'        => array(),
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
									'id'             => $node->id,
									'id_default'     => $node->id,
									'title'          => $node->title,
									'title_default'  => $node->title,
									'parent'         => $node->parent,
									'parent_default' => $node->parent,
									'href'           => $node->href,
									'href_default'   => $node->href,
									'group'          => $node->group,
									'group_default'  => $node->group,
									'meta'           => $node->meta,
									'meta_default'   => $node->meta,
									'submenu'        => array(),
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
											'title'        => $node->title,
											'title_default' => $node->title,
											'parent'       => $node->parent,
											'parent_default' => $node->parent,
											'href'         => $node->href,
											'href_default' => $node->href,
											'group'        => $node->group,
											'group_default' => $node->group,
											'meta'         => $node->meta,
											'meta_default' => $node->meta,
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

}
