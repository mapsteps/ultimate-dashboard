<?php
/**
 * Onboarding page template (both variants).
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Setup;
use Udb\Helpers\Widget_Helper;
use Udb\Helpers\Admin_Bar_Helper;

/**
 * Output the onboarding page.
 *
 * @param string $variant  "wizard" or "migration".
 * @param string $referrer The plugin the user came from (migration variant only).
 */
return function ( $variant, $referrer = '' ) {

	$is_wizard     = 'wizard' === $variant;
	$saved_modules = ( new Setup() )->saved_modules();
	$settings      = get_option( 'udb_settings' );
	$settings      = is_array( $settings ) ? $settings : array();
	$img_url       = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/onboarding/assets/img';
	$referrer_name = ucwords( $referrer );

	$modules = array(
		'login_customizer'  => __( 'Login Customizer', 'ultimate-dashboard' ),
		'white_label'       => __( 'White Label', 'ultimate-dashboard' ),
		'login_redirect'    => __( 'Login Redirect', 'ultimate-dashboard' ),
		'admin_pages'       => __( 'Admin Pages', 'ultimate-dashboard' ),
		'admin_menu_editor' => __( 'Admin Menu Editor', 'ultimate-dashboard' ),
		'admin_bar_editor'  => __( 'Admin Bar Editor', 'ultimate-dashboard' ),
	);

	/**
	 * Output a toggle row (label + round checkbox).
	 *
	 * @param string $id      The input ID.
	 * @param string $value   The value the script reads.
	 * @param string $label   The label.
	 * @param bool   $checked Whether it's checked.
	 * @param string $group   The group the script reads (modules, widgets, settings).
	 * @param bool   $locked  Whether it's locked on.
	 */
	$toggle = function ( $id, $value, $label, $checked, $group, $locked = false ) {
		?>
		<li>
			<div class="module-text">
				<h3><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label></h3>
			</div>
			<div class="module-toggle">
				<label for="<?php echo esc_attr( $id ); ?>" class="label checkbox-label">
					<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" data-udb-group="<?php echo esc_attr( $group ); ?>" <?php checked( $checked ); ?> <?php disabled( $locked ); ?>>
					<div class="indicator"></div>
				</label>
			</div>
		</li>
		<?php
	};
	?>

	<div class="wrap heatbox-wrap udb-onboarding-wizard-page is-<?php echo esc_attr( $variant ); ?>">

		<div class="heatbox-header heatbox-margin-bottom">
			<div class="heatbox-container heatbox-container-center">
				<div class="logo-container">
					<div style="width: 80%">
						<h1 class="title"><?php esc_html_e( 'Welcome to Ultimate Dashboard', 'ultimate-dashboard' ); ?></h1>
						<p class="subtitle">
							<?php
							if ( $is_wizard ) {
								esc_html_e( 'Complete the Setup Wizard and get started with Ultimate Dashboard in less than a minute.', 'ultimate-dashboard' );
							} else {
								echo wp_kses_post( __( 'Complete the 1-Click Setup & get an <strong style="font-weight: 700; color: tomato;">exclusive Discount</strong> on <strong>Ultimate Dashboard PRO!</strong>', 'ultimate-dashboard' ) );
							}
							?>
						</p>
					</div>
					<div style="width: 20%">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="">
					</div>
				</div>
			</div>
		</div>

		<div class="heatbox-container heatbox-container-center">
			<hr class="wp-header-end">

			<div class="heatbox onboarding-wizard-heatbox">

				<div class="udb-onboarding-wizard-slides">

					<div class="udb-onboarding-wizard-slide udb-modules-slide" data-udb-step="modules">
						<header>
							<?php if ( $is_wizard ) : ?>
								<h2><?php esc_html_e( 'Features', 'ultimate-dashboard' ); ?></h2>
								<p><?php esc_html_e( 'Select the features you\'d like to enable. You can change them later in the "Modules" tab.', 'ultimate-dashboard' ); ?></p>
							<?php else : ?>
								<img src="<?php echo esc_url( $img_url . '/features.svg' ); ?>" alt="" class="udb-illustration module-illustration">
								<h2><?php esc_html_e( '1 Click Setup', 'ultimate-dashboard' ); ?></h2>
								<p><?php esc_html_e( 'Choose what features you would like to enable/disable. You can always manage this later from the Modules page.', 'ultimate-dashboard' ); ?></p>
							<?php endif; ?>
						</header>

						<ul class="udb-modules">
							<?php
							foreach ( $modules as $module => $label ) {
								$is_on = ! ( isset( $saved_modules[ $module ] ) && 'false' === $saved_modules[ $module ] );

								// Erident users come for the login customizer: keep it on.
								$locked = 'erident' === $referrer && 'login_customizer' === $module && $is_on;

								$toggle( 'udb_modules__' . $module, $module, $label, $is_on, 'modules', $locked );
							}
							?>
						</ul>
					</div>

					<?php if ( $is_wizard ) : ?>

						<div class="udb-onboarding-wizard-slide udb-widgets-slide" data-udb-step="widgets">
							<header>
								<h2><?php esc_html_e( 'Dashboard Widgets', 'ultimate-dashboard' ); ?></h2>
								<p><?php esc_html_e( 'Let\'s clean up your dashboard. Remove some or all of the default WordPress widgets.', 'ultimate-dashboard' ); ?></p>
							</header>

							<ul class="udb-modules">
								<?php $toggle( 'udb_widgets__remove-all', 'remove-all', __( 'Remove all Dashboard Widgets', 'ultimate-dashboard' ), isset( $settings['remove-all'] ), 'widgets' ); ?>

								<li class="udb-divider for-widget-remove-all">
									<span class="divider-text"><?php esc_html_e( 'Or remove individual widgets', 'ultimate-dashboard' ); ?></span>
								</li>

								<?php
								$toggle( 'udb_widgets__welcome_panel', 'welcome_panel', __( 'Welcome Panel', 'ultimate-dashboard' ), isset( $settings['welcome_panel'] ), 'widgets' );

								foreach ( ( new Widget_Helper() )->get_default() as $widget_id => $widget ) {
									$title = isset( $widget['title_stripped'] ) ? $widget['title_stripped'] : $widget_id;
									$toggle( 'udb_widgets__' . $widget_id, $widget_id, $title, isset( $settings[ $widget_id ] ), 'widgets' );
								}
								?>
							</ul>
						</div>

						<?php $selected_roles = ( new Admin_Bar_Helper() )->roles_to_remove(); ?>

						<div class="udb-onboarding-wizard-slide udb-general-settings-slide" data-udb-step="settings">
							<header>
								<h2><?php esc_html_e( 'General Settings', 'ultimate-dashboard' ); ?></h2>
								<p><?php esc_html_e( 'Next, let\'s simplify the WordPress Dashboard interface by removing extra elements for a cleaner look.', 'ultimate-dashboard' ); ?></p>
							</header>

							<ul class="udb-modules">
								<?php
								$toggle( 'udb_settings__remove_help_tab', 'remove_help_tab', __( 'Remove Help Tab', 'ultimate-dashboard' ), isset( $settings['remove_help_tab'] ), 'settings' );
								$toggle( 'udb_settings__remove_screen_options', 'remove_screen_options', __( 'Remove Screen Options Tab', 'ultimate-dashboard' ), isset( $settings['remove_screen_options'] ), 'settings' );
								?>
								<li class="has-select2">
									<div class="role-dropdown">
										<h3><label for="remove_by_roles" class="dropdown-label"><?php esc_html_e( 'Hide Admin Bar on the Frontend for:', 'ultimate-dashboard' ); ?></label></h3>
										<select id="remove_by_roles" class="full-width-dropdown" multiple data-placeholder="<?php esc_attr_e( 'Select Role', 'ultimate-dashboard' ); ?>">
											<option value="all" <?php selected( in_array( 'all', $selected_roles, true ) ); ?>><?php esc_html_e( 'All', 'ultimate-dashboard' ); ?></option>
											<?php foreach ( wp_roles()->roles as $role_key => $role ) : ?>
												<option value="<?php echo esc_attr( $role_key ); ?>" <?php selected( in_array( $role_key, $selected_roles, true ) ); ?>><?php echo esc_html( translate_user_role( $role['name'] ) ); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</li>
							</ul>
						</div>

						<?php
						$login_redirect = get_option( 'udb_login_redirect' );
						$login_slug     = isset( $login_redirect['login_url_slug'] ) ? trim( $login_redirect['login_url_slug'], '/' ) : '';
						?>

						<div class="udb-onboarding-wizard-slide udb-custom-login-url-slide" data-udb-step="login_url">
							<header>
								<h2><?php esc_html_e( 'Custom Login URL', 'ultimate-dashboard' ); ?></h2>
								<p><?php esc_html_e( 'Customize the login URL to enhance security and avoid common login page attacks.', 'ultimate-dashboard' ); ?></p>
							</header>

							<div class="udb-subscription-form">
								<div class="udb-form-row onboarding-wizard-login-url">
									<code class="onboarding-wizard-login-url"><?php esc_html_e( 'yourdomain.com', 'ultimate-dashboard' ); ?>/</code>
									<input type="text" id="udb_login_redirect" class="udb-input onboarding-wizard-login-url" value="<?php echo esc_attr( $login_slug ); ?>" placeholder="login">
									<code class="onboarding-wizard-login-url">/</code>
								</div>

								<div class="udb-form-row">
									<p class="description">
										<?php
										// translators: %1$s: The site URL.
										echo wp_kses_post( sprintf( __( 'This will replace your login URL (<code>%1$s/wp-login.php</code>) and help secure your site by making the login page less predictable.', 'ultimate-dashboard' ), esc_url( site_url() ) ) );
										?>
									</p>
								</div>
							</div>
						</div>

					<?php endif; ?>

					<div class="udb-onboarding-wizard-slide udb-subscription-slide" data-udb-step="subscription">
						<header>
							<?php if ( ! $is_wizard ) : ?>
								<img src="<?php echo esc_url( $img_url . '/discount.svg' ); ?>" alt="" class="udb-illustration subscription-illustration">
							<?php endif; ?>

							<h2><?php esc_html_e( 'Exclusive Discount 🥳', 'ultimate-dashboard' ); ?></h2>

							<p>
								<?php
								if ( $is_wizard ) {
									echo wp_kses_post( __( 'Unlock all features! Subscribe to our newsletter and claim your <strong>exclusive discount</strong> on Ultimate Dashboard PRO.', 'ultimate-dashboard' ) );
								} else {
									/* translators: %1$s: The plugin the user came from. */
									echo wp_kses_post( sprintf( __( 'We are offering all <strong>%1$s users an exclusive Discount</strong> on Ultimate Dashboard PRO. Subscribe to our Newsletter & get your discount.', 'ultimate-dashboard' ), esc_html( $referrer_name ) ) );
								}
								?>
							</p>
						</header>

						<div class="udb-subscription-form">
							<div class="udb-form-row">
								<input type="text" id="udb-subscription-name" class="udb-input" placeholder="<?php esc_attr_e( 'Name', 'ultimate-dashboard' ); ?>" autocomplete="name">
							</div>
							<div class="udb-form-row">
								<input type="email" id="udb-subscription-email" class="udb-input" placeholder="<?php esc_attr_e( 'Email', 'ultimate-dashboard' ); ?>" autocomplete="email">
							</div>
							<div class="udb-form-row">
								<button type="button" class="button button-primary button-large udb-button subscribe-button">
									<?php esc_html_e( 'Subscribe', 'ultimate-dashboard' ); ?>
								</button>
							</div>
							<div class="udb-form-row udb-onboarding-wizard-skip-discount">
								<a href="#"><?php esc_html_e( 'No, I don\'t want any Discount :(', 'ultimate-dashboard' ); ?></a>
							</div>
						</div>
					</div>

					<div class="udb-onboarding-wizard-slide udb-finished-slide" data-udb-step="finished">
						<header>
							<h2><?php esc_html_e( 'Setup Complete! 🎉', 'ultimate-dashboard' ); ?></h2>

							<p data-udb-show-on="subscribe" class="is-hidden">
								<?php echo wp_kses_post( __( 'We\'ll send you an email with a <strong> discount code for Ultimate Dashboard PRO </strong> shortly.', 'ultimate-dashboard' ) ); ?>
							</p>

							<p>
								<?php echo wp_kses_post( __( 'What\'s next? Explore all features from the <strong>"Ultimate Dash..."</strong> admin menu.', 'ultimate-dashboard' ) ); ?>
							</p>

							<p data-udb-show-on="skip-discount" class="is-hidden">
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
						<button type="button" class="onboarding-wizard-skip-button skip-button"><?php esc_html_e( 'Skip', 'ultimate-dashboard' ); ?></button>
					</div>
					<div class="heatbox-footer-item">
						<div class="udb-dots"></div>
					</div>
					<div class="heatbox-footer-item">
						<button type="button" class="button button-large button-primary udb-button save-button">
							<?php $is_wizard ? esc_html_e( 'Next', 'ultimate-dashboard' ) : esc_html_e( 'Done', 'ultimate-dashboard' ); ?>
						</button>
					</div>
				</footer>

				<div class="udb-discount-notif for-discount is-hidden">
					<?php
					if ( $is_wizard ) {
						echo wp_kses_post( __( 'Don\'t miss out.<br> <strong>This discount won\'t come back!</strong>', 'ultimate-dashboard' ) );
					} else {
						/* translators: %1$s: The plugin the user came from. */
						echo wp_kses_post( sprintf( __( 'This is an exclusive discount for %1$s users.<br> <strong>This discount will not come back!</strong>', 'ultimate-dashboard' ), esc_html( $referrer_name ) ) );
					}
					?>
				</div>

			</div>

			<?php if ( $is_wizard ) : ?>
				<div class="heatbox-footer skip-onboarding-wizard">
					<div class="heatbox-footer-item">
						<a href="#" id="skip-setup-onboarding-wizard" class="skip-onboarding-wizard-link"><?php esc_html_e( 'Skip Setup Wizard', 'ultimate-dashboard' ); ?></a>
					</div>
				</div>

				<div class="heatbox-footer onboarding-wizard-explore-settings">
					<div class="heatbox-footer-item">
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=udb_widgets&page=udb_settings' ) ); ?>" id="onboarding-wizard-explore-settings" class="onboarding-wizard-explore-settings-link is-hidden"><?php esc_html_e( 'Explore Settings', 'ultimate-dashboard' ); ?></a>
					</div>
				</div>
			<?php endif; ?>

		</div>

	</div>

	<?php

};
