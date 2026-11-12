<?php
/**
 * Import field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<p>
		<label for="udb_import_file"><?php esc_html_e( 'Select the .json file you would like to import.', 'ultimate-dashboard' ); ?></label>
	</p>

	<p>
		<input type="file" name="udb_import_file" id="udb_import_file" accept=".json,application/json">
	</p>

	<?php

};
