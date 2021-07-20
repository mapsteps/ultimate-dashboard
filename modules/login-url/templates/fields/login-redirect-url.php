<?php
/**
 * Login redirect url field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;

return function ( $role_key ) {

	$settings       = Vars::get( 'udb_settings' );
	$redirect_slugs = isset( $settings['login_redirect_slugs'] ) ? $settings['login_redirect_slugs'] : array();
	$value          = '';

	if ( ! empty( $redirect_slugs ) ) {
		$value = isset( $redirect_slugs[ $role_key ] ) ? $redirect_slugs[ $role_key ] : '';
	}
	?>

	<div class="udb-url-prefix-suffix-field">
		<div class="udb-url-prefix-field">
			<code>
				<?php echo esc_url( site_url() ); ?>/
			</code>
		</div>

		<input type="text" id="udb_settings[login_redirect_slugs][<?php echo esc_attr( $role_key ); ?>]" name="udb_settings[login_redirect_slugs][<?php echo esc_attr( $role_key ); ?>]" class="regular-text" value="<?php echo esc_attr( $value ); ?>" placeholder="wp-admin">

		<div class="udb-url-suffix-field">
			<code>/</code>
		</div>
	</div>

	<?php

};
