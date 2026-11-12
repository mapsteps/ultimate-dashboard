<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$is_pro = udb_is_pro_active();
	$locked = $is_pro ? '' : ' is-locked';
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
						<p class="subtitle"><?php esc_html_e( 'White label & rebrand your WordPress installation.', 'ultimate-dashboard' ); ?></p>
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

				<?php settings_fields( 'udb-branding-group' ); ?>

				<?php if ( ! $is_pro ) : ?>
					<div class="heatbox-cta primary heatbox-margin-bottom">
						<p><?php esc_html_e( 'The settings marked PRO are available in Ultimate Dashboard PRO. Try the admin colors: they preview live.', 'ultimate-dashboard' ); ?></p>
						<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=white_label_link&utm_campaign=udb" class="button button-primary" target="_blank">
							<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'ultimate-dashboard' ); ?></span>
						</a>
					</div>
				<?php endif; ?>

				<div class="heatbox<?php echo esc_attr( $locked ); ?>">
					<?php do_settings_sections( 'udb-branding-settings' ); ?>
				</div>

				<?php do_action( 'udb_after_branding_layout_metabox' ); ?>

				<div class="heatbox<?php echo esc_attr( $locked ); ?>">
					<?php do_settings_sections( 'udb-darkmode-settings' ); ?>
				</div>

				<?php do_action( 'udb_after_darkmode_metabox' ); ?>

				<?php // Not locked without PRO: the colors can be tried out, they preview live (but aren't saved). ?>
				<div class="heatbox">
					<?php
					do_settings_sections( 'udb-admin-colors-settings' );

					if ( $is_pro ) {
						?>

						<div class="heatbox-overlay"></div>

						<?php
					}
					?>
				</div>

				<?php do_action( 'udb_after_admin_colors_metabox' ); ?>

				<div class="heatbox<?php echo esc_attr( $locked ); ?>">
					<?php
					do_settings_sections( 'udb-admin-logo-settings' );

					if ( $is_pro ) {
						?>

						<div class="heatbox-overlay"></div>

						<?php
					}
					?>
				</div>

				<?php do_action( 'udb_after_admin_logo_metabox' ); ?>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-branding-misc-settings' ); ?>
				</div>

				<?php do_action( 'udb_after_branding_misc_metabox' ); ?>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</div>

		</form>

	</div>

	<?php
};
