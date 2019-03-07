<?php
/**
 * Exports Page Template
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<hr class="wp-header-end">

	<div class="settingstuff">
		<form method="post" action="options.php">
			<div class="clearfix">
				<div class="left-col">

					<div class="postbox">
						<h2 class="hndle is-undraggable">
							<span><?php esc_html_e( 'Export Widgets', 'ultimate-dashboard' ); ?></span>
						</h2>
						<div class="inside">
							<p><?php esc_html_e( ' Use the export button to export to a .json file which you can then import to another Ultimate Dashboard installation.', 'ultimate-dashboard' ); ?></p>

							<div class="buttons">
								<a target="_blank" href="<?php echo esc_url( $export_url ); ?>" class="button button-primary">Export File</a>
							</div>
						</div>
					</div>

				</div>
				<div class="right-col">

					<div class="postbox">
						<h2 class="hndle is-undraggable">
							<span><?php esc_html_e( 'Import Widgets', 'ultimate-dashboard' ); ?></span>
						</h2>
						<div class="inside">
							<p><?php esc_html_e( 'Select the Widgets JSON file you would like to import. When you click the import button below, Ultimate Dashboard will import the widgets.', 'ultimate-dashboard' ); ?></p>

							<div class="fields-wrapper">
								<label class="block-label" for="udb_import_file"><?php esc_html_e( 'Select File', 'ultimate-dashboard' ); ?></label>
								<input type="file" name="udb_import_file">
							</div>

							<div class="buttons">
								<a target="_blank" href="<?php echo esc_url( $export_url ); ?>" class="button button-primary">Import File</a>
							</div>
						</div>
					</div>

				</div>
			</div>
		</form>
	</div> <!-- /settingstuff -->
</div> <!-- /wrap -->

