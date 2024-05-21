<?php
/**
 * The "Enable block editor darkmode" field.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Outputting "enable block editor darkmode" field.
 */
return function () {

	$field_description = __(
		'Enable dark mode for the block editor (Gutenberg editor).',
		'ultimatedashboard'
	);
	?>

	<label for="udb_branding--block_editor_darkmode" class="toggle-switch">
		<input
			type="checkbox"
			name="udb_branding[block_editor_darkmode]"
			id="udb_branding--block_editor_darkmode"
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
