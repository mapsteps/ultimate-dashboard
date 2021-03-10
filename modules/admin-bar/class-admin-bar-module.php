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
		add_action( 'admin_bar_menu', array( self::get_instance(), 'get_existing_menu' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );
		add_action( 'udb_ajax_get_admin_bar', array( self::get_instance(), 'get_admin_bar' ), 15, 2 );

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
	 * Get admin menu via ajax.
	 * This action will be called in "ajax" method in "class-get-menu.php".
	 *
	 * @param object $ajax_handler The ajax handler class from the free version.
	 * @param string $role The role target to simulate.
	 */
	public function get_admin_bar( $ajax_handler, $role ) {

		$roles = wp_get_current_user()->roles;
		$roles = ! $roles || ! is_array( $roles ) ? array() : $roles;

		if ( ! in_array( $role, $roles, true ) ) {
			$this->user()->simulate_role( $role );
		}

		$ajax_handler->load_menu();

		$response = $ajax_handler->format_response( $role );

		wp_send_json_success( $response );

	}

	/**
	 * Turn flat admin bar menu array to a nested format (parent -> submenu).
	 *
	 * @param array $flat_array The default format of admin bar menu.
	 * @return array The nested format of admin bar menu.
	 */
	public function to_nested_format( $flat_array ) {
		if ( ! $flat_array ) {
			return $flat_array;
		}

		$nested_array = array();

		// First, get the parent menu items.
		foreach ( $flat_array as $node_id => $node ) {
			if ( ! $node->parent || ! isset( $flat_array[ $node->parent ] ) ) {
				$nested_array[ $node_id ] = array(
					'id'      => $node->id,
					'title'   => $node->title,
					'parent'  => $node->parent,
					'href'    => $node->href,
					'group'   => $node->group,
					'meta'    => $node->meta,
					'submenu' => array(),
				);
			}
		}

		// Second, get the submenu items.
		foreach ( $flat_array as $node_id => $node ) {
			if ( $node->parent && isset( $flat_array[ $node->parent ] ) ) {
				$nested_array[ $node->parent ]['submenu'] = array(
					'id'      => $node->id,
					'title'   => $node->title,
					'parent'  => $node->parent,
					'href'    => $node->href,
					'group'   => $node->group,
					'meta'    => $node->meta,
					'submenu' => array(),
				);
			}
		}
	}

}
