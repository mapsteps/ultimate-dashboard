<?php
/**
 * Wizard page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Setup;
use Udb\Helpers\Widget_Helper;
use Udb\Helpers\Admin_Bar_Helper;

return function () {

	$udb_core      = new Setup();
	$saved_modules = $udb_core->saved_modules();

	$settings                 = get_option( 'udb_settings' );
	$welcome_panel_is_checked = isset( $settings['welcome_panel'] ) ? 1 : 0;
	$remove_all_is_checked    = isset( $settings['remove-all'] ) ? 1 : 0;

	$login_redirect = get_option( 'udb_login_redirect' );
	$login_slug     = isset( $login_redirect['login_url_slug'] ) ? trim( $login_redirect['login_url_slug'], '/' ) : '';

	$general_settings = array(
		array(
			'name'  => 'remove_help_tab',
			'title' => __( 'Remove Help Tab', 'ultimate-dashboard' ),
		),
		array(
			'name'  => 'remove_screen_options',
			'title' => __( 'Remove Screen Options Tab', 'ultimate-dashboard' ),
		),
		array(
			'name'  => 'remove_admin_bar',
			'title' => __( 'Remove Admin Bar from Frontend', 'ultimate-dashboard' ),
		),
	);

	$modules = array(
		array(
			'module' => 'login_customizer',
			'title'  => __( 'Login Customizer', 'ultimate-dashboard' ),
		),
		array(
			'module' => 'white_label',
			'title'  => __( 'White Label', 'ultimate-dashboard' ),
		),
		array(
			'module' => 'login_redirect',
			'title'  => __( 'Login Redirect', 'ultimate-dashboard' ),
		),
		array(
			'module' => 'admin_pages',
			'title'  => __( 'Admin Pages', 'ultimate-dashboard' ),
		),
		array(
			'module' => 'admin_menu_editor',
			'title'  => __( 'Admin Menu Editor', 'ultimate-dashboard' ),
		),
		array(
			'module' => 'admin_bar_editor',
			'title'  => __( 'Admin Bar Editor', 'ultimate-dashboard' ),
		),
	);

	$widget_helper = new Widget_Helper();
	$widgets       = $widget_helper->get_default();
	?>

	<div class="wrap heatbox-wrap udb-onboarding-wizard-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div style="width: 80%">
						<span class="title">
							<?php esc_html_e( 'Welcome to Ultimate Dashboard', 'ultimate-dashboard' ); ?>
						</span>
						<p class="subtitle">
							<?php esc_html_e( 'Complete the Setup Wizard and get started with Ultimate Dashboard in less than a minute.', 'ultimate-dashboard' ); ?>
						</p>
					</div>

					<div style="width: 20%">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">
			<h1 style="display: none;"></h1>

			<div class="heatbox onboarding-wizard-heatbox">

				<div class="udb-onboarding-wizard-slides">
					<div class="udb-onboarding-wizard-slide udb-modules-slide">

						<header>
							<h2>
								<?php esc_html_e( 'Features', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php esc_html_e( 'Select the features you\'d like to enable. You can change them later in the "Modules" tab.', 'ultimate-dashboard' ); ?>
							</p>
						</header>


						<ul class="udb-modules">
					
							<?php foreach ( $modules as $module ) : ?>
								<?php
								$slug          = $module['module'];
								$title         = $module['title'];
								$disabled_attr = '';
								$is_checked    = true;

								if ( isset( $saved_modules[ $slug ] ) && 'false' === $saved_modules[ $slug ] ) {
									$is_checked = false;

									if ( 'login_customizer' === $slug ) {
										$disabled_attr = '';
									}
								}
								?>

								<li>
									<div class="module-text">
										<h3>
											<label for="udb_modules__<?php echo esc_attr( $slug ); ?>">
												<?php echo esc_html( $title ); ?>
											</label>
										</h3>
									</div>
									<div class="module-toggle">
										<label for="udb_modules__<?php echo esc_attr( $slug ); ?>" class="label checkbox-label">
											<input
												type="checkbox"
												name="udb_modules[<?php echo esc_attr( $slug ); ?>]"
												id="udb_modules__<?php echo esc_attr( $slug ); ?>"
												value="1"
												<?php checked( $is_checked, 1 ); ?>
												<?php echo esc_attr( $disabled_attr ); ?>
											>

											<div class="indicator"></div>
										</label>
									</div>
								</li>

							<?php endforeach; ?>
						</ul>

					</div>

					<div class="udb-onboarding-wizard-slide udb-widgets-slide">

						<header>
							<h2>
								<?php esc_html_e( 'Dashboard Widgets', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php esc_html_e( 'Let\'s clean up your dashboard. Remove some or all of the default WordPress widgets.', 'ultimate-dashboard' ); ?>
							</p>
						</header>
 
						<ul class="udb-modules">
							<li>
								<div class="module-text">
									<h3>
										<label for="udb_widgets__remove-all">
											<?php esc_html_e( 'Remove all Dashboard Widgets', 'ultimate-dashboard' ); ?>
										</label>
									</h3>
								</div>
								<div class="widget-toggle">
									<label for="udb_widgets__remove-all" class="label checkbox-label">
										<input
											type="checkbox"
											name="udb_widgets[remove-all]"
											id="udb_widgets__remove-all"
											value="1"
											<?php checked( $remove_all_is_checked, 1 ); ?>
										>
										<div class="indicator"></div>
									</label>
								</div>
							</li>

							<!-- Divider with text -->
							<div class="udb-divider for-widget-remove-all">
								<span class="divider-text"><?php esc_html_e( 'Or remove individual widgets', 'ultimate-dashboard' ); ?></span>
							</div>
							
							<li>
								<div class="module-text">
									<h3>
										<label for="udb_widgets__welcome_panel">
											<?php esc_html_e( 'Welcome Panel', 'ultimate-dashboard' ); ?>
										</label>
									</h3>
								</div>
								<div class="widget-toggle">
									<label for="udb_widgets__welcome_panel" class="label checkbox-label">
										<input
											type="checkbox"
											name="udb_widgets[welcome_panel]"
											id="udb_widgets__welcome_panel"
											value="1"
											<?php checked( $welcome_panel_is_checked, 1 ); ?>
										>

										<div class="indicator"></div>
									</label>
								</div>
							</li>

						<?php foreach ( $widgets as $id => $widget ) : ?>
							<?php
							$is_checked = isset( $settings[ $id ] ) ? 1 : 0;
							$title      = isset( $widget['title_stripped'] ) ? $widget['title_stripped'] : '';
							$slug       = isset( $widget['id'] ) ? $widget['id'] : '';
							?>

							<li>
								<div class="module-text">
									<h3>
										<label for="udb_widgets__<?php echo esc_attr( $slug ); ?>">
											<?php echo esc_html( $title ); ?>
										</label>
									</h3>
								</div>
								<div class="widget-toggle">
									<label for="udb_widgets__<?php echo esc_attr( $slug ); ?>" class="label checkbox-label">
										<input
											type="checkbox"
											name="udb_widgets[<?php echo esc_attr( $slug ); ?>]"
											id="udb_widgets__<?php echo esc_attr( $slug ); ?>"
											value="1"
											<?php checked( $is_checked, 1 ); ?>
										>

										<div class="indicator"></div>
									</label>
								</div>
							</li>

							<?php endforeach; ?>
						</ul>						

					</div>

					<div class="udb-onboarding-wizard-slide udb-general-settings-slide">

						<header>
							<h2>
								<?php esc_html_e( 'General Settings', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php esc_html_e( 'Next, let\'s simplify the WordPress Dashboard interface by removing extra elements for a cleaner look.', 'ultimate-dashboard' ); ?>
							</p>
						</header>

						<?php
						$admin_bar_helper = new Admin_Bar_Helper();
						$selected_roles   = $admin_bar_helper->roles_to_remove();
						?>
 
						<ul class="udb-modules">
							<?php foreach ( $general_settings as $setting ) : ?>
								<?php
								$is_checked = isset( $settings[ $setting['name'] ] ) ? 1 : 0;
								$title      = isset( $setting['title'] ) ? $setting['title'] : '';
								$slug       = isset( $setting['name'] ) ? $setting['name'] : '';

								// Check if the admin bar should be removed.
								if ( 'remove_admin_bar' === $setting['name'] && $admin_bar_helper->should_remove_admin_bar() ) {
									$is_checked = 1;
								}

								// Get all available roles.
								$roles = wp_roles()->roles;
								?>

								<li class="<?php echo ( 'remove_admin_bar' === $setting['name'] ? 'has-select2' : '' ); ?>">

									<!-- Show roles dropdown if remove_admin_bar is selected -->
									<?php if ( 'remove_admin_bar' === $setting['name'] ) : ?>

										<div class="role-dropdown">
											<h3><label for="remove_by_roles" class="dropdown-label"><?php esc_html_e( 'Hide Admin Bar for:', 'ultimate-dashboard' ); ?></label></h3>
											<select name="remove_by_roles[]" id="remove_by_roles" class="full-width-dropdown use-select2" multiple>
												<option value="all" <?php echo esc_attr( in_array( 'all', $selected_roles, true ) ? 'selected' : '' ); ?>>
													<?php esc_html_e( 'All', 'ultimate-dashboard' ); ?>
												</option>
												
												<?php foreach ( $roles as $role_key => $role ) : ?>
													<option value="<?php echo esc_attr( $role_key ); ?>"
														<?php selected( in_array( $role_key, $selected_roles, true ), true ); ?>>
														<?php echo esc_html( $role['name'] ); ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>

									<?php else : ?>

										<div class="module-text">
											<h3>
												<label for="udb_settings__<?php echo esc_attr( $slug ); ?>">
													<?php echo esc_html( $title ); ?>
												</label>
											</h3>
										</div>

										<div class="setting-toggle">
											<label for="udb_settings__<?php echo esc_attr( $slug ); ?>" class="label checkbox-label">
												<input
													type="checkbox"
													name="udb_settings[<?php echo esc_attr( $slug ); ?>]"
													id="udb_settings__<?php echo esc_attr( $slug ); ?>"
													value="1"
													<?php checked( $is_checked, 1 ); ?>
												>
												<div class="indicator"></div>
											</label>
										</div>

									<?php endif; ?>
								</li>

							<?php endforeach; ?>
						</ul>

					</div>
					
					<div class="udb-onboarding-wizard-slide udb-custom-login-url-slide">
						<header>
							<h2>
								<?php esc_html_e( 'Custom Login URL', 'ultimate-dashboard' ); ?>
							</h2>
							<p>
								<?php esc_html_e( 'Customize the login URL to enhance security and avoid common login page attacks.', 'ultimate-dashboard' ); ?>
							</p>
						</header>

						<div class="udb-subscription-form">
							<!-- URL Row -->
							<div class="udb-form-row onboarding-wizard-login-url">
								<code class="onboarding-wizard-login-url"><?php esc_html_e( 'yourdomain.com', 'ultimate-dashboard' ); ?>/</code>
								<input
									type="text" 
									name="udb_login_redirect[login_url_slug]" 
									id="udb_login_redirect" 
									class="udb-input onboarding-wizard-login-url"
									value="<?php echo esc_attr( $login_slug ); ?>" 
									placeholder="login" 
								>
								<code class="onboarding-wizard-login-url">/</code>
							</div>

							<div class="udb-discount-notif"></div>

							<!-- Description -->
							<div class="udb-form-row">
								<p class="description">
								<?php
								// translators: Placeholder is the site url.
								echo wp_kses_post( sprintf( __( 'This will replace your login URL (<code>%1$s/wp-login.php</code>) and help secure your site by making the login page less predictable.', 'ultimate-dashboard' ), esc_url( site_url() ) ) );
								?>
							</p>
							</div>
						</div>
					</div>

					<div class="udb-onboarding-wizard-slide udb-subscription-slide">

						<header>
							<h2>
								<?php esc_html_e( 'Exclusive Discount 🥳', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php echo wp_kses_post( __( 'Unlock all features! Subscribe to our newsletter and claim your <strong>exclusive discount</strong> on Ultimate Dashboard PRO.', 'ultimate-dashboard' ) ); ?>
							</p>
						</header>

						<div class="udb-subscription-form">
							<div class="udb-form-row">
								<input type="text" name="udb_subscription_name" id="udb-subscription-name" class="udb-input" placeholder="Name">
							</div>
							<div class="udb-form-row">
								<input type="text" name="udb_subscription_email" id="udb-subscription-email" class="udb-input" placeholder="Email">
							</div>
							<div class="udb-form-row">
								<button type="button" class="button button-primary button-large udb-button subscribe-button">
									<?php esc_html_e( 'Subscribe', 'ultimate-dashboard' ); ?>
								</button>
							</div>
							<div class="udb-form-row udb-onboarding-wizard-skip-discount">
								<a href="">
									<?php esc_html_e( 'No, I don\'t want any Discount :(', 'ultimate-dashboard' ); ?>
								</a>
							</div>
						</div>

					</div>

					<div class="udb-onboarding-wizard-slide udb-finished-slide">

						<header>
							<h2>
								<?php esc_html_e( 'Setup Complete! 🎉', 'ultimate-dashboard' ); ?>
							</h2>

							<p data-udb-show-on="subscribe">
								<?php esc_html_e( 'We\'ll send you an email with a <strong> discount code for Ultimate Dashboard PRO </strong> shortly.', 'ultimate-dashboard' ); ?>
							</p>

							<p>
								<?php
								esc_html_e( 'What\'s next? Explore all features from the <strong>"Ultimate Dash..."</strong> admin menu.', 'ultimate-dashboard' );
								?>
							</p>

							<p data-udb-show-on="skip-discount">
								<strong><?php esc_html_e( 'This is your last chance to get an exclusive discount on Ultimate Dashboard PRO at the link below! 👇👇👇', 'ultimate-dashboard' ); ?></strong>
							</p>
						</header>

						<div class="finish-button-wrapper">
							<a target="_blank" href="https://ultimatedashboard.io/special-discount/" class="button button-primary finish-button">
								<?php esc_html_e( 'Grab your Discount', 'ultimate-dashboard' ); ?>
							</a>
						</div>

					</div>
				</div>

				<footer class="heatbox-footer">
					<div class="heatbox-footer-item">
						<span class="onboarding-wizard-skip-button skip-button">
							<?php esc_html_e( 'Skip', 'ultimate-dashboard' ); ?>
						</span>
					</div>
					<div class="heatbox-footer-item">
						<div class="udb-dots"></div>
					</div>
					<div class="heatbox-footer-item">
						<button type="button" class="button button-large button-primary udb-button save-button">
							<?php esc_html_e( 'Next', 'ultimate-dashboard' ); ?>
						</button>
					</div>
				</footer>

				<div class="udb-discount-notif for-discount is-hidden">
					<?php echo wp_kses_post( __( 'Don\'t miss out.<br> <strong>This discount won\'t come back!</strong>', 'ultimate-dashboard' ) ); ?>
				</div>

			</div>

			<!-- Skip Wizard Link -->
			<div class="heatbox-footer skip-onboarding-wizard">
				<div class="heatbox-footer-item">
					<a href="#" id="skip-setup-onboarding-wizard" class="skip-onboarding-wizard-link">
						<?php esc_html_e( 'Skip Setup Wizard', 'ultimate-dashboard' ); ?>
					</a>
				</div>
			</div>

			<!-- Explore Settings Link -->
			<div class="heatbox-footer onboarding-wizard-explore-settings">
				<div class="heatbox-footer-item">
					<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=udb_widgets&page=udb_settings' ) ); ?>" id="onboarding-wizard-explore-settings" class="onboarding-wizard-explore-settings-link is-hidden">
						<?php esc_html_e( 'Explore Settings', 'ultimate-dashboard' ); ?>
					</a>
				</div>
			</div>

		</div>

	</div>

	<?php

};
