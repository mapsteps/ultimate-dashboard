<?php
/**
 * Export field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$modules = array(
		'modules_manager'  => __( 'Module Manager', 'ultimate-dashboard' ),
		'settings'         => __( 'Settings', 'ultimate-dashboard' ),
		'widgets'          => __( 'Dashboard Widgets', 'ultimate-dashboard' ),
		'branding'         => __( 'White Label Settings', 'ultimate-dashboard' ),
		'login_customizer' => __( 'Login Customizer Settings', 'ultimate-dashboard' ),
		'login_redirect'   => __( 'Login Redirect Settings', 'ultimate-dashboard' ),
		'admin_pages'      => __( 'Admin Pages', 'ultimate-dashboard' ),
	);
	?>

	<p><?php esc_html_e( 'Select the settings you would like to export and use the button below to generate & export a .json file.', 'ultimate-dashboard' ); ?></p>

	<div class="setting-fields">
		<?php foreach ( $modules as $value => $label ) : ?>
			<div class="setting-field">
				<label class="label checkbox-label">
					<?php echo esc_html( $label ); ?>
					<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="<?php echo esc_attr( $value ); ?>" checked>
					<div class="indicator"></div>
				</label>
			</div>
		<?php endforeach; ?>
	</div>

	<?php // PRO adds its own checkboxes here. ?>
	<div class="udb-export-more">
		<?php do_action( 'udb_export_fields' ); ?>
	</div>

	<p>
		<button type="button" class="button-link udb-select-all-modules" data-select="<?php esc_attr_e( 'Select All', 'ultimate-dashboard' ); ?>" data-unselect="<?php esc_attr_e( 'Unselect All', 'ultimate-dashboard' ); ?>"><?php esc_html_e( 'Unselect All', 'ultimate-dashboard' ); ?></button>
	</p>

	<?php

};
