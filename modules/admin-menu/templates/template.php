<?php
/**
 * Admin menu page template.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$wp_roles   = wp_roles();
$role_names = $wp_roles->role_names;
?>

<div class="wrap settingstuff">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form action="options.php" method="post" class="udb-admin-menu--edit-form">

		<div class="neatbox has-bigger-heading is-smooth udb-admin-menu-box">
			<div class="notice notice-warning pro-available-notice">
				<p>
					This feature is available in the PRO version.
					<a href="https://ultimatedashboard.io/docs/admin-pages/" target="_blank">Learn more about admin menu feature.</a>
				</p>
			</div>

			<h2>
				<?php _e( 'Admin Menu Editor', 'ultimatedashboard' ); ?>
			</h2>
			<div class="udb-admin-menu--tabs udb-admin-menu--role-tabs">
				<ul class="udb-admin-menu--tab-menu udb-admin-menu--role-menu">
					<?php foreach ( $role_names as $role_key => $role_name ) : ?>

						<li class="udb-admin-menu--tab-menu-item<?php echo ( 'administrator' === $role_key ? ' is-active' : '' ); ?>" data-udb-tab-content="udb-admin-menu--<?php echo esc_html( $role_key ); ?>-edit-area" data-role="<?php echo esc_attr( $role_key ); ?>">
							<button type="button">
								<?php echo esc_html( ucwords( $role_name ) ); ?>
							</button>

						</li>
						<?php
						add_settings_field(
							'page-builder-template-' . $role_key,
							ucwords( $role_name ),
							function () use ( $role_key ) {
								udb_page_builder_template_field( $role_key );
							},
							'udb-general-page',
							'udb-builder-section'
						);
						?>
					<?php endforeach; ?>
				</ul>

				<div class="udb-admin-menu--tab-content udb-admin-menu--edit-area">
					<?php foreach ( $role_names as $role_key => $role_name ) : ?>

						<div id="udb-admin-menu--<?php echo esc_attr( $role_key ); ?>-edit-area" class="udb-admin-menu--tab-content-item udb-admin-menu--role-workspace<?php echo ( 'administrator' === $role_key ? ' is-active' : '' ); ?>" data-role="<?php echo esc_attr( $role_key ); ?>">
							<ul class="udb-admin-menu--menu-list">
								<!-- to be re-written via js -->
							</ul>
						</div>

					<?php endforeach; ?>
				</div><!-- .udb-admin-menu--tab-content -->
			</div><!-- .udb-admin-menu--tabs -->

			<div class="neatbox-footer">
				<a href="https://ultimatedashboard.io/pro/" target="_blank" class="button button-large button-primary udb-admin-menu--button udb-admin-menu--submit-button">
					<?php _e( 'Get Ultimate Dashboard PRO', 'ultimatedashboard' ); ?>
				</a>
			</div>
		</div>

	</form>

</div>
