<?php
/**
 * Login redirect page template.
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
						<p class="subtitle"><?php esc_html_e( 'White label & rebrand your WordPress installation.', 'ultimate-dashboard' ); ?></p>
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

				<?php settings_fields( 'udb-login-redirect-group' ); ?>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-login-url-settings' ); ?>
				</div>

				<div class="heatbox udb-login-redirect-heatbox">
					<?php do_settings_sections( 'udb-login-redirect-settings' ); ?>
				</div>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</div>

		</form>

	</div>

	<?php
};
