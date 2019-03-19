<?php
/**
 * Text Widget
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Text Widget
 */
function udb_text_widget() {
	global $post;
	?>
	<div data-type="text">

		<div class="subbox">
			<h2><?php echo esc_html_e( 'Content', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_content', true );
				?>
				<div class="input-control">
					<?php
					$content  = get_post_meta( $post->ID, 'udb_content', true );
					$editor   = 'udb_content';
					$settings = array(
						'media_buttons' => false,
						'editor_height' => 300,
						'teeny'         => true,
					);
					wp_editor( $content, $editor, $settings );
					?>
				</div>
			</div>
		</div><!-- /subbox -->

		<div class="subbox">
			<h2><?php echo esc_html_e( 'Fixed Height', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_content_height', true );
				?>
				<div class="input-control">
					<input type="text" name="udb_content_height" placeholder="200px" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>">
				</div>
			</div>
		</div><!-- /subbox -->

	</div>
	<?php
}

add_action( 'udb_metabox_widgets', 'udb_text_widget' );
