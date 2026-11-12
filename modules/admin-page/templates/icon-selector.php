<?php
/**
 * Icon selector.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$menu_icon = get_post_meta( $post->ID, 'udb_menu_icon', true );
$menu_icon = $menu_icon ? $menu_icon : 'dashicons dashicons-admin-post';
?>

<div class="udb-metabox-field" data-show-if-field="udb_menu_type" data-show-if-value="parent">
	<label class="label" for="udb_menu_icon_button"><?php esc_html_e( 'Menu Icon', 'ultimate-dashboard' ); ?></label>
	<div class="udb-icon-field">
		<button type="button" class="icon-picker" id="udb_menu_icon_button" aria-label="<?php esc_attr_e( 'Change icon', 'ultimate-dashboard' ); ?>">
			<i class="<?php echo esc_attr( $menu_icon ); ?>" aria-hidden="true"></i>
		</button>
		<input type="hidden" name="udb_menu_icon" id="udb_menu_icon" value="<?php echo esc_attr( $menu_icon ); ?>">
	</div>
</div>
