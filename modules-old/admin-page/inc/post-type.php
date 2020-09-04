<?php
/**
 * Setup admin page post type.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper;

/**
 * Register post type.
 */
function udb_admin_page_cpt() {

	// Labels.
	$labels = array(
		'name'               => _x( 'Admin Pages', 'ultimatedashboard' ),
		'singular_name'      => _x( 'Admin Page', 'ultimatedashboard' ),
		'menu_name'          => _x( 'Admin Pages', 'ultimatedashboard' ),
		'name_admin_bar'     => _x( 'Admin Page', 'ultimatedashboard' ),
		'add_new_item'       => __( 'Add Admin Page', 'ultimatedashboard' ),
		'new_item'           => __( 'New Admin Page', 'ultimatedashboard' ),
		'edit_item'          => __( 'Edit Admin Page', 'ultimatedashboard' ),
		'view_item'          => __( 'View Admin Page', 'ultimatedashboard' ),
		'all_items'          => __( 'Admin Pages', 'ultimatedashboard' ),
		'search_items'       => __( 'Search Admin Pages', 'ultimatedashboard' ),
		'not_found'          => __( 'No Admin Pages found.', 'ultimatedashboard' ),
		'not_found_in_trash' => __( 'No Admin Pages in Trash.', 'ultimatedashboard' ),
	);

	// Change capabilities so only users that can 'manage_options' are able to access the Admin Pages & settings.
	$capabilities = array(
		'edit_post'          => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'read_post'          => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'delete_post'        => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'delete_posts'       => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'edit_posts'         => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'edit_others_posts'  => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'publish_posts'      => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'read_private_posts' => apply_filters( 'udb_settings_capability', 'manage_options' ),
		'create_posts'       => apply_filters( 'udb_settings_capability', 'manage_options' ),
	);

	// Arguments.
	$args = array(
		'labels'              => $labels,
		'menu_icon'           => 'dashicons-format-gallery',
		'public'              => true,
		'exclude_from_search' => true,
		'show_in_menu'        => false,
		'show_in_rest'        => false,
		'query_var'           => false,
		'rewrite'             => false,
		'map_meta_cap'        => false,
		'capabilities'        => $capabilities,
		'has_archive'         => false,
		'hierarchical'        => false,
		'supports'            => array( 'title', 'editor' ),
	);

	register_post_type( 'udb_admin_page', $args );

}
add_action( 'init', 'udb_admin_page_cpt' );

/**
 * Update messages.
 *
 * @param array $messages The messages.
 */
