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
	 * Find associative array's index by its key's value.
	 *
	 * We don't use the array_search combined with array_column method
	 * because it doesn't work in udb admin menu module.
	 *
	 * @see https://stackoverflow.com/questions/8102221/php-multidimensional-array-searching-find-key-by-specific-value
	 *
	 * @param array  $arr The haystack array.
	 * @param string $key The key to search in.
	 * @param mixed  $value The value to search for.
	 *
	 * @return false|int The index if found, false otherwise.
	 */
	public function find_assoc_array_index_by_value( $arr, $key, $value ) {
		foreach ( $arr as $index => $item ) {
			if ( isset( $item[ $key ] ) && $item[ $key ] === $value ) {
				return $index;
			}
		}

		return false;
	}

	/**
	 * Check if specific array key exists in multi-dimensional array.
	 *
	 * @see https://stackoverflow.com/questions/19420715/check-if-specific-array-key-exists-in-multidimensional-array-php#answer-19421079
	 * Check on "Alexandre Nucera" answer.
	 *
	 * @param array  $arr The array.
	 * @param string $key The key.
	 *
	 * @return bool
	 */
	public function nested_key_exists( $arr, $key ) {

		// is in base array?
		if ( array_key_exists( $key, $arr ) ) {
			return true;
		}

		// Check arrays contained in this array.
		foreach ( $arr as $element ) {
			if ( is_array( $element ) ) {
				if ( $this->nested_key_exists( $element, $key ) ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Clean up a (multiple) serialized array.
	 *
	 * The returned $value after unserialization should be an array by default.
	 * If it's still a string, then we need to unserialize it again.
	 *
	 * This is related to a role import/export issue in earlier releases.
	 * Note that this can't be removed in the future to maintain full backwards compatibility.
	 *
	 * @param string $value The value to clean.
	 * @param int    $depth The depth of the checking.
	 *
	 * @return array The unserialized array.
	 */
	public function clean_unserialize( $value, $depth = 2 ) {
		for ( $i = 0; $i < $depth; $i++ ) {
			if ( is_serialized( $value ) ) {
				// phpcs:ignore
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
