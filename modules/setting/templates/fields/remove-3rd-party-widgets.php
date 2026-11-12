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

	<?php

};
