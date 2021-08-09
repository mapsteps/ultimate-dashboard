<?php
/**
 * The wp-admin redirect url field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_login_redirect' );
	$slug     = isset( $settings['wp_admin_redirect_slug'] ) ? trim( $settings['wp_admin_redirect_slug'], '/' ) : '';
	?>

	<div class="udb-url-prefix-suffix-field">
		<div class="udb-url-prefix-field">
			<code>
				<?php echo esc_url( home_url() ); ?>/
			</code>
		</div>

		<input type="text" name="udb_login_redirect[wp_admin_redirect_slug]" class="all-options" value="<?php echo esc_attr( $slug ); ?>" placeholder="404" />

		<div class="udb-url-suffix-field">
			<code>/</code>
		</div>
	</div>

	<p class="description">
		Redirect when someone tries to access <code>/wp-admin/</code> without login.
	</p>

	<?php

};
