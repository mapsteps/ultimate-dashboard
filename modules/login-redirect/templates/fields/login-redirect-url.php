<?php
/**
 * Login redirect url field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;

return function ( $site_type = 'blueprint' ) {

	$site_type_prefix = 'subsites' === $site_type ? 'subsites_' : '';

	$settings       = get_option( 'udb_login_redirect', array() );
	$redirect_slugs = isset( $settings[ $site_type_prefix . 'login_redirect_slugs' ] ) ? $settings[ $site_type_prefix . 'login_redirect_slugs' ] : array();

	$wp_roles   = wp_roles();
	$role_names = $wp_roles->role_names;

	$multisite_supported = apply_filters( 'udb_ms_supported', false );
	$is_blueprint        = apply_filters( 'udb_ms_is_blueprint', false );

	$field_prefix = esc_url( site_url( '/' ) );

	if ( $multisite_supported ) {
		if ( $is_blueprint ) {
			if ( 'blueprint' !== $site_type ) {
				$field_prefix = '{subsite_url}/';
			}
		}
	}
	?>

	<div class="udb-login-redirect--wrapper udb-login-redirect--<?php echo esc_attr( $site_type ); ?>-wrapper">

		<?php // Rows of the roles that have a redirect. ?>
		<div class="udb-login-redirect--repeater">

			<?php
			foreach ( $redirect_slugs as $role_key => $redirect_slug ) {
				if ( empty( $redirect_slug ) ) {
					continue;
				}

				$role_name = isset( $role_names[ $role_key ] ) ? translate_user_role( $role_names[ $role_key ] ) : $role_key;
				$role_name = 'super_admin' === $role_key ? __( 'Super Admin', 'ultimate-dashboard' ) : $role_name;
				$can_edit  = 'super_admin' !== $role_key || ( $multisite_supported && is_super_admin() );
				$field_id  = 'udb-login-redirect--' . $site_type . '-' . $role_key;
				?>

				<div class="udb-login-redirect--repeater-item" data-udb-role-key="<?php echo esc_attr( $role_key ); ?>" data-udb-role-name="<?php echo esc_attr( $role_name ); ?>">
					<label class="udb-login-redirect--field-label" for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $role_name ); ?></label>
					<div class="heatbox-input-group">
						<span class="heatbox-input-prefix" title="<?php echo esc_attr( $field_prefix ); ?>"><?php echo esc_html( $field_prefix ); ?></span>
						<input type="text" id="<?php echo esc_attr( $field_id ); ?>" name="udb_login_redirect[<?php echo esc_attr( $site_type_prefix ); ?>login_redirect_slugs][<?php echo esc_attr( $role_key ); ?>]" value="<?php echo esc_attr( $redirect_slug ); ?>" placeholder="wp-admin/"<?php echo $can_edit ? '' : ' readonly'; ?>>
					</div>
					<?php if ( $can_edit ) : ?>
						<button type="button" class="button-link udb-login-redirect--remove-field">
							<span class="dashicons dashicons-no-alt" aria-hidden="true"></span>
							<span class="screen-reader-text">
								<?php
								/* translators: %s: Role name. */
								echo esc_html( sprintf( __( 'Remove %s', 'ultimate-dashboard' ), $role_name ) );
								?>
							</span>
						</button>
					<?php endif; ?>
				</div>

			<?php } ?>

		</div>

		<?php // Adds a row; a role that has one is disabled here. ?>
		<select class="udb-login-redirect--role-selector" aria-label="<?php esc_attr_e( 'Select a Role', 'ultimate-dashboard' ); ?>" data-udb-site-type="<?php echo esc_attr( $site_type ); ?>" data-udb-field-prefix="<?php echo esc_attr( $field_prefix ); ?>" data-udb-remove-label="<?php /* translators: %s: Role name. */ esc_attr_e( 'Remove %s', 'ultimate-dashboard' ); ?>">

			<option value=""><?php esc_html_e( 'Select a Role', 'ultimate-dashboard' ); ?></option>

			<?php
			if ( $multisite_supported && is_super_admin() ) {
				$super_admin_value = isset( $redirect_slugs['super_admin'] ) ? $redirect_slugs['super_admin'] : '';
				?>

				<option value="super_admin" <?php disabled( ! empty( $super_admin_value ) ); ?>><?php esc_html_e( 'Super Admin', 'ultimate-dashboard' ); ?></option>

				<?php
			}

			foreach ( $role_names as $role_key => $role_name ) {
				?>

				<option value="<?php echo esc_attr( $role_key ); ?>" <?php disabled( ! empty( $redirect_slugs[ $role_key ] ) ); ?>><?php echo esc_html( translate_user_role( $role_name ) ); ?></option>

			<?php } ?>

		</select>

	</div>

	<?php

};
