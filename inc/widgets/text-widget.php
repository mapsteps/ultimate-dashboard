<?php
/**
 * Text widget.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Text widget.
 */
function udb_text_widget() {

	global $post;

	$content  = get_post_meta( $post->ID, 'udb_content', true );
	$editor   = 'udb_content';
	$settings = array(
		'media_buttons' => false,
		'editor_height' => 300,
		'teeny'         => true,
	);
	?>

	<div data-type="text">

		<div class="subbox">
			<h2><?php _e( 'Content', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<div class="input-control">
					<?php wp_editor( $content, $editor, $settings ); ?>
				</div>
			</div>
		</div><!-- /subbox -->

		<div class="subbox">
			<h2><?php _e( 'Fixed Height', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<div class="input-control">
					<input type="text" name="udb_content_height" placeholder="200px" value="<?php echo esc_attr( $content ? $content : '' ); ?>">
				</div>
			</div>
		</div><!-- /subbox -->

	</div>

	<?php

}
add_action( 'udb_metabox_widgets', 'udb_text_widget' );
