<?php
/**
 * Settings page template.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

?>

<div class="wrap settingstuff">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form method="post" action="options.php" class="udb-settings-form">
		<div class="neatbox has-subboxes has-bigger-heading is-smooth">
			<?php
			settings_fields( 'udb-settings-group' );
			do_settings_sections( 'ultimate-dashboard' );
			submit_button();
			?>
		</div>
	</form>
</div>
