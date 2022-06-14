<?php
/**
 * Plugin onboarding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Setup;

return function () {

	$udb_core      = new Setup();
	$saved_modules = $udb_core->saved_modules();

	$modules = array(
		array(
			'module'      => 'white_label',
			'icon'        => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/white-label.png',
			'title'       => __( 'White Label', 'ultimate-dashboard' ),
			'description' => __( 'White label & rebrand the WordPress admin area with the White Label module.', 'ultimate-dashboard' ),
		),
		array(
			'module'      => 'login_customizer',
			'icon'        => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/login-customizer.png',
			'title'       => __( 'Login Customizer', 'ultimate-dashboard' ),
			'description' => __( 'Fully customize the login screen, directly within the WordPress customizer.', 'ultimate-dashboard' ),
		),
		array(
			'module'      => 'login_redirect',
			'icon'        => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/login-redirect.png',
			'title'       => __( 'Login Redirect', 'ultimate-dashboard' ),
			'description' => __( 'Change the WordPress login url, redirect users after login & set a <code>/wp-admin/</code> redirect for non logged-in users.', 'ultimate-dashboard' ),
		),
		array(
			'module'      => 'admin_pages',
			'icon'        => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-pages.png',
			'title'       => __( 'Admin Pages', 'ultimate-dashboard' ),
			'description' => __( 'Create useful custom admin pages for your customers with the Admin Pages module.', 'ultimate-dashboard' ),
		),
		array(
			'module'      => 'admin_menu_editor',
			'icon'        => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-menu.png',
			'title'       => __( 'Admin Menu Editor', 'ultimate-dashboard' ),
			'description' => __( 'Rearrange, hide & add new admin menu items for specific users & user roles with the Admin Menu Editor module.', 'ultimate-dashboard' ),
		),
		array(
			'module'      => 'admin_bar_editor',
			'icon'        => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-bar.png',
			'title'       => __( 'Admin Bar Editor', 'ultimate-dashboard' ),
			'description' => __( 'Rearrange, hide & add new items to the WordPress toolbar with the Admin Bar Editor module.', 'ultimate-dashboard' ),
		),
	)

	?>

	<div class="wrap heatbox-wrap udb-onboarding-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php _e( 'Get started with Ultimate Dashboard.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">
			<h1 style="display: none;"></h1>

			<div class="heatbox onboarding-heatbox">

				<div class="udb-onboarding-slides">
					<div class="udb-onboarding-slide udb-modules-slide">

						<header>
							<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/modules/plugin-onboarding/assets/images/undraw_reviewed_docs_re_9lmr.svg" alt="Ultimate Dashboard Features" class="udb-illustration module-illustration">

							<h2>
								<?php _e( 'Ultimate Dashboard Features', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php _e( "Choose the features you want to enable or disable. The login customizer is always enabled to implement your Erident's settings.", 'ultimate-dashboard' ); ?>
							</p>
						</header>


						<ul class="udb-modules">
							<?php foreach ( $modules as $module ) : ?>
								<?php
								$slug          = $module['module'];
								$icon          = $module['icon'];
								$title         = $module['title'];
								$description   = $module['description'];
								$disabled_attr = 'login_customizer' === $slug ? 'disabled' : '';
								$is_checked    = true;

								if ( isset( $saved_modules[ $slug ] ) && 'false' === $saved_modules[ $slug ] ) {
									$is_checked = false;

									if ( 'login_customizer' === $slug ) {
										$disabled_attr = '';
									}
								}
								?>

								<li>
									<div class="module-image">
										<img src="<?php echo esc_url( $icon ); ?>" alt="">
									</div>
									<div class="module-text">
										<h3>
											<label for="udb_modules__<?php echo esc_attr( $slug ); ?>">
												<?php echo esc_html( $title ); ?>
											</label>
										</h3>
										<p>
											<?php echo $description; ?>
										</p>
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
					<div class="udb-onboarding-slide udb-subscription-slide">

						<header>
							<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/modules/plugin-onboarding/assets/images/undraw_discount_d-4-bd.svg" alt="Ultimate Dashboard Features" class="udb-illustration subscription-illustration">

							<h2>
								<?php _e( 'Signup For Updates', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php _e( 'Get notified about the latest updates and module releases. We also offers discount for Ultimate Dashboard PRO in some events.', 'ultimate-dashboard' ); ?>
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
									<?php esc_html_e( 'Subscribe Now', 'ultimate-dashboard' ); ?>
								</button>
							</div>
						</div>

					</div>

					<div class="udb-onboarding-slide udb-finished-slide">

						<header>
							<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/modules/plugin-onboarding/assets/images/Confetti-Popper-PNG-Download-Image-.png" alt="Finished!" class="udb-illustration subscription-illustration">

							<h2>
								<?php _e( 'Setup done, enjoy!', 'ultimate-dashboard' ); ?>
							</h2>

							<p>
								<?php _e( "Your Ultimate Dashboard plugin has been setup. What's next? You can manage your widgets, or customize your login, or do other settings by clicking on \"Ultimate Dashboard\" menu in the sidebar.", 'ultimate-dashboard' ); ?>
							</p>
						</header>

						<div class="finish-button-wrapper">
							<a href="<?php echo esc_url( admin_url() ); ?>" class="button button-primary finish-button">
								<?php _e( 'Back to Dashboard', 'ultimate-dashboard' ); ?>
							</a>
						</div>

					</div>
				</div>

				<footer class="heatbox-footer">
					<div class="heatbox-footer-item">
						<button type="button" class="button button-large udb-button skip-button">
							Skip
						</button>
					</div>
					<div class="heatbox-footer-item">
						<div class="udb-dots"></div>
					</div>
					<div class="heatbox-footer-item">
						<button type="button" class="button button-large button-primary udb-button save-button">
							Save Changes
						</button>
					</div>
				</footer>

			</div>

		</div>


	</div>

	<?php

};
