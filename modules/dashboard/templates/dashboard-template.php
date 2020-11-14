<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

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
						<tr class="status">
							<td><?php _e('Status: active', 'ultimate-dashboard'); ?></td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_white_label">
										<input
											type="checkbox"
											name="white_label"
											id="udb_is_active_white_label"
											<?php checked( self::get_module_prop('white_label') ); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

					<h2><?php _e('Login Customizer', 'ultimate-dashboard'); ?></h2>
					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/login-customizer.png'; ?>" alt="Login Customizer">
							</td>
							<td>
								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </p>
							</td>
						</tr>
						<tr class="status">
							<td><?php _e('Status: active', 'ultimate-dashboard'); ?></td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_login_customizer">
										<input
											type="checkbox"
											name="login_customizer"
											id="udb_is_active_login_customizer"
											<?php checked( self::get_module_prop('login_customizer') ); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

				</div>

				<div class="right-col">

					<h2><?php _e('Admin Pages', 'ultimate-dashboard'); ?></h2>
					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/admin-pages.png'; ?>" alt="Admin Pages">
							</td>
							<td>
								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </p>
							</td>
						</tr>
						<tr class="status">
							<td><?php _e('Status: active', 'ultimate-dashboard' ); ?></td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_admin_pages">
										<input
											type="checkbox"
											name="admin_pages"
											id="udb_is_active_admin_pages"
											<?php checked( self::get_module_prop('admin_pages') ); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

					<h2><?php _e('Admin Menu Editor', 'ultimate-dashboard' ); ?></h2>
					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/dashboard/assets/img/admin-menu.png'; ?>" alt="Admin Menu Editor">
							</td>
							<td>
								<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. </p>
							</td>
						</tr>
						<tr class="status">
							<td><?php _e('Status: active', 'ultimate-dashboard'); ?></td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_admin_menu_editor">
										<input
											type="checkbox"
											name="admin_menu_editor"
											id="udb_is_active_admin_menu_editor"
											<?php checked( self::get_module_prop('admin_menu_editor') ); ?> >
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
