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

	// Register settings.
	register_setting( 'udb-settings-group', 'udb_settings' );
	register_setting( 'udb-settings-group', 'udb_pro_settings' );

	// Settings sections.
	add_settings_section( 'udb-remove-default-widgets', __( 'WordPress Dashboard Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard' );
	add_settings_section( 'udb-remove-3rd-party-widgets', __( 'Remove 3rd Party Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard' );
	add_settings_section( 'udb-general-settings', __( 'General', 'ultimate-dashboard' ), '', 'ultimate-dashboard' );
	add_settings_section( 'udb-advanced-settings', __( 'Advanced', 'ultimate-dashboard' ), '', 'ultimate-dashboard' );
	add_settings_section( 'udb-misc-settings', __( 'Misc', 'ultimate-dashboard' ), '', 'ultimate-dashboard' );

	// Settings fields.
	add_settings_field( 'remove-all-widgets', __( 'Remove All Widgets', 'ultimate-dashboard' ), 'udb_remove_all_widgets_callback', 'ultimate-dashboard', 'udb-remove-default-widgets' );
	add_settings_field( 'remove-individual-widgets', __( 'Remove Individual Widgets', 'ultimate-dashboard' ), 'udb_remove_single_widgets_callback', 'ultimate-dashboard', 'udb-remove-default-widgets' );
	add_settings_field( 'remove-3rd-party-widgets', __( 'Remove 3rd Party Widgets', 'ultimate-dashboard' ), 'udb_remove_3rd_party_widgets_callback', 'ultimate-dashboard', 'udb-remove-3rd-party-widgets' );
	add_settings_field( 'headline-settings', __( 'Custom Dashboard Headline', 'ultimate-dashboard' ), 'udb_headline_settings_callback', 'ultimate-dashboard', 'udb-general-settings' );
	add_settings_field( 'remove-help-tab-settings', __( 'Remove Help Tab', 'ultimate-dashboard' ), 'udb_remove_help_tab_settings_callback', 'ultimate-dashboard', 'udb-general-settings' );
	add_settings_field( 'remove-screen-options-settings', __( 'Remove Screen Options', 'ultimate-dashboard' ), 'udb_remove_screen_options_settings_callback', 'ultimate-dashboard', 'udb-general-settings' );
	add_settings_field( 'remove-admin-bar-settings', __( 'Remove Admin Bar from Frontend', 'ultimate-dashboard' ), 'udb_remove_admin_bar_settings_callback', 'ultimate-dashboard', 'udb-general-settings' );
	add_settings_field( 'custom-dashboard-css', __( 'Custom Dashboard CSS', 'ultimate-dashboard' ), 'udb_custom_dashboard_css_callback', 'ultimate-dashboard', 'udb-advanced-settings' );
	add_settings_field( 'custom-admin-css', __( 'Custom Admin CSS', 'ultimate-dashboard' ), 'udb_custom_admin_css_callback', 'ultimate-dashboard', 'udb-advanced-settings' );
	add_settings_field( 'remove-all-settings', __( 'Remove Data on Uninstall', 'ultimate-dashboard' ), 'udb_remove_all_settings_callback', 'ultimate-dashboard', 'udb-misc-settings' );

}
add_action( 'admin_init', 'udb_settings' );

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
			<?php _e( 'All', 'ultimatedashboard' ); ?>
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
				<?php _e( 'Welcome Panel', 'ultimatedashboard' ); ?> (<code>welcome_panel</code>)
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
	?>

	<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=remove_3rd_party_widgets_link&utm_campaign=udb" class="button button-primary" target="_blank">
		<?php _e( 'Get Ultimate Dashboard PRO!', 'ultimate-dashboard' ); ?>
	</a>

	<?php
}

/**
 * Change headline callback.
 */
function udb_headline_settings_callback() {

	$settings = get_option( 'udb_settings' );
	$headline = isset( $settings['dashboard_headline'] ) ? $settings['dashboard_headline'] : '';
	?>

	<input type="text" name="udb_settings[dashboard_headline]" class="all-options" value="<?php echo esc_attr( $headline ); ?>" placeholder="<?php _e( 'Dashboard', 'ultimatedashboard' ); ?>" />

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

	$udb_pro_settings = get_option( 'udb_pro_settings' );

	if ( ! isset( $udb_pro_settings['custom_css'] ) ) {
		$custom_css = false;
	} else {
		$custom_css = $udb_pro_settings['custom_css'];
	}

	?>

	<textarea id="udb-custom-dashboard-css" class="widefat textarea udb-custom-css" name="udb_pro_settings[custom_css]"><?php echo wp_unslash( $custom_css ); ?></textarea>

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

