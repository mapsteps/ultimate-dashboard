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
						<h1 class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</h1>
						<p class="subtitle"><?php esc_html_e( 'Export & import the Ultimate Dashboard settings.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

			<hr class="wp-header-end">

			<?php settings_errors(); ?>

			<div class="heatbox-columns">

				<div class="heatbox">
					<h2><?php esc_html_e( 'Export', 'ultimate-dashboard' ); ?></h2>
					<form class="heatbox-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="udb_tools_export">
						<?php
						wp_nonce_field( 'udb_tools_export', 'udb_tools_nonce' );
						Udb\Tool\Tool_Module::get_instance()->render_export_field();
						?>
						<p>
							<button type="submit" class="button button-primary"><?php esc_html_e( 'Export File', 'ultimate-dashboard' ); ?></button>
						</p>
					</form>
				</div>

				<div class="heatbox">
					<h2><?php esc_html_e( 'Import', 'ultimate-dashboard' ); ?></h2>
					<form class="heatbox-content" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
						<input type="hidden" name="action" value="udb_tools_import">
						<?php
						wp_nonce_field( 'udb_tools_import', 'udb_tools_nonce' );
						Udb\Tool\Tool_Module::get_instance()->render_import_field();
						?>
						<p>
							<button type="submit" class="button button-primary"><?php esc_html_e( 'Import File', 'ultimate-dashboard' ); ?></button>
						</p>
					</form>
				</div>

			</div>

		</div>

	</div>

	<?php

};
