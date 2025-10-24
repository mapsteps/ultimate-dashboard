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
		'Enable dark mode for the block editor (Gutenberg).',
		'ultimate-dashboard'
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

	<br>

	<div class="udb-pro-settings-page-notice">

		<?php if ( ! udb_is_pro_active() ) : ?>

			<p><?php esc_html_e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>

			<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=white_label_link&utm_campaign=udb" class="button button-primary" target="_blank">
				<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
			</a>

		<?php endif; ?>

	</div>

	<?php

};
