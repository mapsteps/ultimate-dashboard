<?php
/**
 * Admin menu page template.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;

$wp_roles_obj = wp_roles();
$role_names   = $wp_roles_obj->role_names;

$existing_menu_raw = Vars::get( 'existing_admin_bar_menu' );
$existing_menu     = $this->to_nested_format( $existing_menu_raw );

$saved_menu  = get_option( 'udb_admin_bar', array() );
$parsed_menu = ! $saved_menu ? $existing_menu : $this->parse_menu( $saved_menu, $existing_menu );

// List of user data which has custom admin bar menu.
$saved_user_data = array();

// Collect values for $saved_user_data.
foreach ( $saved_menu as $identifier => $menu_item ) {
	if ( false !== stripos( $identifier, 'user_id_' ) ) {
		$user_id   = absint( str_ireplace( 'user_id_', '', $identifier ) );
		$user_data = get_userdata( $user_id );

		array_push(
			$saved_user_data,
			array(
				'ID'           => $user_id,
				'display_name' => $user_data->display_name,
			)
		);
	}
}

wp_localize_script(
	'udb-admin-bar',
	'udbAdminBarRender',
	array(
		'existingMenu' => $existing_menu,
		'parsedMenu'   => $parsed_menu,
	)
);
?>

<div class="wrap heatbox-wrap udb-admin-bar-editor-page">

	<div class="heatbox-header heatbox-margin-bottom">

		<div class="heatbox-container heatbox-container-center">

			<div class="logo-container">

				<div>
					<span class="title">
						<?php echo esc_html( get_admin_page_title() ); ?>
						<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
					</span>
					<p class="subtitle"><?php _e( 'Fully customize the WordPress admin bar.', 'ultimate-dashboard' ); ?></p>
				</div>

				<div>
					<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png">
				</div>

			</div>

		</div>

	</div>

	<div class="heatbox-container heatbox-container-center">

		<?php do_action( 'udb_admin_bar_before_form' ); ?>

		<form action="options.php" method="post" class="udb-admin-bar--edit-form">

			<div class="heatbox udb-admin-bar-box">

				<div class="udb-admin-bar-box--header">
					<h2 class="udb-admin-bar-box--title">
						<?php _e( 'Admin Bar Editor', 'ultimate-dashboard' ); ?>
					</h2>

					<?php do_action( 'udb_admin_bar_header' ); ?>
				</div>

				<div class="udb-admin-bar--tabs udb-admin-bar--role-tabs">
					<div class="udb-admin-bar--tab-content udb-admin-bar--edit-area">
						<?php foreach ( $role_names as $role_key => $role_name ) : ?>

							<div id="udb-admin-bar--<?php echo esc_attr( $role_key ); ?>-edit-area" class="udb-admin-bar--tab-content-item udb-admin-bar--workspace udb-admin-bar--role-workspace<?php echo ( 'administrator' === $role_key ? ' is-active' : '' ); ?>" data-role="<?php echo esc_attr( $role_key ); ?>">
								<ul class="udb-admin-bar--menu-list">
									<!-- to be re-written via js -->
									<li class="loading"></li>
								</ul>

								<?php do_action( 'udb_admin_bar_add_menu_button' ); ?>
							</div>

						<?php endforeach; ?>
					</div><!-- .udb-admin-bar--tab-content -->
				</div><!-- .udb-admin-bar--role-tabs -->

				<div class="heatbox-footer">

					<div class="heatbox-left-footer">
						<button class="button button-large button-primary udb-admin-bar--button udb-admin-bar--submit-button">
							<i class="dashicons dashicons-yes"></i>
							<?php _e( 'Save Changes', 'ultimate-dashboard' ); ?>
						</button>
					</div>
					<div class="heatbox-right-footer">
						<button type="button" class="button button-large button-danger udb-admin-bar--button udb-admin-bar--reset-button udb-admin-bar--reset-all" data-role="all">
							<?php _e( 'Reset All Menus', 'ultimate-dashboard' ); ?>
						</button>
					</div>

					<?php do_action( 'udb_admin_bar_form_footer' ); ?>

				</div>
			</div>

		</form>

	</div>

</div>
