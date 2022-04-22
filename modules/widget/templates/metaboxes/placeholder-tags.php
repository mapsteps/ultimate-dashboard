<?php
/**
 * Placeholder tags metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Widget\Widget_Output;

return function () {

	$widget_output    = Widget_Output::get_instance();
	$placeholder_tags = $widget_output->placeholder_tags;
	$placeholder_tags = apply_filters( 'udb_widgets_placeholder_tags', $placeholder_tags );
	$total_tags       = count( $placeholder_tags );
	?>

	<p>
		Use the placeholder tags below in title or content fields to output certain information.
	</p>

	<p>
		<?php
		foreach ( $placeholder_tags as $tag_index => $placeholder_tag ) {
			?>
			<code><?php echo esc_attr( $placeholder_tag ); ?></code><?php echo ( $total_tags - 1 === $tag_index ? '' : ',' ); ?>
			<?php
		}
		?>
	</p>

	<?php

};
