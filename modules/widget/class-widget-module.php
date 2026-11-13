<?php
/**
 * Widget module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Widget;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Module;

/**
 * Class to setup widget module.
 */
class Widget_Module extends Base_Module {

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/widget';

	}

	/**
	 * Setup widget module.
	 */
	public function setup() {

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'post_updated_messages', array( $this, 'update_messages' ) );
		add_filter( 'manage_udb_widgets_posts_columns', array( $this, 'set_columns' ) );
		add_action( 'manage_udb_widgets_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
		add_filter( 'disable_months_dropdown', array( $this, 'disable_months_dropdown' ), 10, 2 );
		add_filter( 'bulk_actions-edit-udb_widgets', array( $this, 'bulk_actions' ) );
		add_action( 'in_admin_header', array( $this, 'list_header' ) );
		add_action( 'manage_posts_extra_tablenav', array( $this, 'add_new_button' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		add_action( 'udb_widget_metabox', array( $this, 'icon_widget' ) );
		add_action( 'udb_widget_metabox', array( $this, 'text_widget' ) );
		add_action( 'udb_widget_metabox', array( $this, 'html_widget' ) );

		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

		add_action( 'wp_ajax_udb_widget_change_active_status', array( $this, 'change_active_status' ) );

		// The module output.
		require_once __DIR__ . '/class-widget-output.php';
		Widget_Output::init();

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

		$messages['udb_widgets'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Widget updated.', 'ultimate-dashboard' ),
			2  => __( 'Custom field updated.', 'ultimate-dashboard' ),
			3  => __( 'Custom field deleted.', 'ultimate-dashboard' ),
			4  => __( 'Widget updated.', 'ultimate-dashboard' ),
			// translators: %s: Date and time of the revision.
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Widget restored to revision from %s', 'ultimate-dashboard' ), wp_post_revision_title( absint( $_GET['revision'] ), false ) ) : false, // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Same as WP core's messages, only displayed.
			6  => __( 'Widget published.', 'ultimate-dashboard' ),
			7  => __( 'Widget saved.', 'ultimate-dashboard' ),
			8  => __( 'Widget submitted.', 'ultimate-dashboard' ),
			9  => sprintf(
				/* translators: %1$s: Scheduled date and time */
				__( 'Widget scheduled for: <strong>%1$s</strong>.', 'ultimate-dashboard' ),
				// translators: Publish box date format, see http://php.net/date for more info.
				date_i18n( __( 'M j, Y @ G:i', 'ultimate-dashboard' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Widget draft updated.', 'ultimate-dashboard' ),
		);

		return $messages;

	}

	/**
	 * Setup widget list columns.
	 *
	 * @param array $columns The columns.
	 */
	public function set_columns( $columns ) {

		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Widget Title', 'ultimate-dashboard' ),
			'type'      => __( 'Widget Type', 'ultimate-dashboard' ),
			'roles'     => __( 'User Roles', 'ultimate-dashboard' ),
			'is_active' => __( 'Active', 'ultimate-dashboard' ),
		);

		return $columns;

	}

	/**
	 * Widget list column content.
	 *
	 * @param string  $column The column name/key.
	 * @param integer $post_id The post ID.
	 */
	public function column_content( $column, $post_id ) {

		$column_content = require __DIR__ . '/inc/column-content.php';
		$column_content( $column, $post_id );

	}

	/**
	 * No month filter on the widget list: it doesn't help for a handful of widgets.
	 *
	 * @param bool   $disable   Whether to disable the dropdown.
	 * @param string $post_type The post type.
	 *
	 * @return bool
	 */
	public function disable_months_dropdown( $disable, $post_type ) {

		return 'udb_widgets' === $post_type ? true : $disable;

	}

	/**
	 * Bulk actions on the widget list: only trash, restore and delete (no bulk editing).
	 *
	 * @param array $actions The bulk actions.
	 *
	 * @return array
	 */
	public function bulk_actions( $actions ) {

		return array_intersect_key( $actions, array_flip( array( 'trash', 'untrash', 'delete' ) ) );

	}

	/**
	 * "Add …" button at the right end of the row above the list (instead of next to the title).
	 *
	 * @param string $which Which row: "top" or "bottom".
	 */
	public function add_new_button( $which ) {

		$post_type = get_post_type_object( 'udb_widgets' );

		if ( 'top' !== $which || ! $this->screen()->is_widget_list() || ! current_user_can( $post_type->cap->create_posts ) ) {
			return;
		}

		printf(
			'<a href="%s" class="udb-add-new">%s</a>',
			esc_url( admin_url( 'post-new.php?post_type=udb_widgets' ) ),
			esc_html( $post_type->labels->add_new_item )
		);

	}

	/**
	 * Heatbox header above the widget list and the widget edit screen.
	 */
	public function list_header() {

		global $title;

		if ( $this->screen()->is_widget_list() ) {
			$header_title = get_post_type_object( 'udb_widgets' )->labels->name;
		} elseif ( $this->screen()->is_new_widget() || $this->screen()->is_edit_widget() ) {
			// WordPress' own screen title: Add Dashboard Widget, or Edit Dashboard Widget.
			$header_title = $title;
		} else {
			return;
		}

		$header = require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/templates/post-header.php';
		$header( $header_title, __( 'Add your own widgets to the WordPress dashboard.', 'ultimate-dashboard' ) );

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
	 * Add the icon widget.
	 */
	public function icon_widget() {

		$widget = require __DIR__ . '/templates/widget-types/icon-widget.php';
		$widget();

	}

	/**
	 * Add the text widget.
	 */
	public function text_widget() {

		$widget = require __DIR__ . '/templates/widget-types/text-widget.php';
		$widget();

	}

	/**
	 * Add the html widget.
	 */
	public function html_widget() {

		$widget = require __DIR__ . '/templates/widget-types/html-widget.php';
		$widget();

	}

	/**
	 * Register metaboxes.
	 */
	public function register_meta_boxes() {

		add_meta_box( 'udb-main-metabox', __( 'Ultimate Dashboard', 'ultimate-dashboard' ), array( $this, 'main_metabox' ), 'udb_widgets', 'normal', 'high' );
		// Our Publish box (Active toggle, save, trash) instead of WordPress'.
		remove_meta_box( 'submitdiv', 'udb_widgets', 'side' );
		add_meta_box( 'udb-active-status-metabox', __( 'Publish', 'ultimate-dashboard' ), array( $this, 'active_status_metabox' ), 'udb_widgets', 'side', 'high' );

		if ( ! udb_is_pro_active() ) {
			add_meta_box( 'udb-pro-link-metabox', __( 'PRO Features Available', 'ultimate-dashboard' ), array( $this, 'pro_link_metabox' ), 'udb_widgets', 'side' );
		}

		add_meta_box( 'udb-tags-metabox', __( 'Placeholder Tags', 'ultimate-dashboard' ), array( $this, 'placeholder_tags_metabox' ), 'udb_widgets', 'side' );
		add_meta_box( 'udb-position-metabox', __( 'Position', 'ultimate-dashboard' ), array( $this, 'position_metabox' ), 'udb_widgets', 'side' );

	}

	/**
	 * Widget type metabox.
	 */
	public function main_metabox() {

		$metabox = require __DIR__ . '/templates/metaboxes/main.php';
		$metabox();

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
	 * PRO link metabox.
	 */
	public function pro_link_metabox() {

		$metabox = require __DIR__ . '/templates/metaboxes/pro-link.php';
		$metabox();

	}

	/**
	 * Placeholder tags metabox.
	 */
	public function placeholder_tags_metabox() {

		$metabox = require __DIR__ . '/templates/metaboxes/placeholder-tags.php';
		$metabox();

	}

	/**
	 * Position metabox.
	 *
	 * @param WP_Post $post The WP_Post object.
	 */
	public function position_metabox( $post ) {

		$metabox = require __DIR__ . '/templates/metaboxes/position.php';
		$metabox( $post );

	}

	/**
	 * Save widget's postmeta data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_post( $post_id ) {

		$save_widget = require __DIR__ . '/inc/save-post.php';
		$save_widget( $post_id );

	}

	/**
	 * Ajax handler of widget's active status change.
	 */
	public function change_active_status() {

		$ajax = require __DIR__ . '/ajax/change-active-status.php';
		$ajax();

	}

}
