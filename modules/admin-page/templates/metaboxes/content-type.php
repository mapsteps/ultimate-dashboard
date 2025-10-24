<?php
/**
 * Content type metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$content_type = get_post_meta( $post->ID, 'udb_content_type', true );
	$content_type = $content_type ? $content_type : 'builder';

	?>

	<select name="udb_content_type" id="udb_content_type">
		<option value="builder" <?php selected( $content_type, 'builder' ); ?>><?php esc_html_e( 'Default Editor', 'ultimate-dashboard' ); ?></option>
		<option value="html" <?php selected( $content_type, 'html' ); ?>><?php esc_html_e( 'HTML Editor', 'ultimate-dashboard' ); ?></option>
	</select>

	<?php
};
