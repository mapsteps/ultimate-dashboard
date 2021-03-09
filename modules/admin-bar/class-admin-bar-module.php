<?php
/**
 * Admin Bar module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminBar;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup admin bar module.
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
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'admin_scripts' ) );

	}

	/**
	 * Setup ajax.
	 */
	public function setup_ajax() {

		//

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
		// $enqueue( $this );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		$enqueue = require __DIR__ . '/inc/js-enqueue.php';
		// $enqueue( $this );

	}

}
