<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap udb-branding-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php _e( 'White label & rebrand your WordPress installation.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<form method="post" action="options.php">

			<div class="heatbox-container heatbox-container-center">

				<h1 style="display: none;"></h1>

				<?php settings_fields( 'udb-branding-group' ); ?>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-branding-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php
					do_settings_sections( 'udb-admin-colors-settings' );

					if ( udb_is_pro_active() ) {
						?>

						<div class="heatbox-overlay"></div>

						<?php
					}
					?>
				</div>

				<div class="heatbox">
					<?php
					do_settings_sections( 'udb-admin-logo-settings' );

					if ( udb_is_pro_active() ) {
						?>

						<div class="heatbox-overlay"></div>

						<?php
					}
					?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-branding-misc-settings' ); ?>
				</div>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</div>

		</form>

	</div>

	<?php
};
