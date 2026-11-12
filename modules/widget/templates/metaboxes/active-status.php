<?php
/**
 * Publish metabox: the Active toggle, the save button and "Move to Trash".
 *
 * Replaces WordPress' Publish box: a widget's Draft/Published status, visibility and schedule don't
 * apply to dashboard widgets; "Active" decides whether it shows. Every save publishes the widget.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$is_new    = 'auto-draft' === $post->post_status;
	$is_active = $is_new ? 1 : (int) get_post_meta( $post->ID, 'udb_is_active', true );
	?>

	<div class="submitbox" id="submitpost">

		<div class="udb-publish-active">
			<label for="udb_is_active"><?php esc_html_e( 'Active', 'ultimate-dashboard' ); ?></label>
			<label class="toggle-switch">
				<input type="checkbox" name="udb_is_active" id="udb_is_active" value="1" <?php checked( $is_active, 1 ); ?>>
				<span class="switch-track"><span class="switch-thumb"></span></span>
			</label>
		</div>

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

	</div>

	<?php

};
