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

		<?php settings_fields( 'udb-settings-group' ); ?>

		<div class="neatbox has-subboxes is-grouped has-bigger-heading is-smooth">
			<?php do_settings_sections( 'udb-widgets-page' ); ?>
		</div>

		<div class="neatbox has-subboxes has-bigger-heading is-smooth">
			<?php do_settings_sections( 'udb-general-page' ); ?>
		</div>

		<?php submit_button(); ?>

	</form>
</div>
