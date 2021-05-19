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
		<li><?php _e( 'Use <strong>Elementor</strong>, <strong>Beaver Builder</strong> or <strong>Brizy</strong> to create custom Admin Pages', 'ultimate-dashboard' ); ?></li>
		<li><?php _e( 'Restrict Admin Pages to specific Users or User Roles', 'ultimate-dashboard' ); ?></li>
	</ul>

	<a href="https://ultimatedashboard.io/docs/admin-pages/?utm_source=plugin&utm_medium=edit_admin_page&utm_campaign=udb" target="_blank" class="button button-primary button-large">
		<?php _e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
	</a>

	<?php
};
