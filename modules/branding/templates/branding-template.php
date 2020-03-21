<?php
/**
 * Branding page template.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

?>

<div class="wrap settingstuff">

	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<form method="post" action="options.php" class="udb-settings-form">

		<div class="neatbox has-subboxes has-bigger-heading is-grouped is-smooth udb-pro-box">
			<?php do_settings_sections( 'udb-detailed-branding' ); ?>
		</div>

		<div class="neatbox has-bigger-heading is-smooth udb-pro-box">
			<?php do_settings_sections( 'udb-general-branding' ); ?>
		</div>

		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=remove_3rd_party_widgets_link&utm_campaign=udb" class="button button-primary" target="_blank">
			<?php _e( 'Get Ultimate Dashboard PRO!', 'ultimate-dashboard' ); ?>
		</a>
	</form>

</div>
