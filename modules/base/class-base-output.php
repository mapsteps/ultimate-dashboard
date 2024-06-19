<?php
/**
 * Base module.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Base;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;
use Udb\Helpers\Array_Helper;
use Udb\Helpers\Screen_Helper;
use Udb\Helpers\Content_Helper;
use Udb\Helpers\User_Helper;
use Udb\Helpers\Widget_Helper;

/**
 * Class to setup base output.
 */
class Base_Output {

	/**
	 * Get UDB option data.
	 *
	 * @deprecated 3.7.15 Use get_option() instead.
	 *
	 * @param string $option_name The option name without "udb_" prefix.
	 * @return mixed The value of udb_{$option_name}.
	 */
	public function option( $option_name ) {

		$value = Vars::get( 'udb_' . $option_name );

		if ( $value ) {
			return $value;
		}

		return get_option( 'udb_' . $option_name, array() );

	}

	/**
	 * Array helper.
	 *
	 * @return object Instance of array helper.
	 */
	public function array_helper() {

		return new Array_Helper();

	}

	/**
	 * Content helper.
	 *
	 * @return object Instance of content helper.
	 */
	public function content() {

		return new Content_Helper();

	}

	/**
	 * Screen helper.
	 *
	 * @return object Instance of screen helper.
	 */
	public function screen() {

		return new Screen_Helper();

	}

	/**
	 * User helper.
	 *
	 * @return object Instance of user helper.
	 */
	public function user() {

		return new User_Helper();

	}

	/**
	 * Widget helper.
	 *
	 * @return object Instance of widget helper.
	 */
	public function widget() {

		return new Widget_Helper();

	}
}
