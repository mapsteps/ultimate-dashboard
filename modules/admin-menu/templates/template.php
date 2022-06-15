<?php
/**
 * Admin menu page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$wp_roles   = wp_roles();
$role_names = $wp_roles->role_names;

$saved_menu      = get_option( 'udb_admin_menu', array() );
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
?>

<div class="wrap heatbox-wrap udb-admin-menu-editor-page">

	<div class="heatbox-header heatbox-margin-bottom">

		<div class="heatbox-container heatbox-container-center">

			<div class="logo-container">

				<div>
					<span class="title">
						<?php echo esc_html( get_admin_page_title() ); ?>
						<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
					</span>
					<p class="subtitle"><?php _e( 'Fully customize the WordPress admin menu.', 'ultimate-dashboard' ); ?></p>
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
					<p><?php _e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>
					<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=admin_menu_link&utm_campaign=udb" class="button button-large button-primary" target="_blank">
						<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
					</a>
				</div>

			<?php endif; ?>

			<form action="options.php" method="post" class="udb-admin-menu--edit-form">

				<div class="heatbox udb-admin-menu-box">

					<div class="udb-admin-menu-box--header">
						<h2 class="udb-admin-menu-box--title">
							<?php _e( 'Admin Menu Editor', 'ultimate-dashboard' ); ?>
						</h2>
						<div class="udb-admin-menu-box--search-box is-hidden">
							<select name="udb_admin_menu_user_selector" id="udb_admin_menu_user_selector" class="udb-admin-menu--search-user" data-loading-msg="<?php _e( 'Loading Users...', 'ultimate-dashboard' ); ?>" data-placeholder="<?php _e( 'Select a User', 'ultimate-dashboard' ); ?>" disabled>
								<option value="">
									<?php _e( 'Loading Users...', 'ultimate-dashboard' ); ?>
								</option>
							</select>
						</div>
						<ul class="udb-admin-menu-box--header-tabs">
							<li class="udb-admin-menu-box--header-tab" data-header-tab="users">
								<a href="#users-menu">
									<?php _e( 'Users', 'ultimate-dashboard' ); ?>
								</a>
							</li>
							<li class="udb-admin-menu-box--header-tab is-active" data-header-tab="roles">
								<a href="#roles-menu">
									<?php _e( 'Roles', 'ultimate-dashboard' ); ?>
								</a>
							</li>
						</ul>
					</div>

					<div class="udb-admin-menu--tabs udb-admin-menu--role-tabs">
						<ul class="udb-admin-menu--tab-menu udb-admin-menu--role-menu">
							<?php foreach ( $role_names as $role_key => $role_name ) : ?>

								<li class="udb-admin-menu--tab-menu-item<?php echo ( 'administrator' === $role_key ? ' is-active' : '' ); ?>" data-udb-tab-content="udb-admin-menu--<?php echo esc_html( $role_key ); ?>-edit-area" data-role="<?php echo esc_attr( $role_key ); ?>">
									<button type="button">
										<?php echo esc_html( ucwords( $role_name ) ); ?>
									</button>
								</li>

							<?php endforeach; ?>
						</ul>

						<div class="udb-admin-menu--tab-content udb-admin-menu--edit-area">
							<?php foreach ( $role_names as $role_key => $role_name ) : ?>

								<div id="udb-admin-menu--<?php echo esc_attr( $role_key ); ?>-edit-area" class="udb-admin-menu--tab-content-item udb-admin-menu--workspace udb-admin-menu--role-workspace<?php echo ( 'administrator' === $role_key ? ' is-active' : '' ); ?>" data-role="<?php echo esc_attr( $role_key ); ?>">
									<ul class="udb-admin-menu--menu-list">
										<!-- to be re-written via js -->
										<li class="loading"></li>
									</ul>

									<div class="udb-admin-menu--inline-buttons">
										<?php
										do_action( 'udb_admin_menu_add_menu_button' );
										do_action( 'udb_admin_menu_add_separator_button' );
										?>
									</div>
								</div>

							<?php endforeach; ?>
						</div><!-- .udb-admin-menu--tab-content -->
					</div><!-- .udb-admin-menu--role-tabs -->

					<div class="udb-admin-menu--tabs udb-admin-menu--user-tabs is-hidden">
						<ul class="udb-admin-menu--tab-menu udb-admin-menu--user-menu">
							<?php foreach ( $saved_user_data as $index => $user_data ) : ?>

								<li class="udb-admin-menu--tab-menu-item <?php echo ( 0 === $index ? ' is-active' : '' ); ?>" data-udb-tab-content="udb-admin-menu--user-<?php echo esc_html( $user_data['ID'] ); ?>-edit-area" data-user-id="<?php echo esc_html( $user_data['ID'] ); ?>">
									<button type="button">
										<?php echo esc_html( $user_data['display_name'] ); ?>
									</button>
									<i class="dashicons dashicons-no-alt delete-icon udb-admin-menu--remove-tab"></i>
								</li>

							<?php endforeach; ?>

							<!-- to be managed more via JS -->
						</ul>

						<div class="udb-admin-menu--tab-content udb-admin-menu--edit-area">
							<div id="udb-admin-menu--user-empty-edit-area" class="udb-admin-menu--tab-content-item udb-admin-menu--workspace udb-admin-menu--user-workspace <?php echo ( empty( $saved_user_data ) ? ' is-active' : '' ); ?>">
								<?php _e( 'No user selected.', 'ultimate-dashboard' ); ?>
							</div>

							<?php foreach ( $saved_user_data as $index => $user_data ) : ?>

								<div id="udb-admin-menu--user-<?php echo esc_html( $user_data['ID'] ); ?>-edit-area" class="udb-admin-menu--tab-content-item udb-admin-menu--workspace udb-admin-menu--user-workspace <?php echo ( 0 === $index ? ' is-active' : '' ); ?>" data-user-id="<?php echo esc_html( $user_data['ID'] ); ?>">
									<ul class="udb-admin-menu--menu-list">
										<!-- to be re-written via js -->
										<li class="loading"></li>
									</ul>

									<div class="udb-admin-menu--inline-buttons">
										<?php
										do_action( 'udb_admin_menu_add_menu_button' );
										do_action( 'udb_admin_menu_add_separator_button' );
										?>
									</div>
								</div>

							<?php endforeach; ?>

							<!-- to be managed more via JS -->
						</div><!-- .udb-admin-menu--tab-content -->
					</div><!-- .udb-admin-menu--user-tabs -->

					<div class="heatbox-footer">

						<?php if ( ! udb_is_pro_active() ) : ?>

							<div class="udb-pro-settings-page-notice udb-pro-admin-menu-notice">
								<p><?php _e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>
								<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=admin_menu_link&utm_campaign=udb" class="button button-large button-primary" target="_blank">
									<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
								</a>
							</div>

						<?php endif; ?>

						<?php do_action( 'udb_admin_menu_form_footer' ); ?>

					</div>
				</div>

			</form>
		</div>

		<div class="heatbox-sidebar">
			<div class="heatbox udb-tags-metabox">
				<h2><?php _e( 'Placeholder Tags', 'ultimate-dashboard' ); ?></h2>
				<div class="heatbox-content">
					<p><?php _e( 'Use the placeholder tags below to display certain information dynamically.', 'ultimate-dashboard' ); ?></p>
					<p class="tags-wrapper">
						<?php
						$placeholder_tags = [
							'{site_name}',
							'{site_url}',
						];

						$placeholder_tags = apply_filters( 'udb_admin_menu_placeholder_tags', $placeholder_tags );
						$total_tags       = count( $placeholder_tags );

						foreach ( $placeholder_tags as $tag_index => $placeholder_tag ) {
							?>
							<code><?php echo esc_attr( $placeholder_tag ); ?></code><?php echo ( $total_tags - 1 === $tag_index ? '' : ',' ); ?>
							<?php
						}
						?>
					</p>
				</div>
			</div>

			<?php do_action( 'udb_admin_menu_sidebar' ); ?>
		</div>

	</div>

</div>
