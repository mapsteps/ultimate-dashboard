<?php
/**
 * HTML content metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$html_content = get_post_meta( $post->ID, 'udb_html_content', true );
	?>

	<div class="postbox-content">
		<div class="field html-editor-field">
			<div class="control">
				<textarea class="widefat textarea udb-html-editor" name="udb_html_content"><?php echo wp_unslash( $html_content ); ?></textarea>
			</div>
		</div>
	</div>

	<?php

};
