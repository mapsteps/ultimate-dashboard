<?php
/**
 * Admin menu page template.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;

$wp_roles   = wp_roles();
$role_names = $wp_roles->role_names;

$existing_menu_raw = Vars::get( 'existing_admin_bar_menu' );
$existing_menu     = $this->to_nested_format( $existing_menu_raw );

$saved_menu      = get_option( 'udb_admin_bar', array() );
$parsed_menu     = ! $saved_menu ? $existing_menu : array();
$saved_user_data = array();

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

		<h1 style="display: none;"></h1>

		<?php if ( ! udb_is_pro_active() ) : ?>

			<div class="udb-pro-admin-bar-nag">
				<p><?php _e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>
				<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=admin_bar_link&utm_campaign=udb" class="button button-large button-primary" target="_blank">
					<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
				</a>
			</div>

		<?php endif; ?>

		<form action="options.php" method="post" class="udb-admin-bar--edit-form">

			<div class="heatbox udb-admin-bar-box">

				<div class="udb-admin-bar-box--header">
					<h2 class="udb-admin-bar-box--title">
						<?php _e( 'Admin Bar Editor', 'ultimate-dashboard' ); ?>
					</h2>
					<div class="udb-admin-bar-box--search-box is-hidden">
						<select name="udb_admin_bar_user_selector" id="udb_admin_bar_user_selector" class="udb-admin-bar--search-user" data-loading-msg="<?php _e( 'Loading Users...', 'ultimate-dashboard' ); ?>" data-placeholder="<?php _e( 'Select a User', 'ultimate-dashboard' ); ?>" disabled>
							<option value="">
								<?php _e( 'Loading Users...', 'ultimate-dashboard' ); ?>
							</option>
						</select>
					</div>
					<ul class="udb-admin-bar-box--header-tabs">
						<li class="udb-admin-bar-box--header-tab" data-header-tab="users">
							<?php _e( 'Users', 'ultimate-dashboard' ); ?>
						</li>
						<li class="udb-admin-bar-box--header-tab is-active" data-header-tab="roles">
							<?php _e( 'Roles', 'ultimate-dashboard' ); ?>
						</li>
					</ul>
				</div>

				<div class="udb-admin-bar--tabs udb-admin-bar--role-tabs">
					<ul class="udb-admin-bar--tab-menu udb-admin-bar--role-menu">
						<?php foreach ( $role_names as $role_key => $role_name ) : ?>

							<li class="udb-admin-bar--tab-menu-item<?php echo ( 'administrator' === $role_key ? ' is-active' : '' ); ?>" data-udb-tab-content="udb-admin-bar--<?php echo esc_html( $role_key ); ?>-edit-area" data-role="<?php echo esc_attr( $role_key ); ?>">
								<button type="button">
									<?php echo esc_html( ucwords( $role_name ) ); ?>
								</button>
							</li>

						<?php endforeach; ?>
					</ul>

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

				<div class="udb-admin-bar--tabs udb-admin-bar--user-tabs is-hidden">
					<ul class="udb-admin-bar--tab-menu udb-admin-bar--user-menu">
						<?php foreach ( $saved_user_data as $index => $user_data ) : ?>

							<li class="udb-admin-bar--tab-menu-item <?php echo ( 0 === $index ? ' is-active' : '' ); ?>" data-udb-tab-content="udb-admin-bar--user-<?php echo esc_html( $user_data['ID'] ); ?>-edit-area" data-user-id="<?php echo esc_html( $user_data['ID'] ); ?>">
								<button type="button">
									<?php echo esc_html( $user_data['display_name'] ); ?>
								</button>
								<i class="dashicons dashicons-no-alt delete-icon udb-admin-bar--remove-tab"></i>
							</li>

						<?php endforeach; ?>

						<!-- to be managed more via JS -->
					</ul>

					<div class="udb-admin-bar--tab-content udb-admin-bar--edit-area">
						<div id="udb-admin-bar--user-empty-edit-area" class="udb-admin-bar--tab-content-item udb-admin-bar--workspace udb-admin-bar--user-workspace <?php echo ( empty( $saved_user_data ) ? ' is-active' : '' ); ?>">
							<?php _e( 'No user selected.', 'ultimate-dashboard' ); ?>
						</div>

						<?php foreach ( $saved_user_data as $index => $user_data ) : ?>

							<div id="udb-admin-bar--user-<?php echo esc_html( $user_data['ID'] ); ?>-edit-area" class="udb-admin-bar--tab-content-item udb-admin-bar--workspace udb-admin-bar--user-workspace <?php echo ( 0 === $index ? ' is-active' : '' ); ?>" data-user-id="<?php echo esc_html( $user_data['ID'] ); ?>">
								<ul class="udb-admin-bar--menu-list">
									<!-- to be re-written via js -->
									<li class="loading"></li>
								</ul>

								<?php do_action( 'udb_admin_bar_add_menu_button' ); ?>
							</div>

						<?php endforeach; ?>

						<!-- to be managed more via JS -->
					</div><!-- .udb-admin-bar--tab-content -->
				</div><!-- .udb-admin-bar--user-tabs -->

				<div class="heatbox-footer">

					<?php if ( ! udb_is_pro_active() ) : ?>

						<div class="udb-pro-settings-page-notice udb-pro-admin-bar-notice">
							<p><?php _e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>
							<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=admin_bar_link&utm_campaign=udb" class="button button-large button-primary" target="_blank">
								<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
							</a>
						</div>

					<?php endif; ?>

					<?php do_action( 'udb_admin_bar_form_footer' ); ?>

				</div>
			</div>

		</form>

	</div>

</div>
