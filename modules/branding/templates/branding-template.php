<?php
/**
 * Branding page template.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$is_pro = udb_is_pro_active();
	$locked = $is_pro ? '' : ' is-locked';

	/**
	 * Without PRO: the upsell strip attached below a PRO box.
	 *
	 * @param string $text The text.
	 */
	$cta = function ( $text ) use ( $is_pro ) {
		if ( $is_pro ) {
			return;
		}
		?>
		<div class="heatbox-cta-container is-attached">
			<div class="heatbox-cta primary">
				<p><?php echo esc_html( $text ); ?></p>
				<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=white_label_link&utm_campaign=udb" class="button button-primary" target="_blank">
					<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
					<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'ultimate-dashboard' ); ?></span>
				</a>
			</div>
		</div>
		<?php
	};
	?>

	<div class="wrap heatbox-wrap udb-branding-page">

		<div class="heatbox-header heatbox-margin-bottom">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<h1 class="title">
							<?php echo esc_html( get_admin_page_title() ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</h1>
						<p class="subtitle"><?php esc_html_e( 'White label & rebrand your WordPress installation.', 'ultimate-dashboard' ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="">
					</div>

				</div>

			</div>

		</div>

		<form method="post" action="options.php">

			<div class="heatbox-container heatbox-container-center">

				<hr class="wp-header-end">

				<?php settings_errors(); ?>

				<?php settings_fields( 'udb-branding-group' ); ?>

				<div class="heatbox<?php echo esc_attr( $locked ); ?>">
					<?php do_settings_sections( 'udb-branding-settings' ); ?>
				</div>

				<?php $cta( __( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ) ); ?>

				<?php do_action( 'udb_after_branding_layout_metabox' ); ?>

				<div class="heatbox<?php echo esc_attr( $locked ); ?>">
					<?php do_settings_sections( 'udb-darkmode-settings' ); ?>
				</div>

				<?php $cta( __( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ) ); ?>

				<?php do_action( 'udb_after_darkmode_metabox' ); ?>

				<?php // Not locked without PRO: the colors can be tried out, they preview live (but aren't saved). ?>
				<div class="heatbox">
					<?php
					do_settings_sections( 'udb-admin-colors-settings' );

					if ( $is_pro ) {
						?>

						<div class="heatbox-overlay">
							<p class="heatbox-overlay-note">
								<?php
								/* translators: %s: name of the "WordPress Admin Branding" box */
								echo wp_kses( sprintf( __( 'Enable %s above to use these settings.', 'ultimate-dashboard' ), '<strong>' . esc_html__( 'WordPress Admin Branding', 'ultimate-dashboard' ) . '</strong>' ), array( 'strong' => array() ) );
								?>
							</p>
						</div>

						<?php
					}
					?>
				</div>

				<?php $cta( __( 'Try it: the colors preview live. Saving them is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ) ); ?>

				<?php do_action( 'udb_after_admin_colors_metabox' ); ?>

				<div class="heatbox<?php echo esc_attr( $locked ); ?>">
					<?php
					do_settings_sections( 'udb-admin-logo-settings' );

					if ( $is_pro ) {
						?>

						<div class="heatbox-overlay">
							<p class="heatbox-overlay-note">
								<?php
								/* translators: %s: name of the "WordPress Admin Branding" box */
								echo wp_kses( sprintf( __( 'Enable %s above to use these settings.', 'ultimate-dashboard' ), '<strong>' . esc_html__( 'WordPress Admin Branding', 'ultimate-dashboard' ) . '</strong>' ), array( 'strong' => array() ) );
								?>
							</p>
						</div>

						<?php
					}
					?>
				</div>

				<?php $cta( __( 'This feature is available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ) ); ?>

				<?php do_action( 'udb_after_admin_logo_metabox' ); ?>

				<div class="heatbox">
					<?php do_settings_sections( 'udb-branding-misc-settings' ); ?>
				</div>

				<?php do_action( 'udb_after_branding_misc_metabox' ); ?>

				<?php submit_button( '', 'button button-primary button-larger' ); ?>

			</div>

		</form>

	</div>

	<?php
};
