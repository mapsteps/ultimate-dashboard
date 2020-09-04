<?php
/**
 * Setup column content on widget list screen.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $column, $post_id ) {
	switch ( $column ) {

		case 'type':
			$widget_type = get_post_meta( $post_id, 'udb_widget_type', true );
			// Preventing edge case when widget_type is empty.
			if ( ! $widget_type ) {
				do_action( 'udb_compat_widget_type', $post_id );
			} else {
				if ( 'html' === $widget_type ) {
					_e( 'HTML', 'ultimate-dashboard' );
				} elseif ( 'text' === $widget_type ) {
					_e( 'Text', 'ultimate-dashboard' );
				} elseif ( 'icon' === $widget_type ) {
					echo '<i class="' . esc_attr( get_post_meta( $post_id, 'udb_icon_key', true ) ) . '"></i>';
				}
			}
			break;

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
							data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_widget_' . $post_id . '_change_active_status' ) ); ?>"
							data-post-id="<?php echo esc_attr( $post_id ); ?>"
							<?php checked( $is_active, 1 ); ?>
						>

						<span class="switch"></span>
					</label>
				</div>
			</div>

			<?php
			break;

	}
};
