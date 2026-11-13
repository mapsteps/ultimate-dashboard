<?php
/**
 * Setup column content on widget list screen.
 *
 * @package Ultimate_Dashboard
 */

use Udb\Helpers\Icon_Helper;

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $column, $post_id ) {
	switch ( $column ) {

		case 'type':
			$widget_type = get_post_meta( $post_id, 'udb_widget_type', true );
			// Preventing edge case when widget_type is empty.
			if ( ! $widget_type ) {
				do_action( 'udb_compat_widget_type', $post_id );
			} else {
				$column_content = '';
				$icon_helper    = new Icon_Helper();

				if ( 'html' === $widget_type ) {
					$column_content = __( 'HTML', 'ultimate-dashboard' );
				} elseif ( 'text' === $widget_type ) {
					$column_content = __( 'Text', 'ultimate-dashboard' );
				} elseif ( 'icon' === $widget_type ) {
					$column_content = '<span class="udb-list-icon">' . $icon_helper->html( get_post_meta( $post_id, 'udb_icon_key', true ) ) . '</span>' . __( 'Icon', 'ultimate-dashboard' );
				}

				$column_content = apply_filters( 'udb_widget_list_type_column_content', $column_content, $post_id, $widget_type );

				echo wp_kses( $column_content, array_merge( wp_kses_allowed_html( 'post' ), $icon_helper->allowed_svg_tags() ) );
			}
			break;

		case 'roles':
			$allowed_roles = __( 'All', 'ultimate-dashboard' );
			$allowed_roles = apply_filters( 'udb_widget_list_roles_column_content', $allowed_roles, $post_id );

			echo esc_html( ucwords( $allowed_roles ) );
			break;

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
						data-action="udb_widget_change_active_status"
						data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_widget_' . $post_id . '_change_active_status' ) ); ?>"
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

	}
};
