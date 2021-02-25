<?php
/**
 * Settings page template.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap udb-settings-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php _e( 'Ultimate Dashboard', 'ultimate-dashboard' ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php _e( 'The #1 plugin to customize your WordPress dashboard.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

			<h1 style="display: none;"></h1>

			<form method="post" action="options.php" class="udb-settings-form">

				<?php settings_fields( 'udb-settings-group' ); ?>

				<div class="heatbox is-grouped">
					<?php do_settings_sections( 'udb-widget-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-widget-styling-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-general-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-advanced-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-misc-settings' ); ?>
				</div>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</form>

		</div>

	</div>

	<?php
};
