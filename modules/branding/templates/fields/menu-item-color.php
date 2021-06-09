<?php
/**
 * Menu item color field.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$branding = get_option( 'udb_branding' );
	$color    = isset( $branding['menu_item_color'] ) ? $branding['menu_item_color'] : '#f0f0f1';

	echo '<input type="text" name="udb_branding[menu_item_color]" value="' . esc_attr( $color ) . '" class="udb-color-field udb-branding-color-field" data-default="#0073aa" />';

};
