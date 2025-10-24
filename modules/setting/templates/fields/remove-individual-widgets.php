<?php
/**
 * Remove individual widgets field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Widget_Helper;

return function () {

	$settings   = get_option( 'udb_settings' );
	$is_checked = isset( $settings['welcome_panel'] ) ? 1 : 0;
	?>

	<div class="setting-fields">

		<div class="setting-field">
			<label for="udb_settings[welcome_panel]" class="label checkbox-label">
				<?php esc_html_e( 'Welcome Panel', 'ultimate-dashboard' ); ?> (<code>welcome_panel</code>)
				<input type="checkbox" name="udb_settings[welcome_panel]" id="udb_settings[welcome_panel]" value="1" <?php checked( $is_checked, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<?php
		$widget_helper = new Widget_Helper();
		$widgets       = $widget_helper->get_default();

		foreach ( $widgets as $id => $widget ) {

			$is_checked = isset( $settings[ $id ] ) ? 1 : 0;
			$title      = isset( $widget['title_stripped'] ) ? $widget['title_stripped'] : '';
			?>

			<div class="setting-field">
				<label for="udb_settings[<?php echo esc_attr( $id ); ?>]" class="label checkbox-label">
					<?php echo esc_attr( $title ); ?> (<code><?php echo esc_attr( $id ); ?></code>)
					<input type="checkbox" name="udb_settings[<?php echo esc_attr( $id ); ?>]" id="udb_settings[<?php echo esc_attr( $id ); ?>]" value="1" <?php checked( $is_checked, 1 ); ?>>
					<div class="indicator"></div>
				</label>
			</div>

			<?php

		}
		?>

	</div>

	<?php

};
