<?php
/**
 * HTML widget.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

function udb_html_widget() {

	global $post;

	$content = get_post_meta( $post->ID, 'udb_html', true );

	?>

	<div data-type="html">

		<div class="subbox">
			<h2><?php _e( 'HTML', 'ultimate-dashboard' ); ?></h2>
			<div class="field">
				<div class="input-control">
					<textarea class="widefat textarea" name="udb_html"><?php echo wp_unslash( $content ); ?></textarea>
				</div>
			</div>
		</div>

	</div>

	<?php

}
add_action( 'udb_metabox_widgets', 'udb_html_widget' );
