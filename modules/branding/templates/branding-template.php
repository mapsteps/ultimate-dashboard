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
			<div class="neatbox-content">
				<div class="udb-pro-description">
					<?php _e( 'Plugins or themes can add their own dashboard widgets. These widgets can be disabled using this options which are available in PRO version.', 'ultimate-dashboard' ); ?>
				</div>
				<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=remove_3rd_party_widgets_link&utm_campaign=udb" class="button button-primary" target="_blank">
					<?php _e( 'Get Ultimate Dashboard PRO!', 'ultimate-dashboard' ); ?>
				</a>
			</div>
		</div>

		<div class="neatbox has-bigger-heading is-smooth udb-pro-box">
			<?php do_settings_sections( 'udb-general-branding' ); ?>
		</div>

		<?php submit_button(); ?>
	</form>

</div>
