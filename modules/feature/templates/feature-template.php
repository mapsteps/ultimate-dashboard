<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
use \Udb\Setup;

return function () {

	$saved_modules = Setup::saved_modules();

	?>

	<div class="wrap heatbox-wrap">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form method="post" action="options.php" class="udb-dashboard-form">

			<div class="heatbox">

				<h2><?php _e( 'White Label', 'ultimate-dashboard' ); ?></h2>
				<table class="form-table">
					<tr>
						<td>
							<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/white-label.png'; ?>" alt="White Label">
						</td>
						<td>
							<p>
								This is some text.
							</p>
						</td>
					</tr>
					<tr class="status-wrap">
						<td>
							<div class="status">
								<p><?php _e( 'Status: ', 'ultimate-dashboard' ); ?></p>
								<p class="status-code" data-active-text="<?php _e( 'Active', 'ultimate-dashboard' ); ?>" data-inactive-text="<?php _e( 'Inactive', 'ultimate-dashboard' ); ?>">
									<?php echo empty( $saved_modules ) || $saved_modules['white_label'] == "true" ? '<span class="active">' . __( 'Active', 'ultimate-dashboard' ) . '</span>' : '<span class="inactive">' . __( 'Inactive', 'ultimate-dashboard' ) . '</span>'; ?>
								</p>
							</div>
						</td>
						<td class="field">
							<div class="switch-control is-rounded">
								<label for="udb_is_active_white_label">
									<input
										type="checkbox"
										name="white_label"
										id="udb_is_active_white_label"
										<?php checked( empty( $saved_modules ) || $saved_modules['white_label'] == "true" ); ?> >
									<span class="switch"></span>
								</label>
							</div>
						</td>
					</tr>
				</table>

			</div>

			<div class="heatbox">

				<h2><?php _e( 'Login Customizer', 'ultimate-dashboard' ); ?></h2>
				<table class="form-table">
					<tr>
						<td>
							<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/login-customizer.png'; ?>" alt="Login Customizer">
						</td>
						<td>
							<p>
								This is some text.
							</p>
						</td>
					</tr>
					<tr class="status-wrap">
						<td>
							<div class="status">
								<p><?php _e( 'Status: ', 'ultimate-dashboard' ); ?></p>
								<p class="status-code" data-active-text="<?php _e( 'Active', 'ultimate-dashboard' ); ?>" data-inactive-text="<?php _e( 'Inactive', 'ultimate-dashboard' ); ?>">
									<?php echo empty( $saved_modules ) || $saved_modules['login_customizer'] == "true" ? '<span class="active">' . __( 'Active', 'ultimate-dashboard' ) . '</span>' : '<span class="inactive">' . __( 'Inactive', 'ultimate-dashboard' ) . '</span>'; ?>
								</p>
							</div>
						</td>
						<td class="field">
							<div class="switch-control is-rounded">
								<label for="udb_is_active_login_customizer">
									<input
										type="checkbox"
										name="login_customizer"
										id="udb_is_active_login_customizer"
										<?php checked( empty( $saved_modules ) || $saved_modules['login_customizer'] == "true" ); ?> >
									<span class="switch"></span>
								</label>
							</div>
						</td>
					</tr>
				</table>

			</div>

			<div class="heatbox">

				<h2><?php _e( 'Admin Pages', 'ultimate-dashboard' ); ?></h2>
				<table class="form-table">
					<tr>
						<td>
							<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/admin-pages.png'; ?>" alt="Admin Pages">
						</td>
						<td>
							<p>
								This is some text.
							</p>
						</td>
					</tr>
					<tr class="status-wrap">
						<td>
							<div class="status">
								<p><?php _e( 'Status: ', 'ultimate-dashboard' ); ?></p>
								<p class="status-code" data-active-text="<?php _e( 'Active', 'ultimate-dashboard' ); ?>" data-inactive-text="<?php _e( 'Inactive', 'ultimate-dashboard' ); ?>">
									<?php echo empty( $saved_modules ) || $saved_modules['admin_pages'] == "true" ? '<span class="active">' . __( 'Active', 'ultimate-dashboard' ) . '</span>' : '<span class="inactive">' . __( 'Inactive', 'ultimate-dashboard' ) . '</span>'; ?>
								</p>
							</div>
						</td>
						<td class="field">
							<div class="switch-control is-rounded">
								<label for="udb_is_active_admin_pages">
									<input
										type="checkbox"
										name="admin_pages"
										id="udb_is_active_admin_pages"
										<?php checked( empty( $saved_modules ) || $saved_modules['admin_pages'] == "true" ); ?> >
									<span class="switch"></span>
								</label>
							</div>
						</td>
					</tr>
				</table>

			</div>

			<div class="heatbox">

				<h2><?php _e( 'Admin Menu Editor', 'ultimate-dashboard' ); ?></h2>
				<table class="form-table">
					<tr>
						<td>
							<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/admin-menu.png'; ?>" alt="Admin Menu Editor">
						</td>
						<td>
							<p>
								This is some text.
							</p>
						</td>
					</tr>
					<tr class="status-wrap">
						<td>
							<div class="status">
								<p><?php _e( 'Status: ', 'ultimate-dashboard' ); ?></p>
								<p class="status-code" data-active-text="<?php _e( 'Active', 'ultimate-dashboard' ); ?>" data-inactive-text="<?php _e( 'Inactive', 'ultimate-dashboard' ); ?>">
									<?php echo empty( $saved_modules ) || $saved_modules['admin_menu_editor'] == "true" ? '<span class="active">' . __( 'Active', 'ultimate-dashboard' ) . '</span>' : '<span class="inactive">' . __( 'Inactive', 'ultimate-dashboard' ) . '</span>'; ?>
								</p>
							</div>
						</td>

						<td class="field">
							<div class="switch-control is-rounded">
								<label for="udb_is_active_admin_menu_editor">
									<input
										type="checkbox"
										name="admin_menu_editor"
										id="udb_is_active_admin_menu_editor"
										<?php checked( empty( $saved_modules ) || $saved_modules['admin_menu_editor'] == "true" ); ?> >
									<span class="switch"></span>
								</label>
							</div>
						</td>
					</tr>
				</table>

				<input type="hidden" name="udb_module_nonce" id="udb_module_nonce" value="<?php echo esc_attr( wp_create_nonce( 'udb_module_nonce_action' ) ); ?>" />

			</div>

		</form>

	</div>

	<?php
};
