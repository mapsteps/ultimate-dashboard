<?php
/**
 * Setup column content on admin page list screen.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module, $column, $post_id ) {

	$menu_type = get_post_meta( $post_id, 'udb_menu_type', true );

	switch ( $column ) {

		case 'is_active':
			$is_active = get_post_meta( $post_id, 'udb_is_active', true );
			?>

			<div class="switch-control is-rounded is-small">
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
				$suffix = 'default' === $editor ? __( 'Editor', 'utlimate-dashboard' ) : __( 'Builder', 'utlimate-dashboard' );
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

			echo ucwords( $allowed_roles );
			break;

		case 'icon':
			$icon_class = get_post_meta( $post_id, 'udb_menu_icon', true );
			$icon_class = $icon_class ? $icon_class : 'dashicons dashicons-no is-empty';

			echo ( 'submenu' === $menu_type ? __( 'None', 'ultimate-dashboard' ) : '<i class="' . esc_attr( $icon_class ) . '"></i>' );
			break;

	}

};
