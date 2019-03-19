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
			<h2><?php echo esc_html_e( 'Widget Content', 'utimate-dashboard' ); ?></h2>
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
			<h2><?php echo esc_html_e( 'Dimension Attributes', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_content_height', true );
				?>
				<div class="label-control">
					<label for="udb_content_height"><?php esc_html_e( 'Widget height', 'ultimate-dashboard' ); ?></label>
					<p class="description"><?php esc_html_e( 'Input fixed widget height. Example: 200px', 'ultimate-dashboard' ); ?></p>
				</div>
				<div class="input-control">
					<input type="text" name="udb_content_height" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>">
				</div>
			</div>
		</div><!-- /subbox -->

	</div>
	<?php
}

add_action( 'udb_metabox_widgets', 'udb_text_widget' );
