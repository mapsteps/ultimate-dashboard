<?php
/**
 * Custom dashboard css field.
 *
 * @package Ultimate_Dashboard
 */

use Udb\Helpers\Content_Helper;

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function () {

	$settings       = get_option( 'udb_settings' );
	$custom_css     = isset( $settings['custom_css'] ) ? $settings['custom_css'] : false;
	$content_helper = new Content_Helper();

	if ( $custom_css ) {
		$custom_css = $content_helper->sanitize_css( $custom_css );
	}

	?>

	<textarea id="udb-custom-dashboard-css"
			  class="widefat textarea udb-custom-css"
			  name="udb_settings[custom_css]"><?php echo $custom_css; ?></textarea>

	<?php
};
