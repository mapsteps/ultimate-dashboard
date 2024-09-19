<?php
/**
 * Wizard page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Setup;
use Udb\Helpers\Widget_Helper;

return function ( $referrer = '' ) {

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

	<div class="wrap heatbox-wrap udb-wizard-page" data-udb-referrer="<?php echo esc_attr( $referrer ); ?>">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div style="width: 80%">
						<span class="title">
							<?php _e( 'Welcome to Ultimate Dashboard', 'ultimate-dashboard' ); ?>
						</span>
						<p class="subtitle">
							<?php _e( 'Complete the Setup Wizard and start using Ultimate Dashboard in less than a minute.', 'ultimate-dashboard' ); ?>							
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

			<div class="heatbox wizard-heatbox">

				<div class="udb-wizard-slides">
					<div class="udb-wizard-slide udb-modules-slide">

						<header>
							<h2>
								<?php _e( 'Features', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php _e( 'Select the features you\'d like to enable. You can change them later in the "Modules" tab.', 'ultimate-dashboard' ); ?>
							</p>
						</header>


						<ul class="udb-modules">
					
							<?php foreach ( $modules as $module ) : ?>
								<?php
								$slug          = $module['module'];
								$title         = $module['title'];
								$disabled_attr = '';
								$is_checked    = true;

								if ( 'erident' === $referrer ) {
									$disabled_attr = 'login_customizer' === $slug ? 'disabled' : $disabled_attr;
								}

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

					<div class="udb-wizard-slide udb-widgets-slide">

						<header>
							<h2>
								<?php _e( 'Dashboard Widgets', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php _e( 'Let\'s clean up your dashboard. Remove some or all of the default WordPress widgets.', 'ultimate-dashboard' ); ?>
							</p>
						</header>
 
						<ul class="udb-modules">
							<li>
								<div class="module-text">
									<h3>
										<label for="udb_widgets__remove-all">
											<?php _e( 'Remove all Dashboard Widgets', 'ultimate-dashboard' ); ?>
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
											<?php echo esc_attr( $disabled_attr ); ?>
										>

										<div class="indicator"></div>
									</label>
								</div>
							</li>

							<div class="udb-discount-notif for-widget-remove-all">
							</div>
							
							<li>
								<div class="module-text">
									<h3>
										<label for="udb_widgets__welcome_panel">
											<?php _e( 'Welcome Panel', 'ultimate-dashboard' ); ?>
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
											<?php echo esc_attr( $disabled_attr ); ?>
										>

										<div class="indicator"></div>
									</label>
								</div>
							</li>

						<?php foreach ( $widgets as $id => $widget ) : ?>
							<?php
							$disabled_attr = '';
							$is_checked    = isset( $settings[ $id ] ) ? 1 : 0;
							$title         = isset( $widget['title_stripped'] ) ? $widget['title_stripped'] : '';
							$slug          = isset( $widget['id'] ) ? $widget['id'] : '';
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
											<?php echo esc_attr( $disabled_attr ); ?>
										>

										<div class="indicator"></div>
									</label>
								</div>
							</li>

							<?php endforeach; ?>
						</ul>						

					</div>

					<div class="udb-wizard-slide udb-general-settings-slide">

						<header>
							<h2>
								<?php _e( 'General Settings', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php _e( 'Next, let\'s simplify the WordPress Dashboard interface by removing extra elements for a cleaner look.', 'ultimate-dashboard' ); ?>
							</p>
						</header>
 
						<ul class="udb-modules">
						<?php foreach ( $general_settings as $setting ) : ?>
							<?php
							$disabled_attr = '';
							$is_checked    = isset( $settings[ $setting['name'] ] ) ? 1 : 0;
							$title         = isset( $setting['title'] ) ? $setting['title'] : '';
							$slug          = isset( $setting['name'] ) ? $setting['name'] : '';
							?>

							<li>
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
											<?php echo esc_attr( $disabled_attr ); ?>
										>

										<div class="indicator"></div>
									</label>
								</div>
							</li>

							<?php endforeach; ?>
						</ul>
												

					</div>
					
					<div class="udb-wizard-slide udb-custom-login-url-slide">
						<header>
							<h2><?php _e( 'Custom Login URL', 'ultimate-dashboard' ); ?></h2>
							<p><?php _e( 'Customize the login URL to enhance security and avoid common login page attacks.', 'ultimate-dashboard' ); ?></p>
						</header>

						<div class="udb-subscription-form">
							<!-- URL Row -->
							<div class="udb-form-row wizard-login-url">
								<code class="wizard-login-url">yourdomain.com/</code>
								<input 
									type="text" 
									name="udb_login_redirect[login_url_slug]" 
									id="udb_login_redirect" 
									class="udb-input wizard-login-url"
									value="<?php echo esc_attr( $login_slug ); ?>" 
									placeholder="login" 
								>
								<code class="wizard-login-url">/</code>
							</div>

							<div class="udb-discount-notif"></div>

							<!-- Description -->
							<div class="udb-form-row">
								<p class="description">
									<?php printf( __( 'This will replace your login URL (<code>%1$s/wp-login.php</code>) and help secure your site by making the login page less predictable.', 'ultimate-dashboard' ), esc_url( site_url() ) ); ?>
								</p>
							</div>
						</div>
					</div>


					<div class="udb-wizard-slide udb-subscription-slide">

						<header>
							<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/modules/wizard/assets/images/undraw_discount_d-4-bd.svg" alt="Ultimate Dashboard Features" class="udb-illustration subscription-illustration">

							<h2>
								<?php _e( 'Exclusive <br>Discount 🥳', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php printf( __( 'We are offering all <strong>%1$s users an exclusive Discount</strong> on Ultimate Dashboard PRO. Subscribe to our Newsletter & get your discount.', 'ultimate-dashboard' ), esc_attr( ucwords( $referrer ) ) ); ?>
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
									<?php _e( 'Subscribe', 'ultimate-dashboard' ); ?>
								</button>
							</div>
							<div class="udb-form-row udb-skip-discount">
								<a href="">
									<?php _e( 'No, I don\'t want any Discount :/', 'ultimate-dashboard' ); ?>
								</a>
							</div>
						</div>

					</div>

					<div class="udb-wizard-slide udb-finished-slide">

						<header>
							<h2>
								<?php _e( 'Setup Complete. Enjoy!', 'ultimate-dashboard' ); ?>
							</h2>

							<p data-udb-show-on="subscribe">
								<?php _e( 'We will send you an email with a <strong>Discount Code for Ultimate Dashboard PRO</strong> in just a minute (make sure to also check your spam folder).', 'ultimate-dashboard' ); ?>
							</p>

							<p>
								<?php _e( 'What\'s next? Explore all the new Features from the <strong>"Ultimate Dash..."</strong> admin menu.', 'ultimate-dashboard' ); ?>
							</p>

							<p data-udb-show-on="skip-discount">
								<strong><?php _e( 'Last chance to get a Discount Ultimate Dashboard PRO at the link below! 👇👇👇', 'ultimate-dashboard' ); ?></strong>
							</p>
						</header>

						<div class="finish-button-wrapper">
							<a target="_blank" href="https://ultimatedashboard.io/special-discount/" class="button button-primary finish-button">
								<?php _e( 'Grab the Deal', 'ultimate-dashboard' ); ?>
							</a>
						</div>

					</div>
				</div>

				<footer class="heatbox-footer">
					<div class="heatbox-footer-item">
						<span class="wizard-skip-button skip-button">
							<?php _e( 'Skip', 'ultimate-dashboard' ); ?>
						</span>
					</div>
					<div class="heatbox-footer-item">
						<div class="udb-dots"></div>
					</div>
					<div class="heatbox-footer-item">
						<button type="button" class="button button-large button-primary udb-button save-button">
							<?php _e( 'Next', 'ultimate-dashboard' ); ?>
						</button>
					</div>
				</footer>

				<div class="udb-discount-notif for-discount is-hidden">
					<?php printf( __( 'This is an exclusive discount for %1$s users.<br> <strong>This discount will not come back!</strong>', 'ultimate-dashboard' ), esc_attr( ucwords( $referrer ) ) ); ?>
				</div>

			</div>

			<!-- Add this button below the wizard-heatbox -->
			<div class="heatbox-footer skip-wizard">
				<div class="heatbox-footer-item">
					<a href="#" id="skip-setup-wizard" class="skip-wizard-link">
						<?php _e( 'Skip Setup Wizard', 'ultimate-dashboard' ); ?>
					</a>
				</div>
			</div>

		</div>


	</div>

	<?php

};
