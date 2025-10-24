<?php
/**
 * Admin bar logo field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="setting-fields">

		<div class="setting-field">
			<input class="all-options" type="text" disabled />
			<button type="button" class="button-secondary button-disabled disabled">
				<?php esc_html_e( 'Add or Upload File', 'ultimate-dashboard' ); ?>
			</button>
		</div>

		<div class="setting-field">
			<label class="label checkbox-label">
				<span class="is-disabled">
					<?php esc_html_e( 'Remove Admin Bar Logo', 'ultimate-dashboard' ); ?>
				</span>
				<input type="checkbox" disabled />
				<div class="indicator"></div>
			</label>
		</div>

	</div>

	<?php
};
