<?php
/**
 * Enable branding field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<label class="label checkbox-label">
		&nbsp;
		<input type="checkbox" name="" class="udb-enable-branding" />
		<div class="indicator"></div>
	</label>

	<?php
};
