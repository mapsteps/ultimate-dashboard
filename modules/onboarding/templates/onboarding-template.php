<?php
/**
 * Onboarding page template (both variants).
 *
 * One heatbox box; each step is a <section> the script shows one at a time.
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
	 * Output a toggle row: the label on the left, a heatbox toggle on the right. The whole row is clickable.
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
		<label class="udb-onboarding-row" for="<?php echo esc_attr( $id ); ?>">
			<?php echo esc_html( $label ); ?>
			<span class="toggle-switch">
				<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $value ); ?>" data-udb-group="<?php echo esc_attr( $group ); ?>" <?php checked( $checked ); ?> <?php disabled( $locked ); ?>>
				<span class="switch-track"><span class="switch-thumb"></span></span>
			</span>
		</label>
		<?php
	};
	?>

	<div class="wrap heatbox-wrap udb-onboarding">

		<div class="heatbox-header heatbox-margin-bottom">
			<div class="heatbox-container heatbox-container-center">
				<div class="logo-container">
					<div>
						<h1 class="title">
							<?php esc_html_e( 'Welcome to Ultimate Dashboard', 'ultimate-dashboard' ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</h1>
						<p class="subtitle">
							<?php
							if ( $is_wizard ) {
								esc_html_e( 'Complete the Setup Wizard and get started with Ultimate Dashboard in less than a minute.', 'ultimate-dashboard' );
							} else {
								echo wp_kses_post( __( 'Complete the 1-Click Setup & get an <strong>exclusive Discount</strong> on <strong>Ultimate Dashboard PRO!</strong>', 'ultimate-dashboard' ) );
							}
							?>
						</p>
					</div>
					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="">
					</div>
				</div>
			</div>
		</div>

		<div class="heatbox-container heatbox-container-center udb-onboarding-container">

			<hr class="wp-header-end">

			<div class="heatbox">

				<section class="udb-onboarding-step" data-udb-step="modules">
					<h2 tabindex="-1"><?php $is_wizard ? esc_html_e( 'Features', 'ultimate-dashboard' ) : esc_html_e( '1 Click Setup', 'ultimate-dashboard' ); ?></h2>
					<div class="heatbox-content">
						<?php if ( ! $is_wizard ) : ?>
							<img src="<?php echo esc_url( $img_url . '/features.svg' ); ?>" alt="" class="udb-onboarding-illustration">
						<?php endif; ?>

						<p>
							<?php
							if ( $is_wizard ) {
								esc_html_e( 'Select the features you\'d like to enable. You can change them later in the "Modules" tab.', 'ultimate-dashboard' );
							} else {
								esc_html_e( 'Choose what features you would like to enable/disable. You can always manage this later from the Modules page.', 'ultimate-dashboard' );
							}
							?>
						</p>

						<div>
							<?php
							foreach ( $modules as $module => $label ) {
								$is_on = ! ( isset( $saved_modules[ $module ] ) && 'false' === $saved_modules[ $module ] );

								// Erident users come for the login customizer: keep it on.
								$locked = 'erident' === $referrer && 'login_customizer' === $module && $is_on;

								$toggle( 'udb_modules__' . $module, $module, $label, $is_on, 'modules', $locked );
							}
							?>
						</div>
					</div>
				</section>

				<?php if ( $is_wizard ) : ?>

					<section class="udb-onboarding-step" data-udb-step="widgets" hidden>
						<h2 tabindex="-1"><?php esc_html_e( 'Dashboard Widgets', 'ultimate-dashboard' ); ?></h2>
						<div class="heatbox-content">
							<p><?php esc_html_e( 'Let\'s clean up your dashboard. Remove some or all of the default WordPress widgets.', 'ultimate-dashboard' ); ?></p>

							<div>
								<?php $toggle( 'udb_widgets__remove-all', 'remove-all', __( 'Remove all Dashboard Widgets', 'ultimate-dashboard' ), isset( $settings['remove-all'] ), 'widgets' ); ?>
							</div>

							<h3><?php esc_html_e( 'Or remove individual widgets', 'ultimate-dashboard' ); ?></h3>

							<div>
								<?php
								$toggle( 'udb_widgets__welcome_panel', 'welcome_panel', __( 'Welcome Panel', 'ultimate-dashboard' ), isset( $settings['welcome_panel'] ), 'widgets' );

								foreach ( ( new Widget_Helper() )->get_default() as $widget_id => $widget ) {
									$title = isset( $widget['title_stripped'] ) ? $widget['title_stripped'] : $widget_id;
									$toggle( 'udb_widgets__' . $widget_id, $widget_id, $title, isset( $settings[ $widget_id ] ), 'widgets' );
								}
								?>
							</div>
						</div>
					</section>

					<?php $selected_roles = ( new Admin_Bar_Helper() )->roles_to_remove(); ?>

					<section class="udb-onboarding-step" data-udb-step="settings" hidden>
						<h2 tabindex="-1"><?php esc_html_e( 'General Settings', 'ultimate-dashboard' ); ?></h2>
						<div class="heatbox-content">
							<p><?php esc_html_e( 'Next, let\'s simplify the WordPress Dashboard interface by removing extra elements for a cleaner look.', 'ultimate-dashboard' ); ?></p>

							<div>
								<?php
								$toggle( 'udb_settings__remove_help_tab', 'remove_help_tab', __( 'Remove Help Tab', 'ultimate-dashboard' ), isset( $settings['remove_help_tab'] ), 'settings' );
								$toggle( 'udb_settings__remove_screen_options', 'remove_screen_options', __( 'Remove Screen Options Tab', 'ultimate-dashboard' ), isset( $settings['remove_screen_options'] ), 'settings' );
								?>
							</div>

							<h3 id="udb-onboarding-roles"><?php esc_html_e( 'Hide Admin Bar on the Frontend for:', 'ultimate-dashboard' ); ?></h3>

							<div class="setting-fields udb-onboarding-roles" role="group" aria-labelledby="udb-onboarding-roles">
								<?php
								$roles = array( 'all' => __( 'All', 'ultimate-dashboard' ) );

								foreach ( wp_roles()->roles as $role_key => $role ) {
									$roles[ $role_key ] = translate_user_role( $role['name'] );
								}

								foreach ( $roles as $role_key => $role_name ) :
									?>
									<div class="setting-field">
										<label class="label checkbox-label">
											<?php echo esc_html( $role_name ); ?>
											<input type="checkbox" value="<?php echo esc_attr( $role_key ); ?>" data-udb-group="roles" <?php checked( in_array( $role_key, $selected_roles, true ) ); ?>>
											<div class="indicator"></div>
										</label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</section>

					<?php
					$login_redirect = get_option( 'udb_login_redirect' );
					$login_slug     = isset( $login_redirect['login_url_slug'] ) ? trim( $login_redirect['login_url_slug'], '/' ) : '';
					?>

					<section class="udb-onboarding-step" data-udb-step="login_url" hidden>
						<h2 tabindex="-1"><?php esc_html_e( 'Custom Login URL', 'ultimate-dashboard' ); ?></h2>
						<div class="heatbox-content">
							<p><?php esc_html_e( 'Customize the login URL to enhance security and avoid common login page attacks.', 'ultimate-dashboard' ); ?></p>

							<div class="udb-onboarding-field">
								<label for="udb_login_redirect"><?php esc_html_e( 'New Login URL', 'ultimate-dashboard' ); ?></label>
								<div class="heatbox-input-group">
									<span class="heatbox-input-prefix" title="<?php echo esc_attr( trailingslashit( site_url() ) ); ?>"><?php echo esc_html( trailingslashit( site_url() ) ); ?></span>
									<input type="text" id="udb_login_redirect" value="<?php echo esc_attr( $login_slug ); ?>" placeholder="login">
								</div>
								<p class="description">
									<?php
									// translators: %1$s: The site URL.
									echo wp_kses_post( sprintf( __( 'This will replace your login URL (<code>%1$s/wp-login.php</code>) and help secure your site by making the login page less predictable.', 'ultimate-dashboard' ), esc_url( site_url() ) ) );
									?>
								</p>
							</div>
						</div>
					</section>

				<?php endif; ?>

				<section class="udb-onboarding-step" data-udb-step="subscription" hidden>
					<h2 tabindex="-1"><?php esc_html_e( 'Exclusive Discount 🥳', 'ultimate-dashboard' ); ?></h2>
					<div class="heatbox-content">
						<?php if ( ! $is_wizard ) : ?>
							<img src="<?php echo esc_url( $img_url . '/discount.svg' ); ?>" alt="" class="udb-onboarding-illustration">
						<?php endif; ?>

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

						<div class="udb-onboarding-field">
							<label for="udb-subscription-name"><?php esc_html_e( 'Name', 'ultimate-dashboard' ); ?></label>
							<input type="text" id="udb-subscription-name" class="widefat" autocomplete="name">
						</div>

						<div class="udb-onboarding-field">
							<label for="udb-subscription-email"><?php esc_html_e( 'Email', 'ultimate-dashboard' ); ?></label>
							<input type="email" id="udb-subscription-email" class="widefat" autocomplete="email">
						</div>

						<p>
							<?php
							if ( $is_wizard ) {
								echo wp_kses_post( __( 'Don\'t miss out.<br> <strong>This discount won\'t come back!</strong>', 'ultimate-dashboard' ) );
							} else {
								/* translators: %1$s: The plugin the user came from. */
								echo wp_kses_post( sprintf( __( 'This is an exclusive discount for %1$s users.<br> <strong>This discount will not come back!</strong>', 'ultimate-dashboard' ), esc_html( $referrer_name ) ) );
							}
							?>
						</p>

						<div class="udb-onboarding-actions">
							<button type="button" class="button button-primary button-larger udb-onboarding-subscribe"><?php esc_html_e( 'Subscribe', 'ultimate-dashboard' ); ?></button>
							<a href="#" class="udb-onboarding-skip-discount"><?php esc_html_e( 'No, I don\'t want any Discount :(', 'ultimate-dashboard' ); ?></a>
						</div>
					</div>
				</section>

				<section class="udb-onboarding-step" data-udb-step="finished" hidden>
					<h2 tabindex="-1"><?php esc_html_e( 'Setup Complete! 🎉', 'ultimate-dashboard' ); ?></h2>
					<div class="heatbox-content">
						<p data-udb-show-on="subscribe" hidden>
							<?php echo wp_kses_post( __( 'We\'ll send you an email with a <strong> discount code for Ultimate Dashboard PRO </strong> shortly.', 'ultimate-dashboard' ) ); ?>
						</p>

						<p>
							<?php echo wp_kses_post( __( 'What\'s next? Explore all features from the <strong>"Ultimate Dash..."</strong> admin menu.', 'ultimate-dashboard' ) ); ?>
						</p>

						<p data-udb-show-on="skip-discount" hidden>
							<strong><?php esc_html_e( 'This is your last chance to get an exclusive discount on Ultimate Dashboard PRO at the link below! 👇👇👇', 'ultimate-dashboard' ); ?></strong>
						</p>

						<div class="udb-onboarding-actions">
							<a href="https://ultimatedashboard.io/special-discount/" target="_blank" class="button button-primary button-larger">
								<?php esc_html_e( 'Grab your Discount', 'ultimate-dashboard' ); ?>
								<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'ultimate-dashboard' ); ?></span>
							</a>
							<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=udb_widgets&page=udb_settings' ) ); ?>" class="button button-larger"><?php esc_html_e( 'Explore Settings', 'ultimate-dashboard' ); ?></a>
						</div>
					</div>
				</section>

				<div class="udb-onboarding-footer">
					<button type="button" class="button button-larger udb-onboarding-skip"><?php esc_html_e( 'Skip', 'ultimate-dashboard' ); ?></button>
					<span class="udb-onboarding-progress" hidden></span>
					<button type="button" class="button button-primary button-larger udb-onboarding-next"><?php $is_wizard ? esc_html_e( 'Next', 'ultimate-dashboard' ) : esc_html_e( 'Done', 'ultimate-dashboard' ); ?></button>
				</div>

			</div>

			<?php if ( $is_wizard ) : ?>
				<p class="udb-onboarding-skip-all">
					<a href="#"><?php esc_html_e( 'Skip Setup Wizard', 'ultimate-dashboard' ); ?></a>
				</p>
			<?php endif; ?>

		</div>

	</div>

	<?php

};
