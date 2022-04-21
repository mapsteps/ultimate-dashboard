<?php
/**
 * Export field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	?>

	<p><?php _e( 'Use the export button to export to a .json file which you can then import to another Ultimate Dashboard installation.', 'ultimate-dashboard' ); ?></p>
	<br>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="modules_manager" checked />
			<?php _e( 'Include Module Manager', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="settings" checked />
			<?php _e( 'Include Settings', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="widgets" checked />
			<?php _e( 'Include Widgets', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="branding" checked />
			<?php _e( 'Include White Label', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="login_customizer" checked />
			<?php _e( 'Include Login Customizer', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="login_redirect" checked />
			<?php _e( 'Include Login Redirect', 'ultimate-dashboard' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="checkbox" name="udb_export_modules[]" class="udb-module-checkbox" value="admin_pages" checked />
			<?php _e( 'Include Admin Pages', 'ultimate-dashboard' ); ?>
		</label>
	</p>

	<?php do_action( 'udb_export_fields' ); ?>

	<br>
	<p>
		<a href="#" class="udb-select-all-modules">Select All</a>
	</p>

	<?php

};
