<?php
/**
 * Settings page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$setting_tab_menus = array(
		array(
			'id'     => 'widgets',
			'text'   => __( 'Dashboard Widgets', 'ultimate-dashboard' ),
			'active' => true,
		),
		array(
			'id'     => 'page-builder-dashboard',
			'text'   => __( 'Page Builder Dashboard', 'ultimate-dashboard' ),
			'is_pro' => true,
		),
		array(
			'id'   => 'general',
			'text' => __( 'General', 'ultimate-dashboard' ),
		),
		array(
			'id'   => 'custom-css',
			'text' => __( 'Custom CSS', 'ultimate-dashboard' ),
		),
	);

	$setting_tab_menus = apply_filters( 'udb_setting_tab_menus', $setting_tab_menus );
	?>

	<div class="wrap heatbox-wrap udb-settings-page">

		<div class="heatbox-header heatbox-has-tab-nav heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php esc_html_e( 'Ultimate Dashboard', 'ultimate-dashboard' ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php esc_html_e( 'The #1 plugin to customize your WordPress dashboard.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

				<nav>
					<ul class="heatbox-tab-nav">
						<?php foreach ( $setting_tab_menus as $tab_index => $tab ) : ?>
							<?php
							if ( ! empty( $tab['is_pro'] ) && ! udb_is_pro_active() ) {
								continue;
							}
							?>
							<li class="heatbox-tab-nav-item <?php echo esc_attr( $tab['id'] ); ?>-panel">
								<a href="#<?php echo esc_attr( $tab['id'] ); ?>"><?php echo esc_html( $tab['text'] ); ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

			<h1 style="display: none;"></h1>

			<form method="post" action="options.php" class="udb-settings-form">

				<?php settings_fields( 'udb-settings-group' ); ?>

				<div>
					<div class="heatbox-admin-panel udb-widgets-panel">
						<div class="heatbox is-grouped">
							<?php do_settings_sections( 'udb-widget-settings' ); ?>
						</div>

						<div class="heatbox">
							<?php do_settings_sections( 'udb-widget-styling-settings' ); ?>
						</div>

						<div class="heatbox">
							<?php do_settings_sections( 'udb-welcome-panel-settings' ); ?>
						</div>
					</div>

					<?php do_action( 'udb_after_widgets_panel' ); ?>

					<?php if ( udb_is_pro_active() ) : ?>
						<div class="heatbox-admin-panel udb-page-builder-dashboard-panel">
							<div class="heatbox">
								<?php do_settings_sections( 'udb-page-builder-dashboard-settings' ); ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="heatbox-admin-panel udb-general-panel">
						<div class="heatbox">
							<?php do_settings_sections( 'udb-general-settings' ); ?>
						</div>

						<div class="heatbox">
							<?php do_settings_sections( 'udb-misc-settings' ); ?>
						</div>
					</div>

					<?php do_action( 'udb_after_general_panel' ); ?>

					<div class="heatbox-admin-panel udb-custom-css-panel">
						<div class="heatbox">
							<?php do_settings_sections( 'udb-custom-css-settings' ); ?>
						</div>
					</div>

					<?php do_action( 'udb_after_custom_panel' ); ?>
				</div>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</form>

			<?php if ( ! udb_is_pro_active() ) { ?>

			<div class="heatbox-divider"></div>

			<?php } ?>

		</div>

		<?php if ( ! udb_is_pro_active() ) { ?>

		<div class="heatbox-container heatbox-container-wide heatbox-container-center featured-products">

			<h2><?php esc_html_e( 'Check out our other free WordPress products!', 'ultimate-dashboard' ); ?></h2>

			<ul class="products">
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/swift-control.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php esc_html_e( 'Better Admin Bar', 'ultimate-dashboard' ); ?></h3>
						<p class="subheadline"><?php esc_html_e( 'Replace the boring WordPress Admin Bar with this!', 'ultimate-dashboard' ); ?></p>
						<p><?php esc_html_e( 'Better Admin Bar is the plugin that make your clients love WordPress. It drastically improves the user experience when working with WordPress and allows you to replace the boring WordPress admin bar with your own navigation panel.', 'ultimate-dashboard' ); ?></p>
						<a href="https://wordpress.org/plugins/better-admin-bar/" target="_blank" class="button"><?php esc_html_e( 'View Features', 'ultimate-dashboard' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/page-builder-framework.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php esc_html_e( 'Page Builder Framework', 'ultimate-dashboard' ); ?></h3>
						<p class="subheadline"><?php esc_html_e( 'The only Theme you\'ll ever need.', 'ultimate-dashboard' ); ?></p>
						<p class="description"><?php esc_html_e( 'With its minimalistic design the Page Builder Framework theme is the perfect foundation for your next project. Build blazing fast websites with a theme that is easy to use, lightweight & highly customizable.', 'ultimate-dashboard' ); ?></p>
						<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank" class="button"><?php esc_html_e( 'View Features', 'ultimate-dashboard' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/responsive-youtube-vimeo-popup/" target="_blank">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/wp-video-popup.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php esc_html_e( 'WP Video Popup', 'ultimate-dashboard' ); ?></h3>
						<p class="subheadline"><?php esc_html_e( 'The #1 Video Popup Plugin for WordPress.', 'ultimate-dashboard' ); ?></p>
						<p><?php esc_html_e( 'Add beautiful responsive YouTube & Vimeo video lightbox popups to any post, page or custom post type of website without sacrificing performance.', 'ultimate-dashboard' ); ?></p>
						<a href="https://wordpress.org/plugins/responsive-youtube-vimeo-popup/" target="_blank" class="button"><?php esc_html_e( 'View Features', 'ultimate-dashboard' ); ?></a>
					</div>
				</li>
			</ul>

			<p class="credit"><?php esc_html_e( 'Made with ❤ in Torsby, Sweden', 'ultimate-dashboard' ); ?></p>

		</div>

		<?php } ?>

	</div>

	<?php
};
