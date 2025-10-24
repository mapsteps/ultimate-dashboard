<?php
/**
 * Import field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	?>

	<p><?php esc_html_e( 'Select the .json file you would like to import.', 'ultimate-dashboard' ); ?></p>
	<br>
	<p>
		<label class="block-label" for="udb_import_file"><?php esc_html_e( 'Select File', 'ultimate-dashboard' ); ?></label>
		<input type="file" name="udb_import_file">
	</p>

	<?php

};
