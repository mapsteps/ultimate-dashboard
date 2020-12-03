<?php
/**
 * Settings page template.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form method="post" action="options.php" class="udb-settings-form">

			<?php settings_fields( 'udb-settings-group' ); ?>

			<div class="heatbox is-grouped">
				<?php do_settings_sections( 'udb-widget-settings' ); ?>
			</div>

			<div class="heatbox">
				<?php do_settings_sections( 'udb-widget-styling-settings' ); ?>
			</div>

			<div class="heatbox">
				<?php do_settings_sections( 'udb-general-settings' ); ?>
			</div>

			<div class="heatbox">
				<?php do_settings_sections( 'udb-advanced-settings' ); ?>
			</div>

			<div class="heatbox">
				<?php do_settings_sections( 'udb-misc-settings' ); ?>
			</div>

			<?php submit_button(); ?>

		</form>

	</div>

	<?php
};
