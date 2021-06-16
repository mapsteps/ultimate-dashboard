<?php
/**
 * Color helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup color helper.
 */
class Color_Helper {

	/**
	 * Get color in rgb format from the provided color in hex format.
	 *
	 * @param string $hex_color Color in hex format.
	 * @return array Array containing r,g,b.
	 */
	public function hex_to_rgb( $hex_color ) {

		$hex_color = str_replace( '#', '', $hex_color );
		$hex_color = trim( $hex_color );

		if ( 3 !== strlen( $hex_color ) && 6 !== strlen( $hex_color ) ) {
			return array( 255, 255, 255 );
		}

		if ( 3 === strlen( $hex_color ) ) {
			$r = hexdec( substr( $hex_color, 0, 1 ) . substr( $hex_color, 0, 1 ) );
			$g = hexdec( substr( $hex_color, 1, 1 ) . substr( $hex_color, 1, 1 ) );
			$b = hexdec( substr( $hex_color, 2, 1 ) . substr( $hex_color, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex_color, 0, 2 ) );
			$g = hexdec( substr( $hex_color, 2, 2 ) );
			$b = hexdec( substr( $hex_color, 4, 2 ) );
		}

		return array( $r, $g, $b );

	}

}
