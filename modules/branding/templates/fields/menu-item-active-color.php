<?php
/**
 * Menu item active color field.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	?>

	<input type="text" name="" value="#0073AA" class="udb-color-field udb-branding-color-field udb-instant-preview-trigger" data-default="#0073AA" data-udb-trigger-name="menu-item-active-color" />

	<br>

	<div class="udb-pro-settings-page-notice">

		<p><?php _e( 'This is only preview. Admin Colors feature is available in Ultimate Dashboard PRO.' ); ?></p>

		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=white_label_link&utm_campaign=udb" class="button button-primary" target="_blank">
			<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
		</a>

	</div>

	<?php

};
