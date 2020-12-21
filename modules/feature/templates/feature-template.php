<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
use Udb\Setup;

return function () {

	$saved_modules = Setup::saved_modules();

	$features = array(
		array(
			'title'   => __( 'White Label', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/white-label.png',
			'text'    => __( 'White label & rebrand the WordPress Admin area with the White Label Module.', 'ultimate-dashboard' ),
			'feature' => 'white_label',
		),
		array(
			'title'   => __( 'Login Customizer', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/login-customizer.png',
			'text'    => __( 'Fully customize the login screen, directly within the WordPress customizer.', 'ultimate-dashboard' ),
			'feature' => 'login_customizer',
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
			'text'    => __( 'Rearrange, hide & rename admin menu items for specific user roles with the Admin Menu Editor module.', 'ultimate-dashboard' ),
			'feature' => 'admin_menu_editor',
		),
	)

	?>

	<div class="wrap heatbox-wrap">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form method="post" action="options.php" class="udb-feature-form">

			<?php foreach ( $features as $feature ) { ?>

				<div class="heatbox">

					<h2><img src="<?php echo esc_url( $feature['img'] ); ?>" alt="<?php echo esc_attr( $feature['title'] ); ?>"> <?php echo $feature['title']; ?></h2>

						<div class="heatbox-content">
							<p>
								<?php echo $feature['text']; ?>
							</p>
						</div>

						<div class="feature-status">
							<div class="status">
								<span><?php _e( 'Status: ', 'ultimate-dashboard' ); ?></span>
								<span class="status-code" data-active-text="<?php _e( 'Active', 'ultimate-dashboard' ); ?>" data-inactive-text="<?php _e( 'Inactive', 'ultimate-dashboard' ); ?>">
									<?php echo empty( $saved_modules ) || $saved_modules[$feature['feature']] === "true" ? '<span class="active">' . __( 'Active', 'ultimate-dashboard' ) . '</span>' : '<span class="inactive">' . __( 'Inactive', 'ultimate-dashboard' ) . '</span>'; ?>
								</span>
							</div>
							<div class="switch-control is-rounded">
								<label for="udb_is_active_<?php echo $feature['feature']; ?>">
									<input
										type="checkbox"
										name="<?php echo esc_attr( $feature['feature'] ); ?>"
										id="udb_is_active_<?php echo $feature['feature']; ?>"
										<?php checked( empty( $saved_modules ) || $saved_modules[$feature['feature']] === "true" ); ?> >
									<span class="switch"></span>
								</label>
							</div>
						</div>

					<input type="hidden" name="udb_module_nonce" id="udb_module_nonce" value="<?php echo esc_attr( wp_create_nonce( 'udb_module_nonce_action' ) ); ?>" />

				</div>

			<?php } ?>

		</form>

	</div>

	<?php

};
