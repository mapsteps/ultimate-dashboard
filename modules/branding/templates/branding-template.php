<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<div class="wrap heatbox-wrap">

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form method="post" action="options.php" class="udb-settings-form">
			<?php settings_fields( 'udb-branding-group' ); ?>

			<div class="heatbox">
				<?php do_settings_sections( 'udb-branding-settings' ); ?>
			</div>

			<div class="heatbox">
				<?php do_settings_sections( 'udb-branding-misc-settings' ); ?>
			</div>

			<?php submit_button(); ?>
		</form>

	</div>

	<?php
};
