<?php
/**
 * Main metabox: the box per widget type (only the chosen type's box shows; the type is picked in the sidebar).
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="udb-main-metabox">
		<?php wp_nonce_field( 'udb_save_widget', 'udb_widget_nonce' ); ?>

		<div class="widget-fields">
			<?php do_action( 'udb_widget_metabox' ); ?>
		</div>

	</div>

	<?php
};
