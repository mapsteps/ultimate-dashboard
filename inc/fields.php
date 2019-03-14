<?php
/**
 * Fields
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Icon Metabox
 */
function udb_main_metabox() {
	add_meta_box( 'udb-main-metabox', __( 'Ultimate Dashboard', 'ultimate-dashboard' ), 'udb_main_meta_callback', 'udb_widgets', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'udb_main_metabox' );

/**
 * Position Metabox
 */
function udb_position_metabox() {
	add_meta_box( 'ultimate-dashboard-position-metabox', __( 'Position', 'ultimate-dashboard' ), 'udb_position_meta_callback', 'udb_widgets', 'side' );
}
add_action( 'add_meta_boxes', 'udb_position_metabox' );

/**
 * Priority Metabox
 */
function udb_priority_metabox() {
	add_meta_box( 'ultimate-dashboard-priority-metabox', __( 'Priority', 'ultimate-dashboard' ), 'udb_priority_meta_callback', 'udb_widgets', 'side' );
}
add_action( 'add_meta_boxes', 'udb_priority_metabox' );

/**
 * Priority Metabox Callback
 *
 * @param object $post The post object.
 */
function udb_priority_meta_callback( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_priority_nonce' );

	$udb_stored_meta = get_post_meta( $post->ID, 'udb_priority_key', true );

	if ( ! $udb_stored_meta ) {
		$udb_stored_meta = 'default';
	}

	?>

	<div>
		<input id="udb-metabox-priority-default" type="radio" name="udb_metabox_priority" value="default" <?php checked( $udb_stored_meta, 'default' ); ?> />
		<label for="udb-metabox-priority-default"><?php _e( 'Default', 'ultimate-dashboard' ); ?></label>
	</div>

	<div>
		<input id="udb-metabox-priority-low" type="radio" name="udb_metabox_priority" value="low" <?php checked( $udb_stored_meta, 'low' ); ?> />
		<label for="udb-metabox-priority-low"><?php _e( 'Low', 'ultimate-dashboard' ); ?></label>
	</div>

	<div>
		<input id="udb-metabox-priority-high" type="radio" name="udb_metabox_priority" value="high" <?php checked( $udb_stored_meta, 'high' ); ?> />
		<label for="udb-metabox-priority-high"><?php _e( 'High', 'ultimate-dashboard' ); ?></label>
	</div>

	<?php
}

/**
 * Position Metabox Callback
 *
 * @param object $post The post object.
 */
function udb_position_meta_callback( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_position_nonce' );

	$udb_stored_meta = get_post_meta( $post->ID, 'udb_position_key', true );

	if ( ! $udb_stored_meta ) {
		$udb_stored_meta = 'normal';
	}

	?>

	<div>
		<input id="udb-metabox-content-normal" type="radio" name="udb_metabox_position" value="normal" <?php checked( $udb_stored_meta, 'normal' ); ?> />
		<label for="udb-metabox-content-normal"><?php _e( 'Left column', 'ultimate-dashboard' ); ?></label>
	</div>

	<div>
		<input id="udb-metabox-content-side" type="radio" name="udb_metabox_position" value="side" <?php checked( $udb_stored_meta, 'side' ); ?> />
		<label for="udb-metabox-content-side"><?php _e( 'Right column', 'ultimate-dashboard' ); ?></label>
	</div>


	<?php
}

/**
 * Main Metabox Callback
 */
function udb_main_meta_callback() {
	?>

	<div class="neatbox">
		<div class="field">
			<div class="label-control">
				<label for="udb_widget_type"><?php echo esc_html_e( 'Widget Type', 'utimate-dashboard' ); ?></label>
			</div>
			<div class="input-control">
				<select name="udb_widget_type">
					<option value="icon"><?php echo esc_html_e( 'Icon Widget', 'ultimate-dashboard' ); ?></option>
					<option value="text"><?php echo esc_html_e( 'Text Widget', 'ultimate-dashboard' ); ?></option>
					<option value="html"><?php echo esc_html_e( 'HTML Widget', 'ultimate-dashboard' ); ?></option>
				</select>
			</div>
		</div>

		<div class="widget-fields">
			<?php do_action( 'udb_metabox_widgets' ); ?>
		</div>
	</div>

	<?php
}

/**
 * Save postmeta data function
 *
 * @param int $post_id The post ID.
 */
function udb_save_postmeta( $post_id ) {

	$is_autosave             = wp_is_post_autosave( $post_id );
	$is_revision             = wp_is_post_revision( $post_id );
	$is_valid_metabox_nonce  = ( isset( $_POST['udb_metabox_nonce'] ) && wp_verify_nonce( $_POST['udb_metabox_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false'; // phpcs:ignore -- is ok
	$is_valid_position_nonce = ( isset( $_POST['udb_position_nonce'] ) && wp_verify_nonce( $_POST['udb_position_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false'; // phpcs:ignore -- is ok
	$is_valid_priority_nonce = ( isset( $_POST['udb_priority_nonce'] ) && wp_verify_nonce( $_POST['udb_priority_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false'; // phpcs:ignore -- is ok

	if ( $is_autosave || $is_revision || ! $is_valid_metabox_nonce || ! $is_valid_position_nonce || ! $is_valid_priority_nonce ) {
		return;
	}

	if ( isset( $_POST['udb_widget_type'] ) ) {
		update_post_meta( $post_id, 'udb_widget_type', sanitize_text_field( $_POST['udb_widget_type'] ) );
	}

	if ( isset( $_POST['udb_icon'] ) ) {
		update_post_meta( $post_id, 'udb_icon_key', sanitize_text_field( $_POST['udb_icon'] ) );
	}

	if ( isset( $_POST['udb_link'] ) ) {
		update_post_meta( $post_id, 'udb_link', sanitize_text_field( $_POST['udb_link'] ) );
	}

	$check = isset( $_POST['udb_link_target'] ) && $_POST['udb_link_target'] ? '_blank' : '_self';
	update_post_meta( $post_id, 'udb_link_target', $check );

	if ( isset( $_POST['udb_metabox_position'] ) ) {
		update_post_meta( $post_id, 'udb_position_key', sanitize_text_field( $_POST['udb_metabox_position'] ) );
	}

	if ( isset( $_POST['udb_metabox_priority'] ) ) {
		update_post_meta( $post_id, 'udb_priority_key', sanitize_text_field( $_POST['udb_metabox_priority'] ) );
	}

	if ( isset( $_POST['udb_tooltip'] ) ) {
		update_post_meta( $post_id, 'udb_tooltip', sanitize_text_field( $_POST['udb_tooltip'] ) );
	}

	if ( isset( $_POST['udb_content'] ) ) {
		update_post_meta( $post_id, 'udb_content', $_POST['udb_content'] );
	}

	if ( isset( $_POST['udb_content_height'] ) ) {
		update_post_meta( $post_id, 'udb_content_height', $_POST['udb_content_height'] );
	}

	if ( isset( $_POST['udb_html'] ) ) {
		update_post_meta( $post_id, 'udb_html', $_POST['udb_html'] );
	}

}
add_action( 'save_post', 'udb_save_postmeta' );
