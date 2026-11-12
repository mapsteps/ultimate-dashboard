<?php
/**
 * Heatbox header above WordPress' own list screens (All Widgets, Admin Pages).
 *
 * Printed on `in_admin_header`, so it sits above the Screen Options tab and the notices.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Print the header.
 *
 * @param string $title    The title.
 * @param string $subtitle The line below the title.
 */
return function ( $title, $subtitle ) {
	?>

	<div class="heatbox-wrap udb-post-list-header">

		<div class="heatbox-header">

			<div class="heatbox-container heatbox-container-center">

				<div class="logo-container">

					<div>
						<h1 class="title">
							<?php echo esc_html( $title ); ?>
							<span class="version"><?php echo esc_html( ULTIMATE_DASHBOARD_PLUGIN_VERSION ); ?></span>
						</h1>
						<p class="subtitle"><?php echo esc_html( $subtitle ); ?></p>
					</div>

					<div>
						<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="">
					</div>

				</div>

			</div>

		</div>

	</div>

	<?php
};
