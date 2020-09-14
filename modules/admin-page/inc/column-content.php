<?php
/**
 * Setup column content on admin page list screen.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $module, $column, $post_id ) {

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
				$editor = $module->content()->get_content_editor( $post_id );
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

};