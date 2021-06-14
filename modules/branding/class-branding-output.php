<?php
/**
 * Branding output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Branding;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;

/**
 * Class to setup branding output.
 */
class Branding_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/branding';

	}

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Setup branding output.
	 */
	public function setup() {

		add_filter( 'admin_footer_text', array( self::get_instance(), 'footer_text' ) );
		add_filter( 'update_footer', array( self::get_instance(), 'version_text' ), 20 );

	}

	/**
	 * Footer text.
	 *
	 * @param string $footer_text The footer text.
	 *
	 * @return string The updated footer text.
	 */
	public function footer_text( $footer_text ) {

		$branding = get_option( 'udb_branding' );

		if ( ! empty( $branding['footer_text'] ) ) {
			$footer_text = $branding['footer_text'];
		}

		return $footer_text;

	}

	/**
	 * Version text.
	 *
	 * @param string $version_text The version text.
	 *
	 * @return string The updated version text.
	 */
	public function version_text( $version_text ) {

		$branding = get_option( 'udb_branding' );

		if ( ! empty( $branding['version_text'] ) ) {
			$version_text = $branding['version_text'];
		}

		return $version_text;

	}

}
