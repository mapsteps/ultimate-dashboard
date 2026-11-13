<?php
/**
 * Setup column content on admin page list screen.
 *
 * @package Ultimate_Dashboard
 */

use Udb\AdminPage\Admin_Page_Module;
use Udb\Helpers\Icon_Helper;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Setup column content on admin page list screen.
 *
 * @param Admin_Page_Module $module The module class instance.
 * @param string            $column The column name.
 * @param int               $post_id The post ID.
 */
return function ( $module, $column, $post_id ) {

	$menu_type = get_post_meta( $post_id, 'udb_menu_type', true );

	switch ( $column ) {

		case 'is_active':
			$is_active = get_post_meta( $post_id, 'udb_is_active', true );
			?>

			<div class="heatbox-wrap status-switch" data-error="<?php esc_attr_e( 'An error occurred while saving.', 'ultimate-dashboard' ); ?>">
				<label for="udb_is_active_<?php echo esc_attr( $post_id ); ?>" class="toggle-switch">
					<input
						type="checkbox"
						name="udb_is_active"
						id="udb_is_active_<?php echo esc_attr( $post_id ); ?>"
						value="1"
						data-action="udb_admin_page_change_active_status"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_admin_page_' . $post_id . '_change_active_status' ) ); ?>"
						data-post-id="<?php echo esc_attr( $post_id ); ?>"
						<?php checked( $is_active, 1 ); ?>
					/>
					<div class="switch-track">
						<div class="switch-thumb"></div>
					</div>
					<span class="screen-reader-text"><?php echo esc_html( get_the_title( $post_id ) ); ?></span>
				</label>
				<span class="heatbox-notice is-error" role="alert" hidden></span>
			</div>
				</label>
			</div>

			<?php
			break;

		case 'parent_menu':
			$parent_menu = __( 'None', 'ultimate-dashboard' );

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
				$text = __( 'HTML', 'ultimate-dashboard' );
			} else {
				$editor = $module->content()->get_content_editor( $post_id );
				$editor = 'block' === $editor || 'default' === $editor ? 'default' : $editor;
				$suffix = 'default' === $editor ? __( 'Editor', 'ultimate-dashboard' ) : __( 'Builder', 'ultimate-dashboard' );
				$suffix = 'elementor' === $editor ? '' : $suffix;
				$text   = wp_sprintf(
				// translators: %1$s: is the text prefix, %2$s: is the editor or builder name, %3$s: is the text suffix.
					__( '%1$s %2$s %3$s', 'ultimate-dashboard' ),
					$editor,
					$suffix
				);
			}

			echo esc_html( ucwords( $text ) );
			break;

		case 'roles':
			$allowed_roles = __( 'All', 'ultimate-dashboard' );
			$allowed_roles = apply_filters( 'udb_admin_page_list_roles_column_content', $allowed_roles, $post_id );

			echo esc_html( ucwords( $allowed_roles ) );
			break;

		case 'icon':
			if ( 'submenu' === $menu_type ) {
				echo esc_html__( 'None', 'ultimate-dashboard' );
				break;
			}

			$icon = get_post_meta( $post_id, 'udb_menu_icon', true );
			$icon = $icon ? $icon : 'dashicons dashicons-no is-empty';

			// The icon HTML is built and escaped by the icon helper.
			echo '<span class="udb-list-icon">' . ( new Icon_Helper() )->html( $icon ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			break;

	}

};
