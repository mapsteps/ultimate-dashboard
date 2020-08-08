<?php
/**
 * Settings.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Register setting.
 */
function udb_settings() {

	// Settings group.
	register_setting( 'udb-settings-group', 'udb_settings' );

	// Widget sections.
	add_settings_section( 'udb-widgets-section', __( 'WordPress Dashboard Widgets', 'ultimate-dashboard' ), '', 'udb-widgets-page' );
	add_settings_section( 'udb-3rd-party-section', __( '3rd Party Widgets', 'ultimate-dashboard' ), '', 'udb-widgets-page' );

	// Other sections (styling, general, advanced, misc).
	add_settings_section( 'udb-styling-section', __( 'Widget Styling', 'ultimate-dashboard' ), '', 'udb-general-page' );
	add_settings_section( 'udb-general-section', __( 'General', 'ultimate-dashboard' ), '', 'udb-general-page' );
	add_settings_section( 'udb-advanced-section', __( 'Advanced', 'ultimate-dashboard' ), '', 'udb-general-page' );
	add_settings_section( 'udb-misc-section', __( 'Misc', 'ultimate-dashboard' ), '', 'udb-general-page' );

	// Widget section fields.
	add_settings_field( 'remove-all-widgets', __( 'Remove All Widgets', 'ultimate-dashboard' ), 'udb_remove_all_widgets_callback', 'udb-widgets-page', 'udb-widgets-section' );
	add_settings_field( 'remove-individual-widgets', __( 'Remove Individual Widgets', 'ultimate-dashboard' ), 'udb_remove_single_widgets_callback', 'udb-widgets-page', 'udb-widgets-section' );
	add_settings_field( 'remove-3rd-party-widgets', __( 'Remove 3rd Party Widgets', 'ultimate-dashboard' ), 'udb_remove_3rd_party_widgets_callback', 'udb-widgets-page', 'udb-3rd-party-section' );

	// Styling section fields.
	add_settings_field( 'udb-icon-color-field', __( 'Icon/Text Color', 'ultimate-dashboard' ), 'udb_icon_color_settings_callback', 'udb-general-page', 'udb-styling-section' );
	add_settings_field( 'udb-headline-color-field', __( 'Headline Color', 'ultimate-dashboard' ), 'udb_headline_color_settings_callback', 'udb-general-page', 'udb-styling-section' );

	// General section fields.
	add_settings_field( 'remove-help-tab-settings', __( 'Remove Help Tab', 'ultimate-dashboard' ), 'udb_remove_help_tab_settings_callback', 'udb-general-page', 'udb-general-section' );
	add_settings_field( 'remove-screen-options-settings', __( 'Remove Screen Options Tab', 'ultimate-dashboard' ), 'udb_remove_screen_options_settings_callback', 'udb-general-page', 'udb-general-section' );
	add_settings_field( 'remove-admin-bar-settings', __( 'Remove Admin Bar from Frontend', 'ultimate-dashboard' ), 'udb_remove_admin_bar_settings_callback', 'udb-general-page', 'udb-general-section' );
	add_settings_field( 'headline-settings', __( 'Custom Dashboard Headline', 'ultimate-dashboard' ), 'udb_headline_text_settings_callback', 'udb-general-page', 'udb-general-section' );

	// Advanced section fields.
	add_settings_field( 'custom-dashboard-css', __( 'Custom Dashboard CSS', 'ultimate-dashboard' ), 'udb_custom_dashboard_css_callback', 'udb-general-page', 'udb-advanced-section' );
	add_settings_field( 'custom-admin-css', __( 'Custom Admin CSS', 'ultimate-dashboard' ), 'udb_custom_admin_css_callback', 'udb-general-page', 'udb-advanced-section' );

	// Misc section fields.
	$remove_fa_description = '<p class="description" style="font-weight: 400;">' . __( 'Use only if your icons are not displayed correctly.', 'ultimatedashboard' ) . '</p>';

	add_settings_field( 'remove_font_awesome', __( 'Remove Font Awesome', 'ultimate-dashboard' ) . $remove_fa_description, 'udb_remove_font_awesome_callback', 'udb-general-page', 'udb-misc-section' );
	add_settings_field( 'remove-all-settings', __( 'Remove Data on Uninstall', 'ultimate-dashboard' ), 'udb_remove_all_settings_callback', 'udb-general-page', 'udb-misc-section' );

}
add_action( 'admin_init', 'udb_settings' );

/**
 * Remove Font Awesome callback.
 */
