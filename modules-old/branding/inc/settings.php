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
	add_settings_section( 'udb-branding-detailed-section', __( 'WordPress Admin Branding', 'ultimate-dashboard' ), '', 'udb-detailed-branding' );
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

	?>

	<div class="field setting-field">
		<label class="label checkbox-label">
			&nbsp;
			<input type="checkbox" disabled />
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Layout callback.
 */
function udb_branding_layout_callback() {

	echo '<select disabled>';

	?>

	<option>Modern</option>

	<?php

	echo '</select>';

}

/**
 * Accent color callback.
 */
function udb_branding_accent_color_callback() {

	echo '<input type="text" value="#0073AA" disabled />';

}

/**
 * Admin bar logo callback.
 */
function udb_branding_admin_bar_logo_callback() {

	?>

	<input class="all-options" type="text" disabled />
	<a href="javascript:void(0)" class="button-secondary button-disabled disabled"><?php _e( 'Add or Upload File', 'ultimate-dashboard' ); ?></a>

	<div class="field setting-field" style="margin-top: 10px;">
		<label class="label checkbox-label">
			<?php _e( 'Remove Admin Bar logo', 'ultimate-dashboard' ); ?>
			<input type="checkbox" disabled />
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Admin bar logo url callback.
 */
function udb_branding_admin_bar_logo_url_callback() {

	echo '<input type="url" class="regular-text" disabled />';

	?>

	<br>

	<div class="udb-pro-settings-page-notice">

		<p><?php _e( 'This feature is available in Ultimate Dashboard PRO.' ); ?></p>

		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=white_label_link&utm_campaign=udb" class="button button-primary" target="_blank">
			<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
		</a>

	</div>

	<?php

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
