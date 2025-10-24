<?php
/**
 * Remove 3rd party widgets field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Widget_Helper;

return function () {

	$widget_helper = new Widget_Helper();
	$widgets       = $widget_helper->get_3rd_party();

	if ( empty( $widgets ) ) {
		esc_html_e( 'No 3rd Party Widgets available.', 'ultimate-dashboard' );
	}
	?>

	<div class="setting-fields">

		<?php

		foreach ( $widgets as $id => $widget ) {

			?>

			<div class="setting-field">
				<label class="label checkbox-label">
					<?php echo esc_attr( $widget['title_stripped'] ); ?> (<code><?php echo esc_attr( $id ); ?></code>)
					<input type="checkbox" disabled>
					<div class="indicator"></div>
				</label>
			</div>

			<?php
		}

		?>

	</div>

	<div class="udb-pro-settings-page-notice">

		<p><?php esc_html_e( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ); ?></p>

		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=remove_3rd_party_widgets_link&utm_campaign=udb" class="button button-primary" target="_blank">
			<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
		</a>

	</div>

	<?php

};
