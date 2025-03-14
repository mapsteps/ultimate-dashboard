<?php
/**
 * Admin Menu module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminMenu;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup admin menu module.
 */
class Admin_Menu_Module extends Base_Module {

	/**
	 * The class instance.
	 *
	 * @var Admin_Menu_Module
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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-menu';

	}

	/**
	 * Get instance of the class.
	 *
	 * @return Admin_Menu_Module
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
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );
		add_action( 'udb_ajax_get_admin_menu', array( self::get_instance(), 'get_admin_menu' ), 15, 2 );

		// Save the recent menu and the priority should be lower than our output.
		add_action( 'admin_menu', array( $this, 'save_recent_menu' ), 9000 );

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

		add_action( 'wp_ajax_udb_admin_menu_get_menu', array( $get_menu, 'ajax' ) );
		add_action( 'wp_ajax_udb_admin_menu_get_users', array( $get_users, 'ajax' ) );

	}

	/**
	 * Add submenu page.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Admin Menu Editor', 'ultimate-dashboard' ), __( 'Admin Menu Editor', 'ultimate-dashboard' ), apply_filters( 'udb_settings_capability', 'manage_options' ), 'udb_admin_menu', array( $this, 'submenu_page_content' ) );

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
	 * Get admin menu via ajax.
	 * This action will be called in "ajax" method in "class-get-menu.php".
	 *
	 * @see wp-content/plugins/ultimate-dashboard/helpers/class-user-helper.php
	 *
	 * @param Ajax\Get_Menu $ajax_handler The ajax handler class from the free version.
	 * @param string        $role The role target to simulate.
	 */
	public function get_admin_menu( $ajax_handler, $role ) {

		$roles = wp_get_current_user()->roles;
		$roles = ! $roles || ! is_array( $roles ) ? array() : $roles;

		$simulate_role = in_array( $role, $roles, true ) ? false : true;

		// If current user role is different with the targetted role.
		if ( $simulate_role ) {
			$this->user()->simulate_role( $role, true );
		}

		// Load the global $menu and $submenu.
		$ajax_handler->load_menu();

		// Then format the response.
		$response = $ajax_handler->format_response( $role );

		// And then send the response.
		wp_send_json_success( $response );

	}

	/**
	 * Save recent / up to date global $menu and $submenu to database.
	 *
	 * Some plugins have some condition that makes their admin menu not being added inside ajax request.
	 * To make sure that we have the recent menu & submenu,
	 * we save the global $menu and $submenu everytime a user visit a page in admin area.
	 *
	 * This function is hooked into `admin_menu` action but the priority should be lower than our output.
	 */
	public function save_recent_menu() {

		/**
		 * Prevent this function from being executed by do_action( 'admin_menu' )
		 * when it's inside ajax request.
		 */
		if ( wp_doing_ajax() ) {
			return;
		}

		/**
		 * Stop if current request is ajax request `udb_admin_menu_get_menu`.
		 * When doing `udb_admin_menu_get_menu`, wp_doing_ajax() is temporarily set to `true`,
		 * that's why the checking above alone is not enough.
		 *
		 * This is to prevent the wrong result of saving the global $menu and $submenu
		 * during the ajax request when getting menu for our admin menu editor (the builder).
		 */
		if ( isset( $_POST['action'] ) && 'udb_admin_menu_get_menu' === $_POST['action'] ) {
			return;
		}

		/**
		 * We can't simply use the Screen_Helper class and check for is_admin_menu here.
		 * Because we also need to check if get_current_screen() is null.
		 */
		$current_screen = get_current_screen();

		if ( is_null( $current_screen ) ) {
			return;
		}

		if ( 'edit-udb_widgets_page_udb_admin_menu' !== $current_screen->id ) {
			return;
		}

		global $menu, $submenu;

		$roles = wp_get_current_user()->roles;
		$role  = $roles[0];

		$recent_menu = get_option( 'udb_recent_admin_menu', array() );

		if ( ! isset( $recent_menu[ $role ] ) ) {
			$recent_menu[ $role ] = array();
		}

		$recent_menu[ $role ] = array(
			'menu'    => $menu,
			'submenu' => $submenu,
		);

		update_option( 'udb_recent_admin_menu', $recent_menu, false );

	}

}
