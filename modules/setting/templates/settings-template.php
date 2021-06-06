<?php
/**
 * Settings page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap udb-settings-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php _e( 'Ultimate Dashboard', 'ultimate-dashboard' ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php _e( 'The #1 plugin to customize your WordPress dashboard.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">

			<h1 style="display: none;"></h1>

			<form method="post" action="options.php" class="udb-settings-form">

				<?php settings_fields( 'udb-settings-group' ); ?>

				<div class="heatbox is-grouped">
					<?php do_settings_sections( 'udb-widget-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-widget-styling-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-welcome-panel-settings' ); ?>
				</div>

				<?php if ( udb_is_pro_active() ) : ?>
					<div class="heatbox">
						<?php do_settings_sections( 'udb-page-builder-dashboard-settings' ); ?>
					</div>
				<?php endif; ?>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-general-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-advanced-settings' ); ?>
				</div>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-misc-settings' ); ?>
				</div>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</form>

			<?php if ( ! udb_is_pro_active() ) { ?>

			<div class="heatbox-divider"></div>

			<?php } ?>

		</div>

		<?php if ( ! udb_is_pro_active() ) { ?>

		<div class="heatbox-container heatbox-container-wide heatbox-container-center featured-products">

			<h2><?php _e( 'Check out our other free WordPress products!', 'ultimate-dashboard' ); ?></h2>

			<ul class="products">
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/swift-control/" target="_blank">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/swift-control.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'WP Swift Control', 'ultimate-dashboard' ); ?></h3>
						<p class="subheadline"><?php _e( 'Replace the boring WordPress Admin Bar with this!', 'ultimate-dashboard' ); ?></p>
						<p><?php _e( 'Swift Control is the plugin that make your clients love WordPress. It drastically improves the user experience when working with WordPress and allows you to replace the boring WordPress admin bar with your own navigation panel.', 'ultimate-dashboard' ); ?></p>
						<a href="https://wordpress.org/plugins/swift-control/" target="_blank" class="button"><?php _e( 'View Features', 'ultimate-dashboard' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/page-builder-framework.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'Page Builder Framework', 'ultimate-dashboard' ); ?></h3>
						<p class="subheadline"><?php _e( 'The only Theme you\'ll ever need.', 'ultimate-dashboard' ); ?></p>
						<p class="description"><?php _e( 'With its minimalistic design the Page Builder Framework theme is the perfect foundation for your next project. Build blazing fast websites with a theme that is easy to use, lightweight & highly customizable.', 'ultimate-dashboard' ); ?></p>
						<a href="https://wordpress.org/themes/page-builder-framework/" target="_blank" class="button"><?php _e( 'View Features', 'ultimate-dashboard' ); ?></a>
					</div>
				</li>
				<li class="heatbox">
					<a href="https://wordpress.org/plugins/responsive-youtube-vimeo-popup/" target="_blank">
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/wp-video-popup.jpg">
					</a>
					<div class="heatbox-content">
						<h3><?php _e( 'WP Video Popup', 'ultimate-dashboard' ); ?></h3>
						<p class="subheadline"><?php _e( 'The #1 Video Popup Plugin for WordPress.', 'ultimate-dashboard' ); ?></p>
						<p><?php _e( 'Add beautiful responsive YouTube & Vimeo video lightbox popups to any post, page or custom post type of website without sacrificing performance.', 'ultimate-dashboard' ); ?></p>
						<a href="https://wordpress.org/plugins/responsive-youtube-vimeo-popup/" target="_blank" class="button"><?php _e( 'View Features', 'ultimate-dashboard' ); ?></a>
					</div>
				</li>
			</ul>

			<p class="credit"><?php _e( 'Made with ❤ in Aschaffenburg, Germany', 'ultimate-dashboard' ); ?></p>

		</div>

		<?php } ?>

	</div>

	<?php
};
