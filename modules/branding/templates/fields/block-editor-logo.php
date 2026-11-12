<?php
/**
 * Block editor logo field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="setting-fields">

		<div class="setting-field">
			<input class="all-options" type="text" disabled />
			<button type="button" class="button-secondary button-disabled disabled">
				<?php esc_html_e( 'Add or Upload File', 'ultimate-dashboard' ); ?>
			</button>
		</div>

		<p class="description is-disabled">
			<?php esc_html_e( 'Replace the logo on the top-left inside the WordPress block editor.', 'ultimate-dashboard' ); ?><br>
			<?php echo wp_kses( __( '<strong>Recommended image size:</strong> 512px x 512px.', 'ultimate-dashboard' ), array( 'strong' => array() ) ); ?>
		</p>

	</div>

	<?php
};
