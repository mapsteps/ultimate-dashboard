<?php
/**
 * Pro link metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	?>

	<ul class="udb-pro-metabox-content">
		<li><?php esc_html_e( 'Video Widgets', 'ultimate-dashboard' ); ?></li>
		<li><?php esc_html_e( 'Contact Form Widgets', 'ultimate-dashboard' ); ?></li>
		<li><?php esc_html_e( 'Restrict Widgets to specific Users or User Roles', 'ultimate-dashboard' ); ?></li>
		<li>
		<?php
		printf(
			/* translators: %1$s, %2$s, %3$s: page builder names */
			esc_html__( 'Create a Custom Dashboard with %1$s, %2$s or %3$s', 'ultimate-dashboard' ),
			'<strong>Elementor</strong>',
			'<strong>Beaver Builder</strong>',
			'<strong>Brizy</strong>'
		);
		?>
	</li>
	</ul>

	<a style="width: 100%; text-align: center;" href="https://ultimatedashboard.io/docs-category/widgets/?utm_source=plugin&utm_medium=edit_widget_page&utm_campaign=udb" target="_blank" class="button button-primary button-large">
		<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
	</a>

	<?php
};
