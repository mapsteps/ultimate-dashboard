<?php
/**
 * Settings.
 *
 * @package Ultimate Dashboard PRO
 *
 * @subpackage Ultimate Dashboard PRO Branding
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Settings.
 */
function udb_branding_settings() {

	// Settings group.
	register_setting( 'udb-branding-settings-group', 'udb_branding' );

	// Settings sections (detailed, general).
	add_settings_section( 'udb-branding-detailed-section', __( 'WordPress Admin', 'ultimate-dashboard' ), '', 'udb-detailed-branding' );
	add_settings_section( 'udb-branding-general-section', __( 'Misc', 'ultimate-dashboard' ), '', 'udb-general-branding' );

	// Detailed section fields.
	add_settings_field( 'udb-branding-enable-field', __( 'Enable', 'ultimate-dashboard' ), 'udb_branding_enable_callback', 'udb-detailed-branding', 'udb-branding-detailed-section' );
	add_settings_field( 'udb-branding-layout-field', __( 'Layout', 'ultimate-dashboard' ), 'udb_branding_layout_callback', 'udb-detailed-branding', 'udb-branding-detailed-section' );
	add_settings_field( 'udb-branding-accent-color-field', __( 'Accent Color', 'ultimate-dashboard' ), 'udb_branding_accent_color_callback', 'udb-detailed-branding', 'udb-branding-detailed-section' );
	add_settings_field( 'udb-branding-admin-bar-logo-image-field', __( 'Admin Bar Logo', 'ultimate-dashboard' ), 'udb_branding_admin_bar_logo_callback', 'udb-detailed-branding', 'udb-branding-detailed-section' );
	add_settings_field( 'udb-branding-admin-bar-logo-url-field', __( 'Admin Bar Logo URL', 'ultimate-dashboard' ), 'udb_branding_admin_bar_logo_url_callback', 'udb-detailed-branding', 'udb-branding-detailed-section' );

	// General section fields.
	add_settings_field( 'udb-branding-footer-text-field', __( 'Footer Text', 'ultimate-dashboard' ), 'udb_branding_footer_text_callback', 'udb-general-branding', 'udb-branding-general-section' );
	add_settings_field( 'udb-branding-version-text-field', __( 'Version Text', 'ultimate-dashboard' ), 'udb_branding_version_text_callback', 'udb-general-branding', 'udb-branding-general-section' );

}
add_action( 'admin_init', 'udb_branding_settings' );

/**
 * Activate branding callback.
 */
function udb_branding_enable_callback() {

	$branding   = get_option( 'udb_branding' );
	$is_checked = isset( $branding['enabled'] ) ? 0 : 0;
	?>

	<div class="field setting-field">
		<label for="udb_branding[enabled]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_branding[enabled]" id="udb_branding[enabled]" value="1" <?php checked( $is_checked, 1 ); ?> disabled />
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Layout callback.
 */
function udb_branding_layout_callback() {

	$branding = get_option( 'udb_branding' );

	if ( ! isset( $branding['layout'] ) ) {
		$layout = 'default';
	} else {
		$layout = $branding['layout'];
	}

	echo '<select name="udb_branding[layout]" disabled>';

	?>

	<option value="default" <?php selected( $layout, 'default' ); ?>>Default</option>

	<option value="modern" <?php selected( $layout, 'modern' ); ?>>Modern</option>

	<?php

	echo '</select>';

}

/**
 * Accent color callback.
 */
function udb_branding_accent_color_callback() {

	$branding = get_option( 'udb_branding' );

	if ( ! isset( $branding['accent_color'] ) ) {
		$accent_color = '#0073AA';
	} else {
		$accent_color = $branding['accent_color'];
	}

	echo '<input type="text" name="udb_branding[accent_color]" value="' . esc_attr( $accent_color ) . '" class="udb-accent-color-branding-field" disabled />';

}

/**
 * Admin bar logo callback.
 */
function udb_branding_admin_bar_logo_callback() {

	$branding = get_option( 'udb_branding' );

	$is_checked = isset( $branding['remove_admin_bar_logo'] ) ? absint( $branding['remove_admin_bar_logo'] ) : 0;

	if ( ! isset( $branding['admin_bar_logo_image'] ) ) {
		$admin_bar_logo = false;
	} else {
		$admin_bar_logo = $branding['admin_bar_logo_image'];
	}

	if ( function_exists( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	} else {
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
	}

	?>

	<input class="all-options udb-branding-admin-bar-logo-url" type="text" name="udb_branding[admin_bar_logo_image]" value="<?php echo esc_url( $admin_bar_logo ); ?>" disabled />
	<a href="javascript:void(0)" class="udb-branding-admin-bar-logo-upload button-secondary button-disabled disabled"><?php _e( 'Add or Upload File', 'ultimate-dashboard' ); ?></a>

	<div class="field setting-field" style="margin-top: 10px;">
		<label for="udb_branding[remove_admin_bar_logo]" class="label checkbox-label">
			<?php _e( 'Remove Admin Bar logo', 'ultimate-dashboard' ); ?>
			<input type="checkbox" name="udb_branding[remove_admin_bar_logo]" id="udb_branding[remove_admin_bar_logo]" value="1" <?php checked( $is_checked, 1 ); ?> disabled />
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Admin bar logo url callback.
 */
function udb_branding_admin_bar_logo_url_callback() {

	$branding = get_option( 'udb_branding' );

	if ( ! isset( $branding['admin_bar_logo_url'] ) ) {
		$admin_bar_logo_url = false;
	} else {
		$admin_bar_logo_url = $branding['admin_bar_logo_url'];
	}

	echo '<input type="url" name="udb_branding[admin_bar_logo_url]" class="regular-text" value="' . esc_attr( $admin_bar_logo_url ) . '" disabled />';

}

/**
 * Footer text callback.
 */
function udb_branding_footer_text_callback() {

	$branding = get_option( 'udb_branding' );

	if ( ! isset( $branding['footer_text'] ) ) {
		$footer_text = false;
	} else {
		$footer_text = $branding['footer_text'];
	}

	echo '<input type="text" name="udb_branding[footer_text]" class="all-options" value="' . esc_attr( $footer_text ) . '" />';

}

/**
 * Version text callback.
 */
function udb_branding_version_text_callback() {

	$branding = get_option( 'udb_branding' );

	if ( ! isset( $branding['version_text'] ) ) {
		$version_text = false;
	} else {
		$version_text = $branding['version_text'];
	}

	echo '<input type="text" name="udb_branding[version_text]" class="all-options" value="' . esc_attr( $version_text ) . '" />';

}
