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
				<?php echo esc_url( site_url() ); ?>/
			</code>
		</div>

		<input type="text" name="udb_login_redirect[wp_admin_redirect_slug]" class="all-options" value="<?php echo esc_attr( $slug ); ?>" placeholder="404" />

		<div class="udb-url-suffix-field">
			<code>/</code>
		</div>
	</div>

	<p class="description">
		<?php
		/* translators: %1$s: Site URL */
		echo wp_kses_post( sprintf( __( 'Redirect non-logged-in users that try to access <code>%1$s/wp-admin/</code>.', 'ultimate-dashboard' ), esc_url( site_url() ) ) );
		?>
	</p>

	<?php

};
