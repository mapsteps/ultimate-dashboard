<?php
/**
 * Fields.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Icon metabox.
 */
function udb_main_metabox() {
	add_meta_box( 'udb-main-metabox', __( 'Ultimate Dashboard', 'ultimate-dashboard' ), 'udb_main_meta_callback', 'udb_widgets', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'udb_main_metabox' );

/**
 * Pro metabox.
 */
function udb_pro_metabox() {
	add_meta_box( 'udb-pro-metabox', __( 'PRO Features Available', 'ultimate-dashboard' ), 'udb_pro_meta_callback', 'udb_widgets', 'side' );
}
add_action( 'add_meta_boxes', 'udb_pro_metabox' );

/**
 * Position metabox.
 */
function udb_position_metabox() {
	add_meta_box( 'udb-position-metabox', __( 'Position', 'ultimate-dashboard' ), 'udb_position_meta_callback', 'udb_widgets', 'side' );
}
add_action( 'add_meta_boxes', 'udb_position_metabox' );

/**
 * Priority metabox.
 */
function udb_priority_metabox() {
	add_meta_box( 'udb-priority-metabox', __( 'Priority', 'ultimate-dashboard' ), 'udb_priority_meta_callback', 'udb_widgets', 'side' );
}
add_action( 'add_meta_boxes', 'udb_priority_metabox' );

/**
 * Main metabox callback.
 */
function udb_main_meta_callback() {

	global $post;

	$udb_widget_types = array(
		'icon' => __( 'Icon Widget', 'ultimate-dashboard' ),
		'text' => __( 'Text Widget', 'ultimate-dashboard' ),
		'html' => __( 'HTML Widget', 'ultimate-dashboard' ),
	);

	$udb_widget_types = apply_filters( 'udb_widget_types', $udb_widget_types );
	$stored_meta      = get_post_meta( $post->ID, 'udb_widget_type', true );

	?>

	<div class="neatbox is-smooth has-bigger-heading has-subboxes">
		<div class="subbox">
			<h2><?php _e( 'Widget Type', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<div class="input-control">
					<select name="udb_widget_type">
						<?php
						foreach ( $udb_widget_types as $value => $text ) {
							?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $stored_meta ); ?>><?php echo esc_html( $text ); ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="widget-fields">
			<?php do_action( 'udb_metabox_widgets' ); ?>
		</div>
	</div>

	<?php

}

/**
 * "PRO Features" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_pro_meta_callback( $post ) {

	?>

	<div class="postbox-content">
		<ul class="udb-pro-metabox-content">
			<li><?php _e( 'Video Widgets', 'ultimate-dashboard' ); ?></li>
			<li><?php _e( 'Contact Form Widgets', 'ultimate-dashboard' ); ?></li>
			<li><?php _e( 'Restrict Widgets to specific Users or User Roles', 'ultimate-dashboard' ); ?></li>
			<li><?php _e( 'Create a Custom Dashboard with <strong>Elementor</strong> or <strong>Beaver Builder</strong>', 'ultimate-dashboard' ); ?></li>
		</ul>

		<a style="width: 100%; text-align: center;" href="https://ultimatedashboard.io/docs-category/widgets/?utm_source=plugin&utm_medium=edit_widget_page&utm_campaign=udb" target="_blank" class="button button-primary button-large">
			<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
		</a>
	</div>

	<?php
}

/**
 * Priority metabox callback.
 *
 * @param object $post The post object.
 */
function udb_priority_meta_callback( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_priority_nonce' );

	$saved_meta = get_post_meta( $post->ID, 'udb_priority_key', true );

	if ( ! $saved_meta ) {
		$saved_meta = 'default';
	}

	?>

	<div class="neatbox">
		<div class="field radio-field">
			<div class="input-control">
				<ul>
					<li>
						<label>
							<input type="radio" name="udb_metabox_priority" value="default" <?php checked( $saved_meta, 'default' ); ?> />
							<?php _e( 'Default', 'ultimate-dashboard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="udb_metabox_priority" value="low" <?php checked( $saved_meta, 'low' ); ?> />
							<?php _e( 'Low', 'ultimate-dashboard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="udb_metabox_priority" value="high" <?php checked( $saved_meta, 'high' ); ?> />
							<?php _e( 'High', 'ultimate-dashboard' ); ?>
						</label>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<?php

}

/**
 * Position metabox callback.
 *
 * @param object $post The post object.
 */
function udb_position_meta_callback( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_position_nonce' );

	$saved_meta = get_post_meta( $post->ID, 'udb_position_key', true );

	if ( ! $saved_meta ) {
		$saved_meta = 'normal';
	}

	?>

	<div class="neatbox">
		<div class="field radio-field">
			<div class="input-control">
				<ul>
					<li>
						<label>
							<input type="radio" name="udb_metabox_position" value="normal" <?php checked( $saved_meta, 'normal' ); ?> />
							<?php _e( 'Left column', 'ultimate-dashboard' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="udb_metabox_position" value="side" <?php checked( $saved_meta, 'side' ); ?> />
							<?php _e( 'Right column', 'ultimate-dashboard' ); ?>
						</label>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<?php

}

/**
 * Save postmeta data.
 *
 * @param int $post_id The post ID.
 */
function udb_save_postmeta( $post_id ) {

	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );

	$is_valid_metabox_nonce  = ( isset( $_POST['udb_metabox_nonce'] ) && wp_verify_nonce( $_POST['udb_metabox_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';
	$is_valid_position_nonce = ( isset( $_POST['udb_position_nonce'] ) && wp_verify_nonce( $_POST['udb_position_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';
	$is_valid_priority_nonce = ( isset( $_POST['udb_priority_nonce'] ) && wp_verify_nonce( $_POST['udb_priority_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false';

	if ( $is_autosave || $is_revision || ! $is_valid_metabox_nonce || ! $is_valid_position_nonce || ! $is_valid_priority_nonce ) {
		return;
	}

	if ( isset( $_POST['udb_widget_type'] ) ) {
		update_post_meta( $post_id, 'udb_widget_type', sanitize_text_field( $_POST['udb_widget_type'] ) );
	}

	// Icon widget.
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

	// Text widget.
	if ( isset( $_POST['udb_content'] ) ) {
		update_post_meta( $post_id, 'udb_content', wp_kses_post( $_POST['udb_content'] ) );
	}

	if ( isset( $_POST['udb_content_height'] ) ) {
		update_post_meta( $post_id, 'udb_content_height', sanitize_text_field( $_POST['udb_content_height'] ) );
	}

	// HTML widget.
	if ( isset( $_POST['udb_html'] ) ) {
		update_post_meta( $post_id, 'udb_html', wp_kses_post( $_POST['udb_html'] ) );
	}

	// User defined widget.
	do_action( 'udb_save_widget', $post_id );

}
add_action( 'save_post', 'udb_save_postmeta' );
