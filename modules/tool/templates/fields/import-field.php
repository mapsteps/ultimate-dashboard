<?php
/**
 * Import field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	?>

	<p><?php _e( 'Select the Widgets JSON file you would like to import. When you click the import button below, Ultimate Dashboard will import the widgets.', 'ultimate-dashboard' ); ?></p>
	<br>
	<p>
		<label class="block-label" for="udb_import_file"><?php _e( 'Select File', 'ultimate-dashboard' ); ?></label>
		<input type="file" name="udb_import_file">
	</p>

	<?php

};
