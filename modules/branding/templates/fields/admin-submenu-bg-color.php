<?php
/**
 * Admin submenu bg color field.
 *
 * @package Ultimate_Dashboard_PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	?>

	<input type="text" name="" value="#2c3338" class="udb-color-field udb-branding-color-field udb-instant-preview-trigger" data-default="#2c3338" data-udb-trigger-name="admin-submenu-bg-color" />

	<br>

	<div class="udb-pro-settings-page-notice">

		<p><?php esc_html_e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>

		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=white_label_link&utm_campaign=udb" class="button button-primary" target="_blank">
			<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
		</a>

	</div>

	<?php

};
