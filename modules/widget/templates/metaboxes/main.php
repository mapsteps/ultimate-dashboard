<?php
/**
 * Widget types metabox.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	global $post;

	$widget_types = array(
		'icon' => __( 'Icon Widget', 'ultimate-dashboard' ),
		'text' => __( 'Text Widget', 'ultimate-dashboard' ),
		'html' => __( 'HTML Widget', 'ultimate-dashboard' ),
	);

	$widget_types = apply_filters( 'udb_widget_types', $widget_types );
	$stored_meta  = get_post_meta( $post->ID, 'udb_widget_type', true );

	?>

	<div class="udb-main-metabox">
		<div class="postbox">
			<div class="postbox-header">
				<h2><?php esc_html_e( 'Widget Type', 'ultimate-dashboard' ); ?></h2>
			</div>
			<?php wp_nonce_field( 'udb_widget_type', 'udb_widget_type_nonce' ); ?>
			<div class="inside">
				<select name="udb_widget_type">
					<?php foreach ( $widget_types as $value => $text ) { ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $stored_meta ); ?>><?php echo esc_html( $text ); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<div class="widget-fields">
			<?php do_action( 'udb_widget_metabox' ); ?>
		</div>

	</div>

	<?php
};
