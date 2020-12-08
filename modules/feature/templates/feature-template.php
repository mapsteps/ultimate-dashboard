<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
use \Udb\Setup;

return function () {

	$saved_modules = Setup::saved_modules();

	$features = array(
		array(
			'title'   => __( 'White Label', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/white-label.png',
			'text'    => __( 'This is some text', 'ultimate-dashboard' ),
			'feature' => 'white_label',
		),
		array(
			'title'   => __( 'Login Customizer', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/login-customizer.png',
			'text'    => __( 'This is some text', 'ultimate-dashboard' ),
			'feature' => 'login_customizer',
		),
		array(
			'title'   => __( 'Admin Pages', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-pages.png',
			'text'    => __( 'This is some text', 'ultimate-dashboard' ),
			'feature' => 'admin_pages',
		),
		array(
			'title'   => __( 'Admin Menu Editor', 'ultimate-dashboard' ),
			'img'     => ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/feature/assets/img/admin-menu.png',
			'text'    => __( 'This is some text', 'ultimate-dashboard' ),
			'feature' => 'admin_menu_editor',
		),
	)

	?>

	<div class="wrap heatbox-wrap">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form method="post" action="options.php" class="udb-dashboard-form">

			<?php foreach ( $features as $feature ) { ?>
				<div class="heatbox">

					<h2><?php echo $feature['title']; ?></h2>

					<table class="form-table">
						<tr>
							<td>
								<img src="<?php echo esc_url( $feature['img'] ); ?>" alt="<?php echo esc_attr( $feature['title'] ); ?>">
							</td>
							<td>
								<p>
									<?php echo $feature['text']; ?>
								</p>
							</td>
						</tr>
						<tr class="status-wrap">
							<td>
								<div class="status">
									<p><?php _e( 'Status: ', 'ultimate-dashboard' ); ?></p>
									<p class="status-code" data-active-text="<?php _e( 'Active', 'ultimate-dashboard' ); ?>" data-inactive-text="<?php _e( 'Inactive', 'ultimate-dashboard' ); ?>">
										<?php echo empty( $saved_modules ) || $saved_modules[$feature['feature']] === "true" ? '<span class="active">' . __( 'Active', 'ultimate-dashboard' ) . '</span>' : '<span class="inactive">' . __( 'Inactive', 'ultimate-dashboard' ) . '</span>'; ?>
									</p>
								</div>
							</td>
							<td class="field">
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
							</td>
						</tr>
					</table>

					<input type="hidden" name="udb_module_nonce" id="udb_module_nonce" value="<?php echo esc_attr( wp_create_nonce( 'udb_module_nonce_action' ) ); ?>" />

				</div>

			<?php } ?>

		</form>

	</div>

	<?php

};