function udb_remove_font_awesome_callback() {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_font_awesome'] ) ? 1 : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove_font_awesome]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_settings[remove_font_awesome]" id="udb_settings[remove_font_awesome]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Remove on uninstall callback.
 */
function udb_remove_all_settings_callback() {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove-on-uninstall'] ) ? 1 : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove-on-uninstall]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_settings[remove-on-uninstall]" id="udb_settings[remove-on-uninstall]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Remove all widgets callback.
 */
function udb_remove_all_widgets_callback() {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove-all'] ) ? absint( $settings['remove-all'] ) : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove-all]" class="label checkbox-label">
			<?php _e( 'All', 'ultimate-dashboard' ); ?>
			<input type="checkbox" name="udb_settings[remove-all]" id="udb_settings[remove-all]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Remove individual widgets callback.
 */
function udb_remove_single_widgets_callback() {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['welcome_panel'] ) ? 1 : 0;
	?>

	<div class="setting-fields is-gapless">

		<div class="field setting-field">
			<label for="udb_settings[welcome_panel]" class="label checkbox-label">
				<?php _e( 'Welcome Panel', 'ultimate-dashboard' ); ?> (<code>welcome_panel</code>)
				<input type="checkbox" name="udb_settings[welcome_panel]" id="udb_settings[welcome_panel]" value="1" <?php checked( $is_checked, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<?php
		$widgets = udb_get_default_widgets();

		foreach ( $widgets as $id => $widget ) {

			$is_checked = isset( $settings[ $id ] ) ? 1 : 0;
			?>

			<div class="field setting-field">
				<label for="udb_settings[<?php echo esc_attr( $id ); ?>]" class="label checkbox-label">
					<?php echo esc_attr( $widget['title_stripped'] ); ?> (<code><?php echo esc_attr( $id ); ?></code>)
					<input type="checkbox" name="udb_settings[<?php echo esc_attr( $id ); ?>]" id="udb_settings[<?php echo esc_attr( $id ); ?>]" value="1" <?php checked( $is_checked, 1 ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php

		}
		?>

	</div>

	<?php

}

/**
 * Remove 3rd party widgets callback.
 */
function udb_remove_3rd_party_widgets_callback() {

	$widgets = udb_get_third_party_widgets();

	if ( empty( $widgets ) ) {
		_e( 'No 3rd Party Widgets available.', 'ultimate-dashboard' );
	}
	?>

	<div class="setting-fields udb-pro-fields is-gapless">

		<?php

		foreach ( $widgets as $id => $widget ) {

			?>

			<div class="field setting-field">
				<label class="label checkbox-label">
					<?php echo esc_attr( $widget['title_stripped'] ); ?> (<code><?php echo esc_attr( $id ); ?></code>)
					<input type="checkbox" disabled>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
		}

		?>

	</div>

	<div class="udb-pro-settings-page-notice">

		<p><?php _e( 'This feature is available in Ultimate Dashboard PRO.' ); ?></p>

		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=remove_3rd_party_widgets_link&utm_campaign=udb" class="button button-primary" target="_blank">
			<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
		</a>

	</div>

	<?php
}

/**
 * Icon color callback.
 */
function udb_icon_color_settings_callback() {

	$settings = get_option( 'udb_settings' );

	if ( ! isset( $settings['icon_color'] ) ) {
		$accent_color = 0;
	} else {
		$accent_color = $settings['icon_color'];
	}

	echo '<input type="text" name="udb_settings[icon_color]" value="' . esc_attr( $accent_color ) . '" class="udb-color-settings-field udb-widget-color-settings-field" data-default="#555555" />';

}

/**
 * Headline color callback.
 */
function udb_headline_color_settings_callback() {

	$settings = get_option( 'udb_settings' );

	if ( ! isset( $settings['headline_color'] ) ) {
		$accent_color = 0;
	} else {
		$accent_color = $settings['headline_color'];
	}

	echo '<input type="text" name="udb_settings[headline_color]" value="' . esc_attr( $accent_color ) . '" class="udb-color-settings-field udb-headline-color-settings-field" data-default="#23282d" />';

}

/**
 * Change headline callback.
 */
function udb_headline_text_settings_callback() {

	$settings = get_option( 'udb_settings' );
	$headline = isset( $settings['dashboard_headline'] ) ? $settings['dashboard_headline'] : '';
	?>

	<input type="text" name="udb_settings[dashboard_headline]" class="all-options" value="<?php echo esc_attr( $headline ); ?>" placeholder="<?php _e( 'Dashboard', 'ultimate-dashboard' ); ?>" />

	<?php

}

/**
 * Remove help tab callback.
 */
function udb_remove_help_tab_settings_callback() {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_help_tab'] ) ? absint( $settings['remove_help_tab'] ) : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove_help_tab]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_settings[remove_help_tab]" id="udb_settings[remove_help_tab]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Remove screen options callback.
 */
function udb_remove_screen_options_settings_callback() {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_screen_options'] ) ? absint( $settings['remove_screen_options'] ) : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove_screen_options]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_settings[remove_screen_options]" id="udb_settings[remove_screen_options]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Remove admin bar callback.
 */
function udb_remove_admin_bar_settings_callback() {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['remove_admin_bar'] ) ? absint( $settings['remove_admin_bar'] ) : 0;
	?>

	<div class="field setting-field">
		<label for="udb_settings[remove_admin_bar]" class="label checkbox-label">
			&nbsp;
			<input type="checkbox" name="udb_settings[remove_admin_bar]" id="udb_settings[remove_admin_bar]" value="1" <?php checked( $is_checked, 1 ); ?>>
			<div class="indicator"></div>
		</label>
	</div>

	<?php

}

/**
 * Custom dashboard css callback.
 */
function udb_custom_dashboard_css_callback() {

	$settings = get_option( 'udb_settings' );

	if ( ! isset( $settings['custom_css'] ) ) {
		$custom_css = false;
	} else {
		$custom_css = $settings['custom_css'];
	}

	?>

	<textarea id="udb-custom-dashboard-css" class="widefat textarea udb-custom-css" name="udb_settings[custom_css]"><?php echo wp_unslash( $custom_css ); ?></textarea>

	<?php

}

/**
 * Custom admin css callback.
 */
function udb_custom_admin_css_callback() {

	$settings   = get_option( 'udb_settings' );
	$custom_css = isset( $settings['custom_admin_css'] ) ? $settings['custom_admin_css'] : '';

	?>

	<textarea id="udb-custom-admin-css" class="widefat textarea udb-custom-css" name="udb_settings[custom_admin_css]"><?php echo wp_unslash( $custom_css ); ?></textarea>

	<?php

}

