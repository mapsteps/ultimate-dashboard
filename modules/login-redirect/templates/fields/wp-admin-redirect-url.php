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

	<div class="heatbox-input-group">
		<span class="heatbox-input-prefix" title="<?php echo esc_attr( trailingslashit( site_url() ) ); ?>"><?php echo esc_html( trailingslashit( site_url() ) ); ?></span>
		<input type="text" name="udb_login_redirect[wp_admin_redirect_slug]" value="<?php echo esc_attr( $slug ); ?>" placeholder="404">
	</div>

	<p class="description">
		<?php
		/* translators: %1$s: Site URL */
		echo wp_kses_post( sprintf( __( 'Redirect non-logged-in users that try to access <code>%1$s/wp-admin/</code>.', 'ultimate-dashboard' ), esc_url( site_url() ) ) );
		?>
		<br>
		<?php esc_html_e( 'Left empty while a new login URL is set, they get a "not found" page, so wp-admin doesn\'t reveal your login URL.', 'ultimate-dashboard' ); ?>
	</p>

	<?php

};
