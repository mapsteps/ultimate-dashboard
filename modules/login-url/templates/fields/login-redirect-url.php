<?php
/**
 * Individual Login redirect url field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;

return function () {

	$settings      = Vars::get( 'udb_settings' );
	$redirect_urls = isset( $settings['login_redirect_urls'] ) ? $settings['login_redirect_urls'] : array();

	$wp_roles   = wp_roles();
	$role_names = $wp_roles->role_names;
	?>

	<div class="udb-login-redirect--field-wrapper">
		<select class="udb-login-redirect--role-selector" data-placeholder="<?php _e( 'Select a Role', 'ultimate-dashboard' ); ?>" data-width="200px">

			<option value="" readonly>
				<?php _e( 'Select a User', 'ultimate-dashboard' ); ?>
			</option>

			<?php
			foreach ( $role_names as $role_key => $role_name ) :
				$value       = ! empty( $redirect_urls ) && isset( $redirect_urls[ $role_key ] ) ? $redirect_urls[ $role_key ] : '';
				$is_disabled = $value ? true : false;
				?>

				<option value="<?php echo esc_attr( $role_key ); ?>" data-udb-default-url="<?php echo esc_attr( $value ); ?>" <?php disabled( $is_disabled, true ); ?>>
					<?php echo esc_html( $role_name ); ?>
				</option>

			<?php endforeach; ?>

		</select>
	</div>

	<div class="udb-login-redirect--repeater">

		<?php
		foreach ( $redirect_urls as $role_key => $redirect_url ) {
			if ( ! empty( $redirect_url ) ) {
				?>

				<div class="udb-login-redirect--repeater-item" data-udb-role-key="<?php echo esc_attr( $role_key ); ?>" data-udb-role-name="<?php echo esc_attr( $role_names[ $role_key ] ); ?>">
					<label class="udb-login-redirect--field-label">
						<?php echo esc_html( $role_names[ $role_key ] ); ?>
					</label>
					<div class="udb-login-redirect--field-control">
						<input type="text" name="udb_settings[login_redirect_urls][<?php echo esc_attr( $role_key ); ?>]" value="<?php echo esc_attr( $redirect_url ); ?>" placeholder="<?php echo esc_url( admin_url() ); ?>">
						<button type="button" class="udb-login-redirect--remove-field">
							<i class="fas fa-minus"></i>
						</button>
					</div>
				</div>

				<?php
			}
		}
		?>

	</div>
	<?php

};
