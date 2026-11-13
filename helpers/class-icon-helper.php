<?php
/**
 * Icon helper: icon widgets & admin page menu icons.
 *
 * Saved icons are one of:
 * - a Lucide icon name ("gauge"), drawn as inline SVG (since 3.9),
 * - Dashicons classes ("dashicons dashicons-admin-post"),
 * - Font Awesome classes from before 3.9 ("fas fa-tachometer-alt", "fa fa-dashboard"). Font Awesome is gone,
 *   these are shown as the closest Lucide icon, or the fallback icon (brand icons, unknown names).
 *   Saved values aren't changed: picking a new icon saves a Lucide name.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to setup icon helper.
 */
class Icon_Helper {

	/**
	 * The Lucide icon for icons without a match.
	 *
	 * @var string
	 */
	const FALLBACK = 'link';

	/**
	 * The Lucide icons (inner SVG markup, keyed by name), loaded once.
	 *
	 * @var array|null
	 */
	private static $icons = null;

	/**
	 * Font Awesome names and their Lucide icon, loaded once.
	 *
	 * @var array|null
	 */
	private static $font_awesome_map = null;

	/**
	 * Get all Lucide icons.
	 *
	 * @return array The inner SVG markup of each icon, keyed by icon name.
	 */
	public function get_icons() {

		if ( null === self::$icons ) {
			$icons       = json_decode( (string) file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/icons/lucide.json' ), true );
			self::$icons = is_array( $icons ) ? $icons : array();
		}

		return self::$icons;

	}

	/**
	 * Whether a saved icon is a Dashicon.
	 *
	 * @param string $icon The saved icon.
	 * @return bool
	 */
	public function is_dashicon( $icon ) {

		return is_string( $icon ) && 0 === strpos( trim( $icon ), 'dashicons' );

	}

	/**
	 * Get the Lucide icon to show for a saved icon that isn't a Dashicon.
	 *
	 * @param string $icon The saved icon: a Lucide name or old Font Awesome classes.
	 * @return string The Lucide icon name.
	 */
	public function resolve( $icon ) {

		$icon  = is_string( $icon ) ? strtolower( trim( $icon ) ) : '';
		$icons = $this->get_icons();

		// Font Awesome classes, like "fas fa-tachometer-alt" or "fa fa-dashboard". Brand icons ("fab") have no match.
		if ( preg_match( '/^(fas|far|fa)\s+fa-([a-z0-9-]+)$/', $icon, $matches ) ) {
			if ( null === self::$font_awesome_map ) {
				self::$font_awesome_map = require __DIR__ . '/font-awesome-map.php';
			}

			$icon = isset( self::$font_awesome_map[ $matches[2] ] ) ? self::$font_awesome_map[ $matches[2] ] : '';
		}

		return isset( $icons[ $icon ] ) ? $icon : self::FALLBACK;

	}

	/**
	 * Get the HTML of a saved icon: a Dashicon <i>, or an inline SVG for everything else.
	 *
	 * @param string $icon The saved icon.
	 * @return string The HTML, safe to print.
	 */
	public function html( $icon ) {

		if ( $this->is_dashicon( $icon ) ) {
			return '<i class="' . esc_attr( trim( $icon ) ) . '" aria-hidden="true"></i>';
		}

		$name  = $this->resolve( $icon );
		$icons = $this->get_icons();

		// The inner markup comes from our own icon file (paths, circles, rects… with plain attributes).
		return '<svg class="udb-icon" data-icon="' . esc_attr( $name ) . '" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';

	}

	/**
	 * Get a saved icon (not a Dashicon) as an SVG data URL, black on transparent, for a CSS mask.
	 *
	 * @param string $icon The saved icon.
	 * @return string The data URL.
	 */
	public function mask_url( $icon ) {

		$name  = $this->resolve( $icon );
		$icons = $this->get_icons();
		$svg   = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $icons[ $name ] . '</svg>';

		return 'data:image/svg+xml,' . rawurlencode( $svg );

	}

	/**
	 * The SVG tags & attributes of the icons, for wp_kses().
	 *
	 * @return array
	 */
	public function allowed_svg_tags() {

		$shape = array(
			'd'      => true,
			'x'      => true,
			'y'      => true,
			'x1'     => true,
			'x2'     => true,
			'y1'     => true,
			'y2'     => true,
			'cx'     => true,
			'cy'     => true,
			'r'      => true,
			'rx'     => true,
			'ry'     => true,
			'width'  => true,
			'height' => true,
			'points' => true,
			'fill'   => true,
		);

		return array(
			'svg'      => array(
				'class'           => true,
				'data-icon'       => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
				'aria-hidden'     => true,
				'focusable'       => true,
			),
			'path'     => $shape,
			'circle'   => $shape,
			'ellipse'  => $shape,
			'line'     => $shape,
			'polygon'  => $shape,
			'polyline' => $shape,
			'rect'     => $shape,
		);

	}

}
