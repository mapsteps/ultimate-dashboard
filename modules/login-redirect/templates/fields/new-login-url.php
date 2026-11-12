<?php
/**
 * New login url field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings = get_option( 'udb_login_redirect' );
	$slug     = isset( $settings['login_url_slug'] ) ? trim( $settings['login_url_slug'], '/' ) : '';
	?>

	<div class="heatbox-input-group">
		<span class="heatbox-input-prefix" title="<?php echo esc_attr( trailingslashit( site_url() ) ); ?>"><?php echo esc_html( trailingslashit( site_url() ) ); ?></span>
		<input type="text" name="udb_login_redirect[login_url_slug]" value="<?php echo esc_attr( $slug ); ?>" placeholder="login">
	</div>

	<p class="description">
		<?php
		/* translators: %1$s: Site URL */
		echo wp_kses_post( sprintf( __( 'Change the login URL and prevent users from accessing <code>%1$s/wp-login.php</code>.', 'ultimate-dashboard' ), esc_url( site_url() ) ) );
		?>
	</p>

	<?php

};
