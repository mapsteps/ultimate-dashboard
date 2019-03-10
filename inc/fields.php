<?php
/**
 * Fields
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Icon Metabox
 */
function udb_main_metabox() {
	add_meta_box( 'ultimate-dashboard-main-metabox', __( 'Ultimate Dashboard', 'ultimate-dashboard' ), 'udb_main_meta_callback', 'udb_widgets', 'normal', 'high' );
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
 */
function udb_priority_meta_callback( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_priority_nonce' );

	$udb_stored_meta = get_post_meta( $post->ID, 'udb_priority_key', true );

	if( !$udb_stored_meta ) { 
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
	
<?php }

/**
 * Position Metabox Callback
 */
function udb_position_meta_callback( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'udb_position_nonce' );

	$udb_stored_meta = get_post_meta( $post->ID, 'udb_position_key', true );

	if( !$udb_stored_meta ) {
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


<?php }

/**
 * Main Metabox Callback
 */
function udb_main_meta_callback() {

	$nav_tabs = array(
		'<a class="nav-tab udb-icon-tab nav-tab-active" href="#">'. __( 'Icon Widget', 'ultimate-dashboard' ) .'</a>',
	);

	$nav_tabs = apply_filters( 'udb_extend_tab_nav', $nav_tabs );

	?>

	<div id="udb-metabox-tab-nav">
		<h2 class="nav-tab-wrapper">
			<?php foreach ( $nav_tabs as $nav_tab ) {
				echo $nav_tab;
			} ?>
		</h2>
	</div>

	<?php do_action( 'udb_metabox_widgets' ); ?>

	<div style="clear: both;"></div>

<?php }

/**
 * Save postmeta data function
 */
function udb_save_postmeta( $post_id ) {

	$is_autosave             = wp_is_post_autosave( $post_id );
	$is_revision             = wp_is_post_revision( $post_id );
	$is_valid_metabox_nonce  = ( isset( $_POST['udb_metabox_nonce'] ) && wp_verify_nonce( $_POST['udb_metabox_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false'; // phpcs:ignore -- is ok
	$is_valid_position_nonce = ( isset( $_POST['udb_position_nonce'] ) && wp_verify_nonce( $_POST['udb_position_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false'; // phpcs:ignore -- is ok
	$is_valid_priority_nonce = ( isset( $_POST['udb_priority_nonce'] ) && wp_verify_nonce( $_POST['udb_priority_nonce'], basename( __FILE__ ) ) ) ? 'true' : 'false'; // phpcs:ignore -- is ok

	if ( $is_autosave || $is_revision || !$is_valid_metabox_nonce || !$is_valid_position_nonce || !$is_valid_priority_nonce ) {
		return;
	}

	if ( isset( $_POST['udb_icon'] ) ) {
		update_post_meta( $post_id, 'udb_icon_key', sanitize_text_field( $_POST['udb_icon'] ) );
	}

	if ( isset( $_POST['udb_link'] ) ) {
		update_post_meta( $post_id, 'udb_link', sanitize_text_field( $_POST['udb_link'] ) );
	}

	$check = isset( $_POST['udb_link_target'] ) && $_POST['udb_link_target'] ? '_blank' : '_self';
	update_post_meta( $post_id, 'udb_link_target', $check );

	if (isset( $_POST['udb_metabox_position'] ) ) {
		update_post_meta( $post_id, 'udb_position_key', sanitize_text_field( $_POST['udb_metabox_position'] ) );
	}

	if (isset( $_POST['udb_metabox_priority'] ) ) {
		update_post_meta( $post_id, 'udb_priority_key', sanitize_text_field( $_POST['udb_metabox_priority'] ) );
	}

}
add_action( 'save_post', 'udb_save_postmeta' );