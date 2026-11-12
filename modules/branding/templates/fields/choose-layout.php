<?php
/**
 * Choose layout field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<select disabled>
		<option><?php esc_html_e( 'Modern', 'ultimate-dashboard' ); ?></option>
	</select>

	<?php
};
