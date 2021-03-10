<?php
/**
 * Vars.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to manage global scoped data.
 */
class Vars {

	/**
	 * The data wrapper
	 *
	 * @var array
	 */
	private static $data = array();

	/**
	 * Get value based on name.
	 *
	 * @param string $name The data name.
	 * @return mixed The data value.
	 */
	public static function get( $name ) {

		if ( ! isset( self::$data[ $name ] ) ) {
			return null;
		}

		return self::$data[ $name ];

	}

	/**
	 * Set data by $name & $value pair.
	 *
	 * @param string $name The data name.
	 * @param mixed  $value The data value.
	 */
	public static function set( $name, $value ) {

		self::$data[ $name ] = $value;

	}
}
