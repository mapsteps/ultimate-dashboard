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

	<div class="wrap settingstuff">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<?php settings_errors(); ?>

		<form method="post" action="options.php" class="udb-dashboard-form">

			<div class="neatbox has-bigger-heading has-subboxes is-smooth is-medium">

				<div class="left-col">

					<h2><?php _e( 'White Label', 'ultimate-dashboard' ); ?></h2>
					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/white-label.png'; ?>" alt="White Label">
							</td>
							<td>
								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </p>
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
								<div class="control switch-control is-rounded">
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

					<h2><?php _e( 'Login Customizer', 'ultimate-dashboard' ); ?></h2>
					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/login-customizer.png'; ?>" alt="Login Customizer">
							</td>
							<td>
								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </p>
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
								<div class="control switch-control is-rounded">
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

					<?php do_action( 'udb_after_modules_first_col' ); ?>

				</div>

				<div class="right-col">

					<h2><?php _e( 'Admin Pages', 'ultimate-dashboard' ); ?></h2>
					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/admin-pages.png'; ?>" alt="Admin Pages">
							</td>
							<td>
								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </p>
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
								<div class="control switch-control is-rounded">
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

					<h2><?php _e( 'Admin Menu Editor', 'ultimate-dashboard' ); ?></h2>
					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/admin-menu.png'; ?>" alt="Admin Menu Editor">
							</td>
							<td>
								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </p>
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
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_admin_menu_editor">
										<input
											type="checkbox"
											name="admin_menu_editor"
											id="udb_is_active_admin_menu_editor"
											<?php checked( (empty( $saved_modules ) || $saved_modules['admin_menu_editor'] == "true" ? 1 : 0), 1); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

				</div>
				<input type="hidden" name="udb_modules_nonce" id="udb_modules_nonce" value="<?php echo esc_attr( wp_create_nonce( 'udb_modules_nonce_action' ) ); ?>" />
			</div>

		</form>

	</div>

	<?php
};
