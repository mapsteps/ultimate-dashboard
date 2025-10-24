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
		<li><?php esc_html_e( 'Create a Custom Dashboard with <strong>Elementor</strong>, <strong>Beaver Builder</strong> or <strong>Brizy</strong>', 'ultimate-dashboard' ); ?></li>
	</ul>

	<a style="width: 100%; text-align: center;" href="https://ultimatedashboard.io/docs-category/widgets/?utm_source=plugin&utm_medium=edit_widget_page&utm_campaign=udb" target="_blank" class="button button-primary button-large">
		<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
	</a>

	<?php
};
