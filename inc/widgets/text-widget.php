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

		<div class="field">
			<?php
			$stored_meta = get_post_meta( $post->ID, 'udb_content', true );
			?>
			<div class="label-control">
				<label for="udb_content"><?php esc_html_e( 'Content', 'ultimate-dashboard' ); ?></label>
			</div>
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

		<div class="field">
			<?php
			$stored_meta = get_post_meta( $post->ID, 'udb_content_height', true );
			?>
			<div class="label-control">
				<label for="udb_content_height"><?php esc_html_e( 'Fixed Widget Height', 'ultimate-dashboard' ); ?></label>
				<p class="description"><?php esc_html_e( 'Example: 200px', 'ultimate-dashboard' ); ?></p>
			</div>
			<div class="input-control">
				<input type="text" name="udb_content_height" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>">
			</div>
		</div>

	</div>
	<?php
}

add_action( 'udb_metabox_widgets', 'udb_text_widget' );
