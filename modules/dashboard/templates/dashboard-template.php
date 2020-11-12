<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$is_white_label_active = get_option( 'udb_is_white_label_active', 1 );

	?>

	<div class="wrap settingstuff">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<?php settings_errors(); ?>

		<form method="post" action="options.php" class="udb-dashboard-form">

			<div class="neatbox has-bigger-heading has-subboxes is-smooth is-medium">

				<div class="left-col">

					<h2>White Label</h2>
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
							<td>Status: active</td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_white_label">
										<input type="checkbox" name="udb_is_active" id="udb_is_active_white_label" value="1" data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_is_active_white_label_status' ) ); ?>" <?php checked( $is_white_label_active, 1 ); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

					<h2>Login Customizer</h2>
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
							<td>Status: active</td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_white_label">
										<input type="checkbox" name="udb_is_active" id="udb_is_active_white_label" value="1" data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_is_active_white_label_status' ) ); ?>" <?php checked( $is_white_label_active, 1 ); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

				</div>

				<div class="right-col">

					<h2>Admin Pages</h2>
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
							<td>Status: active</td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_white_label">
										<input type="checkbox" name="udb_is_active" id="udb_is_active_white_label" value="1" data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_is_active_white_label_status' ) ); ?>" <?php checked( $is_white_label_active, 1 ); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

					<h2>Admin Menu Editor</h2>
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
							<td>Status: active</td>
							<td class="field">
								<div class="control switch-control is-rounded">
									<label for="udb_is_active_white_label">
										<input type="checkbox" name="udb_is_active" id="udb_is_active_white_label" value="1" data-nonce="<?php echo esc_attr( wp_create_nonce( 'udb_is_active_white_label_status' ) ); ?>" <?php checked( $is_white_label_active, 1 ); ?> >
										<span class="switch"></span>
									</label>
								</div>
							</td>
						</tr>
					</table>

				</div>


			</div>

		</form>

	</div>

	<?php
};
