<?php
/**
 * Choose layout field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	echo '<select disabled>';
	echo '<option>Modern</option>';
	echo '</select>';

	?>

	<br>

	<div class="udb-pro-settings-page-notice">

		<p><?php esc_html_e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>

		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=white_label_link&utm_campaign=udb" class="button button-primary" target="_blank">
			<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
		</a>

	</div>

	<?php

};
