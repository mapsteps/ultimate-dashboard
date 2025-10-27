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
		<li>
			<?php
			printf(
				/* translators: %1$s, %2$s, %3$s: page builder names */
				esc_html__( 'Use %1$s, %2$s or %3$s to create custom Admin Pages', 'ultimate-dashboard' ),
				'<strong>Elementor</strong>',
				'<strong>Beaver Builder</strong>',
				'<strong>Brizy</strong>'
			);
			?>
		</li>
		<li><?php esc_html_e( 'Restrict Admin Pages to specific Users or User Roles', 'ultimate-dashboard' ); ?></li>
	</ul>

	<a href="https://ultimatedashboard.io/docs/admin-pages/?utm_source=plugin&utm_medium=edit_admin_page&utm_campaign=udb" target="_blank" class="button button-primary button-large">
		<?php esc_html_e( 'Get Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
	</a>

	<?php
};
