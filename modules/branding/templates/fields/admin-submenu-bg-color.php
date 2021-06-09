<?php
/**
 * Admin submenu bg color field.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding = get_option( 'udb_branding' );
	$bg_color = isset( $branding['admin_menu_bg_color'] ) ? $branding['admin_menu_bg_color'] : '#2c3338';

	echo '<input type="text" name="udb_branding[admin_menu_bg_color]" value="' . esc_attr( $bg_color ) . '" class="udb-color-field udb-branding-color-field" data-default="#0073aa" />';

};
