<?php
/**
 * Admin page module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\AdminPage;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;
use WP_Post;

/**
 * Class to setup admin page module.
 */
class Admin_Page_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-page';

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
	 * Setup admin page module.
	 */
	public function setup() {

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'post_updated_messages', array( $this, 'update_messages' ) );
		add_filter( 'manage_udb_admin_page_posts_columns', array( $this, 'set_columns' ) );
		add_action( 'manage_udb_admin_page_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
		add_action( 'do_meta_boxes', array( $this, 'remove_metaboxes' ) );

		add_filter( 'template_include', array( $this, 'include_template' ), 1 );

		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
		add_filter( 'submenu_file', array( $this, 'highlight_submenu' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

		add_action( 'wp_ajax_udb_admin_page_change_active_status', array( $this, 'change_active_status' ) );

		// The module output.
		require_once __DIR__ . '/class-admin-page-output.php';
		Admin_Page_Output::init();

	}

	/**
	 * Register post type.
	 */
	public function register_post_type() {

		$post_type = require __DIR__ . '/inc/post-type.php';
		$post_type();

	}

	/**
	 * Update messages.
	 *
	 * @param array $messages The messages.
	 */
	public function update_messages( $messages ) {

		$post = get_post();

		$messages['udb_admin_page'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Admin Page updated.', 'ultimate-dashboard' ),
			2  => __( 'Custom field updated.', 'ultimate-dashboard' ),
			3  => __( 'Custom field deleted.', 'ultimate-dashboard' ),
			4  => __( 'Admin Page updated.', 'ultimate-dashboard' ),
			// translators: %s: Date and time of the revision.
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Admin Page restored to revision from %s', 'ultimate-dashboard' ), wp_post_revision_title( absint( $_GET['revision'] ), false ) ) : false,
			6  => __( 'Admin Page published.', 'ultimate-dashboard' ),
			7  => __( 'Admin Page saved.', 'ultimate-dashboard' ),
			8  => __( 'Admin Page submitted.', 'ultimate-dashboard' ),
			9  => sprintf(
			// translators: Publish box date format, see http://php.net/date for more info.
				__( 'Admin Page scheduled for: <strong>%1$s</strong>.', 'ultimate-dashboard' ),
				date_i18n( __( 'M j, Y @ G:i', 'ultimate-dashboard' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Admin Page draft updated.', 'ultimate-dashboard' ),
		);

		return $messages;

	}

	/**
	 * Setup widget list columns.
	 *
	 * @param array $columns The columns.
	 */
	public function set_columns( $columns ) {

		return array(
			'cb'          => '<input type="checkbox" />',
			'title'       => __( 'Page Name', 'ultimate-dashboard' ),
			'icon'        => __( 'Menu Icon', 'ultimate-dashboard' ),
			'type'        => __( 'Content Type', 'ultimate-dashboard' ),
			'parent_menu' => __( 'Parent Menu', 'ultimate-dashboard' ),
			'roles'       => __( 'User Roles', 'ultimate-dashboard' ),
			'is_active'   => __( 'Active', 'ultimate-dashboard' ),
		);

	}

	/**
	 * Widget list column content.
	 *
	 * @param string  $column The column name/key.
	 * @param integer $post_id The post ID.
	 */
	public function column_content( $column, $post_id ) {

		$column_content = require __DIR__ . '/inc/column-content.php';
		$column_content( $this, $column, $post_id );

	}

	/**
	 * Remove some known metaboxes from admin page editing.
	 */
	public function remove_metaboxes() {

		remove_meta_box( 'wpbf', 'udb_admin_page', 'side' );
		remove_meta_box( 'wpbf_sidebar', 'udb_admin_page', 'side' );
		remove_meta_box( 'revisionsdiv', 'udb_admin_page', 'normal' );
		remove_meta_box( 'slugdiv', 'udb_admin_page', 'normal' );
		remove_meta_box( 'pageparentdiv', 'udb_admin_page', 'side' );
		remove_meta_box( 'wpbf_header', 'udb_admin_page', 'side' );
		remove_meta_box( 'postcustom', 'udb_admin_page', 'normal' );

	}

	/**
	 * Force default template for admin page.
	 * This could live in the PRO add-on as frontend-editing is only available via page builders.
	 * Though, moving it is not worth the effort and this might come in handy at some point.
	 *
	 * @param string $template_path The template path.
	 *
	 * @return string The template path.
	 */
	public function include_template( $template_path ) {

		if ( 'udb_admin_page' !== get_post_type() ) {
			return $template_path;
		}

		return __DIR__ . '/templates/edit-page.php';

	}

	/**
	 * Add "Admin Page" submenu under "Ultimate Dashboard" menu item.
	 */
	public function submenu_page() {

		add_submenu_page( 'edit.php?post_type=udb_widgets', __( 'Admin Pages', 'ultimate-dashboard' ), __( 'Admin Pages', 'ultimate-dashboard' ), apply_filters( 'udb_settings_capability', 'manage_options' ), 'edit.php?post_type=udb_admin_page' );

	}

	/**
	 * Hightlight submenu page.
	 *
	 * @param string $submenu_file The submenu file.
	 * @param string $parent_file The parent menu file.
	 *
	 * @return string The submenu file.
	 */
	public function highlight_submenu( $submenu_file, $parent_file ) {

		global $current_screen;
		global $parent_file;

		if (
			in_array(
				$current_screen->base,
				array(
					'post',
					'edit',
				),
				true
			) && 'udb_admin_page' === $current_screen->post_type
		) {

			$parent_file  = 'edit.php?post_type=udb_widgets';
			$submenu_file = 'edit.php?post_type=udb_admin_page';

		}

		return $submenu_file;

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
	 * Register metaboxes.
	 */
	public function register_meta_boxes() {

		add_meta_box( 'udb-active-status-metabox', __( 'Active', 'ultimate-dashboard' ), array( $this, 'active_status_metabox' ), 'udb_admin_page', 'side', 'high' );
		add_meta_box( 'udb-content-type-metabox', __( 'Content Type', 'ultimate-dashboard' ), array( $this, 'content_type_metabox' ), 'udb_admin_page', 'side', 'high' );

		if ( ! udb_is_pro_active() ) {
			add_meta_box( 'udb-pro-link-metabox', __( 'PRO Features Available', 'ultimate-dashboard' ), array( $this, 'pro_link_metabox' ), 'udb_admin_page', 'side', 'high' );
		}

		add_meta_box( 'udb-menu-metabox', __( 'Menu Attributes', 'ultimate-dashboard' ), array( $this, 'menu_metabox' ), 'udb_admin_page', 'side' );
		add_meta_box( 'udb-html-metabox', __( 'HTML', 'ultimate-dashboard' ), array( $this, 'html_metabox' ), 'udb_admin_page', 'normal', 'high' );
		add_meta_box( 'udb-display-metabox', __( 'Display Options', 'ultimate-dashboard' ), array( $this, 'display_metabox' ), 'udb_admin_page', 'normal' );
		add_meta_box( 'udb-advanced-metabox', __( 'Advanced', 'ultimate-dashboard' ), array( $this, 'advanced_metabox' ), 'udb_admin_page', 'normal' );

	}

	/**
	 * Active status metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function active_status_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/active-status.php';
		$metabox( $post );

	}

	/**
	 * Content type metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function content_type_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/content-type.php';
		$metabox( $post );

	}

	/**
	 * PRO link metabox.
	 */
	public function pro_link_metabox() {

		$metabox = require __DIR__ . '/templates/metaboxes/pro-link.php';
		$metabox();

	}

	/**
	 * Menu metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function menu_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/menu.php';
		$metabox( $this, $post );

	}

	/**
	 * HTML content metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function html_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/html.php';
		$metabox( $post );

	}

	/**
	 * Display options metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function display_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/display.php';
		$metabox( $post );

	}

	/**
	 * Advanced metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function advanced_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/advanced.php';
		$metabox( $post );

	}

	/**
	 * Save admin page's postmeta data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_post( $post_id ) {

		$save_widget = require __DIR__ . '/inc/save-post.php';
		$save_widget( $this, $post_id );

	}

	/**
	 * Ajax handler of admin page's active status change.
	 */
	public function change_active_status() {

		$ajax = require __DIR__ . '/ajax/change-active-status.php';
		$ajax();

	}

}
