<?php
/**
 * Redirect old login url field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_settings' );
	$path     = isset( $settings['old_login_url_redirect_path'] ) ? $settings['old_login_url_redirect_path'] : '';
	?>

	<div class="udb-url-prefix-suffix-field">
		<div class="udb-url-prefix-field">
			<code>
				<?php echo esc_url( home_url() ); ?>/
			</code>
		</div>

		<input type="text" name="udb_settings[old_login_url_redirect_path]" class="all-options" value="<?php echo esc_attr( $path ); ?>" placeholder="404" />

		<div class="udb-url-suffix-field">
			<code>/</code>
		</div>
	</div>

	<p class="description">
		Redirect when someone tries to access <code>wp-login.php</code> or trying to access <code>/wp-admin/</code> without login
	</p>

	<?php

};