function udb_admin_page_updated_post_messages( $messages ) {

	$post = get_post();

	$messages['udb_admin_page'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Admin Page updated.', 'ultimatedashboard' ),
		2  => __( 'Custom field updated.', 'ultimatedashboard' ),
		3  => __( 'Custom field deleted.', 'ultimatedashboard' ),
		4  => __( 'Admin Page updated.', 'ultimatedashboard' ),
		// translators: %s: Date and time of the revision.
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Admin Page restored to revision from %s', 'ultimatedashboard' ), wp_post_revision_title( absint( sanitize_text_field( $_GET['revision'] ) ), false ) ) : false,
		6  => __( 'Admin Page published.', 'ultimatedashboard' ),
		7  => __( 'Admin Page saved.', 'ultimatedashboard' ),
		8  => __( 'Admin Page submitted.', 'ultimatedashboard' ),
		9  => sprintf(
			// translators: Publish box date format, see http://php.net/date for more info.
			__( 'Admin Page scheduled for: <strong>%1$s</strong>.', 'ultimatedashboard' ),
			date_i18n( __( 'M j, Y @ G:i', 'ultimatedashboard' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Admin Page draft updated.', 'ultimatedashboard' ),
	);

	return $messages;

}
add_filter( 'post_updated_messages', 'udb_admin_page_updated_post_messages' );

/**
 * Setup widget columns.
 *
 * @param array $columns Array of columns.
 */
function udb_admin_page_posts_columns( $columns ) {

	$columns = array(
		'cb'          => '<input type="checkbox" />',
		'icon'        => __( 'Menu Icon', 'ultimatedashboard' ),
		'title'       => __( 'Page Name', 'ultimatedashboard' ),
		'type'        => __( 'Content Type', 'ultimatedashboard' ),
		'parent_menu' => __( 'Parent Menu', 'ultimatedashboard' ),
		'roles'       => __( 'User Roles', 'ultimatedashboard' ),
		'is_active'   => __( 'Active', 'ultimatedashboard' ),
	);

	return $columns;

}
add_filter( 'manage_udb_admin_page_posts_columns', 'udb_admin_page_posts_columns' );

/**
 * Admin page columns.
 *
 * @param string  $column The column name/key.
 * @param integer $post_id The post ID.
 */
function udb_admin_page_posts_custom_column( $column, $post_id ) {

	switch ( $column ) {

		case 'is_active':
			$is_active = get_post_meta( $post_id, 'udb_is_active', true );
			?>

			<div class="field">
				<div class="control switch-control is-rounded is-small">
					<label for="udb_is_active_<?php echo esc_attr( $post_id ); ?>">
						<input
							type="checkbox"
							name="udb_is_active"
							id="udb_is_active_<?php echo esc_attr( $post_id ); ?>"
							value="1"
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_admin_page_' . $post_id . '_change_active_status' ) ); ?>"
							data-post-id="<?php echo esc_attr( $post_id ); ?>"
							<?php checked( $is_active, 1 ); ?>
						>

						<span class="switch"></span>
					</label>
				</div>
			</div>

			<?php
			break;

		case 'parent_menu':
			$menu_type   = get_post_meta( $post_id, 'udb_menu_type', true );
			$parent_menu = __( 'None', 'ultimatedashboard' );

			if ( 'submenu' === $menu_type ) {
				$parent_slug = get_post_meta( $post_id, 'udb_menu_parent', true );

				foreach ( $GLOBALS['menu'] as $menu ) {
					if ( $menu[2] === $parent_slug ) {
						$parent_menu = $menu[0];
						break;
					}
				}
			}

			echo esc_html( $parent_menu );
			break;

		case 'type':
			$type = get_post_meta( $post_id, 'udb_content_type', true );

			if ( 'html' === $type ) {
				$text = __( 'HTML', 'ultimatedashboard' );
			} else {
				$content_helper = new Content_Helper();

				$editor = $content_helper->get_content_editor( $post_id );
				$editor = 'block' === $editor || 'normal' === $editor ? 'default' : $editor;
				$suffix = 'default' === $editor ? __( 'Editor', 'utlimatedashboard' ) : __( 'Builder', 'utlimatedashboard' );
				$suffix = 'elementor' === $editor ? '' : $suffix;
				$text   = wp_sprintf(
					// translators: %1$s: is the text prefix, %2$s: is the editor or builder name, %3$s: is the text suffix.
					__( '%1$s %2$s %3$s', 'ultimatedashboard' ),
					$editor,
					$suffix
				);
			}

			echo esc_html( ucwords( $text ) );
			break;

		case 'roles':
			_e( 'All', 'ultimatedashboard' );
			break;

		case 'icon':
			$icon_class = get_post_meta( $post_id, 'udb_menu_icon', true );
			$icon_class = $icon_class ? $icon_class : 'dashicons dashicons-no is-empty';

			echo '<i class="' . esc_attr( $icon_class ) . '"></i>';
			break;

	}

}
add_action( 'manage_udb_admin_page_posts_custom_column', 'udb_admin_page_posts_custom_column', 10, 2 );

/**
 * Remove some metaboxes from admin page editing.
 */
function udb_admin_page_remove_metaboxes() {
	remove_meta_box( 'wpbf', 'udb_admin_page', 'side' );
	remove_meta_box( 'wpbf_sidebar', 'udb_admin_page', 'side' );
	remove_meta_box( 'revisionsdiv', 'udb_admin_page', 'normal' );
	remove_meta_box( 'slugdiv', 'udb_admin_page', 'normal' );
	remove_meta_box( 'pageparentdiv', 'udb_admin_page', 'side' );
	remove_meta_box( 'wpbf_header', 'udb_admin_page', 'side' );
	remove_meta_box( 'postcustom', 'udb_admin_page', 'normal' );
}
add_action( 'do_meta_boxes', 'udb_admin_page_remove_metaboxes' );

/**
 * Force default template for admin page.
 *
 * @param string $template_path The template path.
 * @return string The template path.
 */
function udb_admin_page_include_template( $template_path ) {
	if ( 'udb_admin_page' !== get_post_type() ) {
		return $template_path;
	}

	return ULTIMATE_DASHBOARD_PLUGIN_DIR . '/modules/admin-page/templates/edit-page.php';
}
add_filter( 'template_include', 'udb_admin_page_include_template', 1 );
