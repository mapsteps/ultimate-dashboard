<?php
/**
 * Import field.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	?>

	<p><?php _e( 'Select the JSON file you would like to import.', 'ultimate-dashboard' ); ?></p>

	<div class="fields-wrapper has-top-gap">
		<label class="block-label" for="udb_import_file"><?php _e( 'Select File', 'ultimate-dashboard' ); ?></label>
		<input type="file" name="udb_import_file">
	</div>

	<?php

};
