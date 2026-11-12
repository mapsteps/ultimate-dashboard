<?php
/**
 * Modules page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
use Udb\Setup;

return function () {

	$module        = new Setup();
	$saved_modules = $module->saved_modules();

	$features = array(
		array(
			'title'   => __( 'White Label', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/white-label.png',
			'text'    => __( 'White label & rebrand the WordPress admin area with the White Label module.', 'ultimate-dashboard' ),
			'feature' => 'white_label',
		),
		array(
			'title'   => __( 'Login Customizer', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/login-customizer.png',
			'text'    => __( 'Fully customize the login screen, directly within the WordPress customizer.', 'ultimate-dashboard' ),
			'feature' => 'login_customizer',
		),
		array(
			'title'   => __( 'Login Redirect', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/login-redirect.png',
			'text'    => __( 'Change the WordPress login url, redirect users after login & set a <code>/wp-admin/</code> redirect for non logged-in users.', 'ultimate-dashboard' ),
			'feature' => 'login_redirect',
		),
		array(
			'title'   => __( 'Admin Pages', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-pages.png',
			'text'    => __( 'Create useful custom admin pages for your customers with the Admin Pages module.', 'ultimate-dashboard' ),
			'feature' => 'admin_pages',
		),
		array(
			'title'   => __( 'Admin Menu Editor', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-menu.png',
			'text'    => __( 'Rearrange, hide & add new admin menu items for specific users & user roles with the Admin Menu Editor module.', 'ultimate-dashboard' ),
			'feature' => 'admin_menu_editor',
		),
		array(
			'title'   => __( 'Admin Bar Editor', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-bar.png',
			'text'    => __( 'Rearrange, hide & add new items to the WordPress toolbar with the Admin Bar Editor module.', 'ultimate-dashboard' ),
			'feature' => 'admin_bar_editor',
		),
	)

	?>

	<div class="wrap heatbox-wrap udb-features-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<h1 class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</h1>
						<p class="subtitle"><?php esc_html_e( 'Enable/disable Ultimate Dashboard features.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

			<hr class="wp-header-end">

			<div class="heatbox-columns is-three udb-modules">

				<?php
				foreach ( $features as $feature ) :
					$is_on = empty( $saved_modules ) || 'true' === $saved_modules[ $feature['feature'] ];
					?>

					<div class="heatbox">
						<h2>
							<img src="<?php echo esc_url( $feature['img'] ); ?>" alt="">
							<?php echo esc_html( $feature['title'] ); ?>
							<label class="toggle-switch">
								<input type="checkbox" name="<?php echo esc_attr( $feature['feature'] ); ?>" <?php checked( $is_on ); ?>>
								<span class="switch-track"><span class="switch-thumb"></span></span>
								<span class="screen-reader-text"><?php echo esc_html( $feature['title'] ); ?></span>
							</label>
						</h2>
						<div class="heatbox-content">
							<p class="heatbox-notice is-error" role="alert" hidden></p>
							<p><?php echo wp_kses_post( $feature['text'] ); ?></p>
						</div>
					</div>

				<?php endforeach; ?>

			</div>

		</div>

	</div>

	<?php

};
