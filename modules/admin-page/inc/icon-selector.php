<?php
/**
 * Icon selector.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$dashicons   = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'assets/json/dashicons.json' );
$dashicons   = json_decode( $dashicons, true );
$dashicons   = $dashicons ? $dashicons : array();
$fontawesome = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'assets/json/fontawesome4.json' );
$fontawesome = json_decode( $fontawesome, true );
$fontawesome = $fontawesome ? $fontawesome : array();
$udb_icons   = array();
$selected    = array(
	'id'   => 'dashicons dashicons-admin-post',
	'text' => 'Admin Post',
);

// loop over dashicons.
foreach ( $dashicons as $icon_category => $icons ) {

	$category_name = str_ireplace( '_', ' ', $icon_category );
	$category_name = ucwords( $category_name );
	$category_name = 'Dashicons: ' . $category_name;
	$icons         = $icons && is_array( $icons ) ? $icons : array();

	$items = array();

	foreach ( $icons as $icon_class ) {

		$icon_name = explode( ' ', $icon_class );
		$icon_name = $icon_name[1];
		$splits    = explode( '-', $icon_name );
		$icon_name = str_ireplace( $splits[0] . '-', '', $icon_name );
		$icon_name = str_ireplace( '-', ' ', $icon_name );
		$icon_name = ucwords( $icon_name ) . ' <code style="font-size:10px">' . $icon_class . '</code>';
		$icon_text = '<i class="' . $icon_class . '"></i> ' . $icon_name;

		if ( $icon_class === $menu_icon ) {

			$selected = array(
				'id'   => $icon_class,
				'text' => $icon_text,
			);

		}

		array_push(
			$items,
			array(
				'id'   => $icon_class,
				'text' => $icon_text,
			)
		);

	}

	array_push(
		$udb_icons,
		array(
			'text'     => $category_name,
			'children' => $items,
		)
	);

}

// Loop over FontAwesome.
foreach ( $fontawesome as $icon_category => $icons ) {

	$category_name = str_ireplace( '_', ' ', $icon_category );
	$category_name = ucwords( $category_name );
	$category_name = 'Font Awesome 4: ' . $category_name;
	$icons         = $icons && is_array( $icons ) ? $icons : array();

	$items = array();

	foreach ( $icons as $icon_class ) {

		$icon_name = explode( ' ', $icon_class );
		$icon_name = $icon_name[1];
		$splits    = explode( '-', $icon_name );
		$icon_name = str_ireplace( $splits[0] . '-', '', $icon_name );
		$icon_name = str_ireplace( '-', ' ', $icon_name );
		$icon_name = ucwords( $icon_name ) . ' <code style="font-size:10px">' . $icon_class . '</code>';
		$icon_text = '<i class="' . $icon_class . '"></i> ' . $icon_name;

		if ( $icon_class === $menu_icon ) {

			$selected = array(
				'id'   => $icon_class,
				'text' => $icon_text,
			);

		}

		array_push(
			$items,
			array(
				'id'   => $icon_class,
				'text' => $icon_text,
			)
		);

	}

	array_push(
		$udb_icons,
		array(
			'text'     => $category_name,
			'children' => $items,
		)
	);

}

wp_localize_script(
	'udb-edit-admin-page',
	'udbIcons',
	array(
		'icons'    => $udb_icons,
		'selected' => $selected,
	)
);

?>

<div class="field" data-show-if-field="udb_menu_type" data-show-if-value="parent">
	<label class="label" for="udb_menu_icon"><?php _e( 'Menu Icon', 'ultimatedashboard' ); ?></label>
	<div class="control">
		<div class="icon-preview"></div>
	</div>
</div>
<div class="field" data-show-if-field="udb_menu_type" data-show-if-value="parent">
	<div class="control icon-picker-control">
		<select name="udb_menu_icon" id="udb_menu_icon" class="udb-icon-selector">
			<?php if ( $menu_icon ) { ?>
				<option value="<?php echo esc_attr( $menu_icon ); ?>" selected><?php echo esc_html( $menu_icon ); ?></option>
			<?php } ?>
		</select>
	</div>
</div>
