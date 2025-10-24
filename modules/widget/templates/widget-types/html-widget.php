<?php
/**
 * HTML widget.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	global $post;

	$content = get_post_meta( $post->ID, 'udb_html', true );

	?>

	<div data-type="html">

		<div class="postbox">
			<div class="postbox-header">
				<h2><?php esc_html_e( 'HTML', 'ultimate-dashboard' ); ?></h2>
			</div>
			<div class="inside">
				<textarea class="widefat textarea" name="udb_html"><?php echo wp_kses_post( wp_unslash( $content ) ); ?></textarea>
			</div>
		</div>

	</div>

	<?php

};
