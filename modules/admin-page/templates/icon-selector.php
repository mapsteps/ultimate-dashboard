<?php
/**
 * Icon selector.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$menu_icon   = get_post_meta( $post->ID, 'udb_menu_icon', true );
$menu_icon   = $menu_icon ? $menu_icon : 'dashicons dashicons-admin-post';
$dashicons   = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/json/dashicons.json' );
$dashicons   = json_decode( $dashicons, true );
$dashicons   = $dashicons ? $dashicons : array();
$fontawesome = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/json/fontawesome5.json' );
$fontawesome = json_decode( $fontawesome, true );
$fontawesome = $fontawesome ? $fontawesome : array();
$udb_icons   = array_merge( $dashicons, $fontawesome );

wp_localize_script(
	'udb-edit-admin-page',
	'iconPickerIcons',
	$udb_icons
);

?>

<div class="udb-metabox-field" data-show-if-field="udb_menu_type" data-show-if-value="parent">
	<label class="label" for="udb_menu_icon"><?php esc_html_e( 'Menu Icon', 'ultimate-dashboard' ); ?></label>
	<div class="icon-preview"></div>
</div>

<div class="udb-metabox-field" data-show-if-field="udb_menu_type" data-show-if-value="parent">
	<label class="label" for="udb_menu_icon"><?php esc_html_e( 'Select Icon', 'ultimate-dashboard' ); ?></label>
	<input type="text" class="icon-picker is-full" name="udb_menu_icon" id="udb_menu_icon" value="<?php echo esc_attr( $menu_icon ); ?>" placeholder="dashicons dashicons-admin-generic" />
</div>
