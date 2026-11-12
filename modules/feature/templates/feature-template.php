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

	// Icons: 24px grid, 1.75px rounded strokes in the accent color (currentColor).
	$features = array(
		array(
			'title'   => __( 'White Label', 'ultimate-dashboard' ),
			'icon'    => '<path d="M3 12.2V5a2 2 0 0 1 2-2h7.2a2 2 0 0 1 1.4.6l7.2 7.2a2 2 0 0 1 0 2.8l-7.6 7.6a2 2 0 0 1-2.8 0l-6.8-6.8a2 2 0 0 1-.6-1.4z"/><circle cx="7.5" cy="7.5" r="1.5" fill="currentColor" stroke="none"/>',
			'text'    => __( 'White label & rebrand the WordPress admin area with the White Label module.', 'ultimate-dashboard' ),
			'feature' => 'white_label',
		),
		array(
			'title'   => __( 'Login Customizer', 'ultimate-dashboard' ),
			'icon'    => '<rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="12" cy="10" r="2.5"/><path d="M7.5 16.5a4.5 4.5 0 0 1 9 0"/>',
			'text'    => __( 'Fully customize the login screen, directly within the WordPress customizer.', 'ultimate-dashboard' ),
			'feature' => 'login_customizer',
		),
		array(
			'title'   => __( 'Login Redirect', 'ultimate-dashboard' ),
			'icon'    => '<path d="M4 8.5h14M14.5 5 18 8.5 14.5 12M20 15.5H6M9.5 12 6 15.5 9.5 19"/>',
			'text'    => __( 'Change the WordPress login url, redirect users after login & set a <code>/wp-admin/</code> redirect for non logged-in users.', 'ultimate-dashboard' ),
			'feature' => 'login_redirect',
		),
		array(
			'title'   => __( 'Admin Pages', 'ultimate-dashboard' ),
			'icon'    => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h4"/>',
			'text'    => __( 'Create useful custom admin pages for your customers with the Admin Pages module.', 'ultimate-dashboard' ),
			'feature' => 'admin_pages',
		),
		array(
			'title'   => __( 'Admin Menu Editor', 'ultimate-dashboard' ),
			'icon'    => '<rect x="3" y="4" width="18" height="16" rx="3"/><path d="M6 4h3v16H6a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3z" fill="currentColor" stroke="none"/>',
			'text'    => __( 'Rearrange, hide & add new admin menu items for specific users & user roles with the Admin Menu Editor module.', 'ultimate-dashboard' ),
			'feature' => 'admin_menu_editor',
		),
		array(
			'title'   => __( 'Admin Bar Editor', 'ultimate-dashboard' ),
			'icon'    => '<rect x="3" y="4" width="18" height="16" rx="3"/><path d="M6 4h12a3 3 0 0 1 3 3v2H3V7a3 3 0 0 1 3-3z" fill="currentColor" stroke="none"/>',
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
							<svg class="udb-module-icon" viewBox="0 0 24 24" aria-hidden="true">
								<?php echo $feature['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Static SVG markup from the array above. ?>
							</svg>
							<?php echo esc_html( $feature['title'] ); ?>
						</h2>
						<div class="heatbox-content">
							<p class="heatbox-notice is-error" role="alert" hidden></p>
							<p><?php echo wp_kses_post( $feature['text'] ); ?></p>
						</div>
						<div class="udb-module-status">
							<span class="heatbox-badge <?php echo $is_on ? 'is-info' : 'is-neutral'; ?>"><?php $is_on ? esc_html_e( 'Active', 'ultimate-dashboard' ) : esc_html_e( 'Inactive', 'ultimate-dashboard' ); ?></span>
							<label class="toggle-switch">
								<input type="checkbox" name="<?php echo esc_attr( $feature['feature'] ); ?>" <?php checked( $is_on ); ?>>
								<span class="switch-track"><span class="switch-thumb"></span></span>
								<span class="screen-reader-text"><?php echo esc_html( $feature['title'] ); ?></span>
							</label>
						</div>
					</div>

				<?php endforeach; ?>

			</div>

		</div>

	</div>

	<?php

};
