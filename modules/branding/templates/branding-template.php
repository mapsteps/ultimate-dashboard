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

	<?php settings_errors(); ?>

	<form method="post" action="options.php" class="udb-settings-form">
		<?php settings_fields( 'udb-branding-settings-group' ); ?>

		<div class="neatbox has-bigger-heading is-smooth udb-pro-box">
			<?php do_settings_sections( 'udb-detailed-branding' ); ?>
		</div>

		<div class="neatbox has-bigger-heading is-smooth udb-pro-box">
			<?php do_settings_sections( 'udb-general-branding' ); ?>
		</div>

		<?php submit_button(); ?>
	</form>

</div>
