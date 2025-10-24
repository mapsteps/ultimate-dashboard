<?php
/**
 * The "Enable wp admin darkmode" field.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting "enable wp admin darkmode" field.
 */
return function () {

	$field_description = __(
		'Enable dark mode for the WordPress admin area.',
		'ultimate-dashboard'
	);
	?>

	<label for="udb_branding--wp_admin_darkmode" class="toggle-switch">
		<input
			type="checkbox"
			name="udb_branding[wp_admin_darkmode]"
			id="udb_branding--wp_admin_darkmode"
			value="0"
			disabled
		/>
		<div class="switch-track">
			<div class="switch-thumb"></div>
		</div>
	</label>

	<p class="description">
		<?php echo esc_html( $field_description ); ?>
	</p>

	<?php

};
