<?php
/**
 * Position metabox: the dashboard column and the priority within it.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $post ) {

	$position = get_post_meta( $post->ID, 'udb_position_key', true );
	$position = $position ? $position : 'normal';
	$priority = get_post_meta( $post->ID, 'udb_priority_key', true );
	$priority = $priority ? $priority : 'default';

	$groups = array(
		array(
			'name'    => 'udb_metabox_position',
			'label'   => __( 'Column', 'ultimate-dashboard' ),
			'value'   => $position,
			'options' => array(
				'normal' => __( 'Left column', 'ultimate-dashboard' ),
				'side'   => __( 'Right column', 'ultimate-dashboard' ),
			),
		),
		array(
			'name'    => 'udb_metabox_priority',
			'label'   => __( 'Priority', 'ultimate-dashboard' ),
			'value'   => $priority,
			'options' => array(
				'default' => __( 'Default', 'ultimate-dashboard' ),
				'low'     => __( 'Low', 'ultimate-dashboard' ),
				'high'    => __( 'High', 'ultimate-dashboard' ),
			),
		),
	);

	foreach ( $groups as $group ) :
		$label_id = $group['name'] . '_label';
		?>

		<div class="udb-position-group" role="radiogroup" aria-labelledby="<?php echo esc_attr( $label_id ); ?>">
			<p class="udb-position-label" id="<?php echo esc_attr( $label_id ); ?>"><?php echo esc_html( $group['label'] ); ?></p>
			<?php foreach ( $group['options'] as $value => $text ) : ?>
				<label>
					<input type="radio" name="<?php echo esc_attr( $group['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php checked( $group['value'], $value ); ?>>
					<?php echo esc_html( $text ); ?>
				</label>
			<?php endforeach; ?>
		</div>

		<?php
	endforeach;

};
