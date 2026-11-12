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
						<h1 class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</h1>
						<p class="subtitle"><?php esc_html_e( 'Change the login URL & redirect users after login.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="">
					</div>

				</div>

			</div>

		</div>

		<form method="post" action="options.php">

			<div class="heatbox-container heatbox-container-center">

				<hr class="wp-header-end">

				<?php settings_errors(); ?>

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
