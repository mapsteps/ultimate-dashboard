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
							Welcome to Ultimate Dashboard
						</span>
						<p class="subtitle">
							Complete the 1-Click Setup & get an <strong style="font-weight: 700; color: tomato;">exclusive Discount</strong> on <strong>Ultimate Dashboard PRO!</strong>
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
								1-Click Setup
							</h2>

							<p>
								Choose what features you would like to enable/disable. You can always manage this later from the Modules page.
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
								WordPress Dashboard Widgets
							</h2>

							<p>
								Choose which widget you would like to enable/disable. You can always manage this later from the Settings page.
							</p>
						</header>
 
						<ul class="udb-modules">
							<li>
								<div class="module-text">
									<h3>
										<label for="udb_widgets__remove-all">
											Remove all Dashboard Widgets
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
								<br>
							<li>
								<div class="module-text">
									<h3>
										<label for="udb_widgets__welcome_panel">
											Welcome Panel
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
								General Settings
							</h2>

							<p>
								Choose which one you would like to remove. You can always manage this later from the Settings page.
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
							<h2>
								Custom Login URL
							</h2>

							<p>
								Set a custom login URL. You can always manage this later from the Login Redirect page.
							</p>
						</header>

						<div class="udb-subscription-form"> 					 

							<div class="udb-form-row">
								<code>
									<?php echo esc_url( site_url() ); ?>/
								</code>
							</div>

							<div class="udb-form-row">
								<input type="text" name="udb_login_redirect[login_url_slug]" id="udb_login_redirect" class="udb-input" value="<?php echo esc_attr( $login_slug ); ?>" placeholder="Login URL" >
							</div>  

							<div class="udb-form-row">
								<p class="description">
									<?php printf( __( 'Change the login URL and prevent users from accessing <code>%1$s/wp-login.php</code>.', 'ultimate-dashboard' ), esc_url( site_url() ) ); ?>
								</p>
							</div>

						</div>						
  
					</div>

					<div class="udb-wizard-slide udb-subscription-slide">

						<header>
							<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/modules/wizard/assets/images/undraw_discount_d-4-bd.svg" alt="Ultimate Dashboard Features" class="udb-illustration subscription-illustration">

							<h2>
								Exclusive <br>Discount 🥳
							</h2>

							<p>
								We are offering all <strong><?php echo esc_attr( ucwords( $referrer ) ); ?> users an exclusive Discount</strong> on Ultimate Dashboard PRO. Subscribe to our Newsletter & get your discount.
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
									Subscribe
								</button>
							</div>
							<div class="udb-form-row udb-skip-discount">
								<a href="">
									No, I don't want any Discount :/
								</a>
							</div>
						</div>

					</div>

					<div class="udb-wizard-slide udb-finished-slide">

						<header>
							<h2>
								Setup Complete. Enjoy!
							</h2>

							<p data-udb-show-on="subscribe">
								We will send you an email with a <strong>Discount Code for Ultimate Dashboard PRO</strong> in just a minute (make sure to also check your spam folder).
							</p>

							<p>
								What's next? Explore all the new Features from the <strong>"Ultimate Dash..."</strong> admin menu.
							</p>

							<p data-udb-show-on="skip-discount">
								<strong>Last chance to get a Discount Ultimate Dashboard PRO at the link below! 👇👇👇</strong>
							</p>
						</header>

						<div class="finish-button-wrapper">
							<a target="_blank" href="https://ultimatedashboard.io/special-discount/" class="button button-primary finish-button">
								Grab the Deal
							</a>
						</div>

					</div>
				</div>

				<footer class="heatbox-footer">
					<div class="heatbox-footer-item">
						<span class="wizard-skip-button skip-button">
							Skip
						</span>
					</div>
					<div class="heatbox-footer-item">
						<div class="udb-dots"></div>
					</div>
					<div class="heatbox-footer-item">
						<button type="button" class="button button-large button-primary udb-button save-button">
							Next
						</button>
					</div>
				</footer>

				<div class="udb-discount-notif is-hidden">
					This is an exclusive discount for <?php echo esc_attr( ucwords( $referrer ) ); ?> users.<br> <strong>This discount will not come back!</strong>
				</div>

			</div>

			<!-- Add this button below the wizard-heatbox -->
			<div class="heatbox-footer skip-wizard">
				<div class="heatbox-footer-item">
					<button id="skip-setup-wizard" class="button button-large button-primary">
						Skip Setup Wizard
					</button>
				</div>
			</div>
		</div>


	</div>

	<?php

};
