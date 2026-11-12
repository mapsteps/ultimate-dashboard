<?php
/**
 * Publish metabox.
 *
 * Classic editor: the Active toggle, the save button and "Move to Trash", instead of WordPress' Publish
 * box (Draft/Published, visibility and schedule don't apply; "Active" decides whether the page shows).
 * Block editor (PRO): only the Active toggle; the editor has its own Publish and Trash.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$is_new          = 'auto-draft' === $post->post_status;
	$is_active       = $is_new ? 1 : (int) get_post_meta( $post->ID, 'udb_is_active', true );
	$is_block_editor = function_exists( 'use_block_editor_for_post' ) && use_block_editor_for_post( $post );

	wp_nonce_field( 'udb_edit_admin_page', 'udb_nonce' );
	?>

	<div class="submitbox" id="<?php echo $is_block_editor ? 'udb-active-status' : 'submitpost'; ?>">

		<div class="udb-publish-active">
			<?php // In the block editor the box itself is titled "Active". ?>
			<label for="udb_is_active"<?php echo $is_block_editor ? ' class="screen-reader-text"' : ''; ?>><?php esc_html_e( 'Active', 'ultimate-dashboard' ); ?></label>
			<label class="toggle-switch">
				<input type="checkbox" name="udb_is_active" id="udb_is_active" value="1" <?php checked( $is_active, 1 ); ?>>
				<span class="switch-track"><span class="switch-thumb"></span></span>
			</label>
		</div>

		<?php if ( ! $is_block_editor ) : ?>
			<div id="major-publishing-actions">
				<div id="delete-action">
					<?php if ( ! $is_new && current_user_can( 'delete_post', $post->ID ) ) : ?>
						<a class="submitdelete deletion" href="<?php echo esc_url( get_delete_post_link( $post->ID ) ); ?>">
							<?php EMPTY_TRASH_DAYS ? esc_html_e( 'Move to Trash', 'ultimate-dashboard' ) : esc_html_e( 'Delete permanently', 'ultimate-dashboard' ); ?>
						</a>
					<?php endif; ?>
				</div>
				<div id="publishing-action">
					<?php if ( 'publish' === $post->post_status ) : ?>
						<?php // Like WordPress' own box: "save" keeps the status (and says "updated"), "publish" publishes. ?>
						<input type="submit" name="save" id="publish" class="button button-primary button-large" value="<?php esc_attr_e( 'Update', 'ultimate-dashboard' ); ?>">
					<?php else : ?>
						<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="<?php esc_attr_e( 'Publish', 'ultimate-dashboard' ); ?>">
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
		<?php endif; ?>

	</div>

	<?php

};
