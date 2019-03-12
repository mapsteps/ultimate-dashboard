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
function udb_text_widget() { ?>

	<?php global $post; ?>

	<div class="udb-metabox-wrapper udb-text-wrapper">

		<div class="udb-metabox-section">

			<h3><?php _e( 'Text', 'ultimate-dashboard' ); ?></h3>

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

		<div class="udb-metabox-section">

			<h3><?php _e( 'Size', 'ultimate-dashboard' ); ?></h3>

			<?php $udb_stored_meta = get_post_meta( $post->ID, 'udb_content_height', true ); ?>

			<table class="form-table">
				<tbody>
					<tr>
						<th>
							<label for="udb-content-size"><?php _e( 'Fixed Widget Height', 'ultimate-dashboard' ); ?><br>
								<span class="description"><?php _e( 'Example: 200px', 'ultimate-dashboard' ); ?></span>
							</label>
						</th>
						<td>
							<input id="udb-content-size" type="text" name="udb_content_height" value="<?php echo $udb_stored_meta ? $udb_stored_meta : false; ?>" />
						</td>
					</tr>
				</tbody>
			</table>

		</div>

	</div>

	<?php
}

add_action( 'udb_metabox_widgets', 'udb_text_widget' );
