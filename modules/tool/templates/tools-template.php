<?php
/**
 * Tools template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap udb-tools-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php esc_html_e( 'Export & import the Ultimate Dashboard settings.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

			<h1 style="display: none;"></h1>

			<?php settings_errors(); ?>

			<div class="udb-tools-container">

				<div class="heatbox">
					<form method="post" action="options.php">
					<?php
					settings_fields( 'udb-export-group' );
					do_settings_sections( 'ultimate-dashboard-export' );
					submit_button( __( 'Export File', 'ultimate-dashboard' ) );
					?>
					</form>
				</div>

				<div class="heatbox">
					<form method="post" action="options.php" enctype="multipart/form-data">
					<?php
					settings_fields( 'udb-import-group' );
					do_settings_sections( 'ultimate-dashboard-import' );
					submit_button( __( 'Import File', 'ultimate-dashboard' ) );
					?>
					</form>
				</div>

			</div>

		</div>

	</div>

	<?php
};
