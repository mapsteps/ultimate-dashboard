<?php
/**
 * Widget types metabox.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {
	global $post;

	$udb_widget_types = array(
		'icon' => __( 'Icon Widget', 'ultimate-dashboard' ),
		'text' => __( 'Text Widget', 'ultimate-dashboard' ),
		'html' => __( 'HTML Widget', 'ultimate-dashboard' ),
	);

	$udb_widget_types = apply_filters( 'udb_widget_types', $udb_widget_types );
	$stored_meta      = get_post_meta( $post->ID, 'udb_widget_type', true );

	?>

	<div class="neatbox is-smooth has-bigger-heading has-subboxes">
		<div class="subbox">
			<h2><?php _e( 'Widget Type', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<div class="input-control">
					<select name="udb_widget_type">
						<?php
						foreach ( $udb_widget_types as $value => $text ) {
							?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $stored_meta ); ?>><?php echo esc_html( $text ); ?></option>
							<?php
						}
						?>
					</select>
				</div>
			</div>
		</div>
		<div class="widget-fields">
			<?php do_action( 'udb_metabox_widgets' ); ?>
		</div>
	</div>

	<?php
};
