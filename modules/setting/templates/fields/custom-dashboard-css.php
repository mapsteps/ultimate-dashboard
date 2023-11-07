<?php
/**
 * Custom dashboard css field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings   = get_option( 'udb_settings' );
	$custom_css = isset( $settings['custom_css'] ) ? $settings['custom_css'] : false;

	if ( $custom_css ) {
		$custom_css = wp_strip_all_tags( $custom_css );
		$custom_css = wp_filter_nohtml_kses( $custom_css );
		$custom_css = strtr(
			$custom_css,
			array(
				'&gt;' => '>',
				"\'"   => "'",
				'\"'   => '"',
			)
		);
	}

	?>

	<textarea id="udb-custom-dashboard-css" class="widefat textarea udb-custom-css" name="udb_settings[custom_css]"><?php echo $custom_css; ?></textarea>

	<?php
};
