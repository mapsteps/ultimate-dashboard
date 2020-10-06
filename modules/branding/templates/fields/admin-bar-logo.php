<?php
/**
 * Admin bar logo field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<input class="all-options" type="text" disabled />
	<a href="javascript:void(0)" class="button-secondary button-disabled disabled"><?php _e( 'Add or Upload File', 'ultimate-dashboard' ); ?></a>

	<div class="field setting-field" style="margin-top: 10px;">
		<label class="label checkbox-label">
			<?php _e( 'Remove Admin Bar Logo', 'ultimate-dashboard' ); ?>
			<input type="checkbox" disabled />
			<div class="indicator"></div>
		</label>
	</div>

	<?php
};
