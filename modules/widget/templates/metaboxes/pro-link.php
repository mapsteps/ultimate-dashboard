<?php
/**
 * Pro link metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$features = array(
		esc_html__( 'Video Widgets', 'ultimate-dashboard' ),
		esc_html__( 'Contact Form Widgets', 'ultimate-dashboard' ),
		esc_html__( 'Restrict Widgets to specific Users or User Roles', 'ultimate-dashboard' ),
		sprintf(
			/* translators: %1$s: "Block Editor", %2$s: list of page builders */
			esc_html__( 'Create a Custom Dashboard with the %1$s or your page builder: %2$s', 'ultimate-dashboard' ),
			'<strong>' . esc_html__( 'Block Editor', 'ultimate-dashboard' ) . '</strong>',
			'<strong>Elementor, Divi, Bricks, Breakdance, Beaver Builder, Oxygen & Brizy</strong>'
		),
	);
	?>

	<ul class="heatbox-features">
		<?php foreach ( $features as $feature ) : ?>
			<li>
				<span class="dashicons dashicons-yes" aria-hidden="true"></span>
				<div><?php echo wp_kses( $feature, array( 'strong' => array() ) ); ?></div>
			</li>
		<?php endforeach; ?>
	</ul>

	<p>
		<a href="https://ultimatedashboard.io/pro/?utm_source=plugin&utm_medium=edit_widget_page&utm_campaign=udb" target="_blank" class="button button-primary button-full">
			<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'ultimate-dashboard' ); ?></span>
		</a>
	</p>

	<?php

};
