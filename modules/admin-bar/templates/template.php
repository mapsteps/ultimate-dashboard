<?php
/**
 * Admin menu page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\AdminBar\Admin_Bar_Module;
use Udb\Vars;

/**
 * This function is being called in class-admin-bar-module.php.
 *
 * @param Admin_Bar_Module $module
 */
return function ( $module ) {

	$existing_menu = Vars::get( 'existing_admin_bar_menu' );
	$existing_menu = $module->nodes_to_array( $existing_menu );

	$saved_menu  = get_option( 'udb_admin_bar', array() );
	$saved_menu  = apply_filters( 'udb_ms_admin_bar_saved_menu', $saved_menu );
	$parsed_menu = ! $saved_menu ? $existing_menu : $module->parse_menu( $saved_menu, $existing_menu );
	$parsed_menu = $module->parse_frontend_items( $parsed_menu );

	// error_log( "existingMenu:\n" . print_r( $existing_menu, true ) );

	// error_log( "parsedMenu:\n" . print_r( $parsed_menu, true ) );

	// error_log( "builderItems:\n" . print_r( $module->to_builder_format( $parsed_menu ), true ) );

	wp_localize_script(
		'udb-admin-bar',
		'udbAdminBarBuilder',
		array(
			'existingMenu' => $existing_menu,
			'parsedMenu'   => $parsed_menu,
			'builderItems' => $module->to_builder_format( $parsed_menu ),
		)
	);

	?>

	<div class="wrap heatbox-wrap udb-admin-bar udb-menu-builder-editor-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<span class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</span>
						<p class="subtitle"><?php esc_html_e( 'Fully customize the WordPress admin bar.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
					</div>

				</div>

			</div>

		</div>

		<div class="heatbox-container heatbox-container-center">
			<h1 style="display: none;"></h1>
		</div>

		<div class="heatbox-container heatbox-container-center heatbox-column-container">
			<div class="heatbox-main">

				<?php if ( ! udb_is_pro_active() ) : ?>

					<div class="udb-pro-upgrade-nag">
						<p><?php esc_html_e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>
						<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=admin_bar_link&utm_campaign=udb" class="button button-large button-primary" target="_blank">
							<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
						</a>
					</div>

				<?php endif; ?>

				<?php do_action( 'udb_admin_bar_before_form' ); ?>

				<form action="options.php" method="post" class="udb-menu-builder--edit-form">
	
					<div class="heatbox heatbox-admin-panel udb-menu-builder-box udb-menu-builder-box-panel">

						<div class="udb-menu-builder-box--header">
							<h2 class="udb-menu-builder-box--title">
								<?php esc_html_e( 'Admin Bar Editor', 'ultimate-dashboard' ); ?>
							</h2>

							<?php do_action( 'udb_admin_bar_header' ); ?>
						</div>

						<div class="udb-menu-builder--edit-area">
							<div id="udb-menu-builder--workspace" class="udb-menu-builder--workspace">
								<ul class="udb-menu-builder--menu-list" data-menu-type="parent">
									<!-- to be re-written via js -->
									<li class="loading"></li>
								</ul>

								<?php do_action( 'udb_admin_bar_add_menu_button' ); ?>
							</div>
						</div>

						<div class="heatbox-footer">

							<?php if ( ! udb_is_pro_active() ) : ?>

								<div class="udb-pro-settings-page-notice udb-pro-admin-bar-notice">
									<p><?php esc_html_e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>
									<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=admin_bar_link&utm_campaign=udb" class="button button-large button-primary" target="_blank">
										<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
									</a>
								</div>

							<?php endif; ?>

							<?php do_action( 'udb_admin_bar_form_footer' ); ?>

						</div>
					</div>

				</form>
			</div>
			<div class="heatbox-sidebar">
				<?php
				require_once __DIR__ . '/metaboxes/remove-admin-bar-metabox.php';
				require_once __DIR__ . '/metaboxes/placeholder-tags-metabox.php';
				?>

				<?php do_action( 'udb_admin_bar_sidebar' ); ?>
			</div>
		</div>

	</div>

	<?php
};
