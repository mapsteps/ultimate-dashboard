<?php
/**
 * Exports Page Template
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="wrap settingstuff">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<?php settings_errors(); ?>

	<div class="clearfix">
		<div class="left-col">

			<form method="post" action="options.php">
				<div class="postbox">
					<?php
					settings_fields( 'udb-export-group' );
					do_settings_sections( 'ultimate-dashboard-export' );
					submit_button( __( 'Export File', 'ultimate-dashboard' ) );
					?>
				</div>
			</form>

		</div><!-- /left-col -->
		<div class="right-col">

			<form method="post" action="options.php" enctype="multipart/form-data">
				<div class="postbox">
					<?php
					settings_fields( 'udb-import-group' );
					do_settings_sections( 'ultimate-dashboard-import' );
					submit_button( __( 'Import File', 'ultimate-dashboard' ) );
					?>
				</div>
			</form>

		</div><!-- /right-col -->
	</div><!-- /clearfix -->

</div><!-- /wrap -->

