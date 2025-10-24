<?php
/**
 * Placeholer tags metabox
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox tags-heatbox">
	<h2>
		<?php esc_html_e( 'Placeholder Tags', 'ultimate-dashboard' ); ?>
		<span class="action-status">📋 Copied</span>
	</h2>

	<div class="heatbox-content">
		<p>
			<?php esc_html_e( 'Use the placeholder tags below to display certain information dynamically.', 'ultimate-dashboard' ); ?>
			<br><strong><?php esc_html_e( '(Click to copy)', 'ultimate-dashboard' ); ?></strong>
		</p>
		<div class="tags-wrapper">
			<?php
			$placeholder_tags = [
				'{site_name}',
				'{site_url}',
			];

			$placeholder_tags = apply_filters( 'udb_admin_menu_placeholder_tags', $placeholder_tags );
			$total_tags       = count( $placeholder_tags );

			foreach ( $placeholder_tags as $tag_index => $placeholder_tag ) {
				?>
				<code><?php echo esc_attr( $placeholder_tag ); ?></code>
				<?php
			}
			?>
		</div>
	</div>
</div>
