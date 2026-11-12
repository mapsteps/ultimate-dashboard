<?php
/**
 * Text widget.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	global $post;

	$content        = get_post_meta( $post->ID, 'udb_content', true );
	$content_height = get_post_meta( $post->ID, 'udb_content_height', true );
	$editor         = 'udb_content';
	$args           = array(
		'media_buttons' => false,
		'editor_height' => 300,
		'teeny'         => true,
	);

	?>

	<div data-type="text">
		<div class="postbox">
			<div class="postbox-header">
				<h2><?php esc_html_e( 'Text Widget', 'ultimate-dashboard' ); ?></h2>
			</div>
			<div class="inside">

				<div class="udb-metabox-field">
					<?php wp_editor( $content, $editor, $args ); ?>
				</div>

				<div class="udb-metabox-field">
					<label class="udb-field-label" for="udb_content_height"><?php esc_html_e( 'Fixed Height', 'ultimate-dashboard' ); ?></label>
					<input type="text" class="small-text" id="udb_content_height" name="udb_content_height" placeholder="200px" value="<?php echo esc_attr( $content_height ? $content_height : '' ); ?>">
				</div>

			</div>
		</div>
	</div>

	<?php

};
