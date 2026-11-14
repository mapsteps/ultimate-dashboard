<?php
/**
 * Widget type metabox (sidebar).
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$widget_types = array(
		'icon' => __( 'Icon Widget', 'ultimate-dashboard' ),
		'text' => __( 'Text Widget', 'ultimate-dashboard' ),
		'html' => __( 'HTML Widget', 'ultimate-dashboard' ),
	);

	$widget_types = apply_filters( 'udb_widget_types', $widget_types );
	$stored_meta  = get_post_meta( $post->ID, 'udb_widget_type', true );
	?>

	<select name="udb_widget_type" aria-label="<?php esc_attr_e( 'Widget Type', 'ultimate-dashboard' ); ?>">
		<?php foreach ( $widget_types as $value => $text ) { ?>
			<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $stored_meta ); ?>><?php echo esc_html( $text ); ?></option>
		<?php } ?>
	</select>

	<?php
};
