<?php
/**
 * Array helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup array helper.
 */
class Array_Helper {
	/**
	 * Find associative array's index by it's key's value.
	 *
	 * We don't use the array_search combined with array_column method
	 * because it doesn't work in udb admin menu module.
	 *
	 * @see https://stackoverflow.com/questions/8102221/php-multidimensional-array-searching-find-key-by-specific-value
	 *
	 * @param array  $array The haystack array.
	 * @param string $key The key to search in.
	 * @param mixed  $value The value to search for.
	 *
	 * @return array The index if found.
	 */
	public function find_assoc_array_index_by_value( $array, $key, $value ) {
		foreach ( $array as $index => $item ) {
			if ( isset( $item[ $key ] ) && $item[ $key ] === $value ) {
				return $index;
			}
		}

		return false;
	}

	/**
	 * Clean a serialized array from nested-serialized.
	 *
	 * The returned $value after unserialized should be an array.
	 * If it's still a string, then we need to unserialize it.
	 * This was related to roles issue on export / import.
	 *
	 * @param string $value The value to clean.
	 * @param int    $depth The depth of the checking.
	 *
	 * @return array The unserialized array.
	 */
	public function clean_unserialize( $value, $depth = 2 ) {
		for ( $i = 0; $i < $depth; $i++ ) {
			if ( is_serialized( $value ) ) {
				$value = unserialize( $value );

				if ( ! is_serialized( $value ) ) {
					break;
				}
			} else {
				break;
			}
		}

		return $value;
	}
}
