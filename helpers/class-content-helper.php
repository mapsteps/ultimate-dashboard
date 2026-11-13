<?php
/**
 * Content helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

use WP_Customize_Setting;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to set up content helper.
 */
class Content_Helper {

	/**
	 * Image sanitization callback.
	 *
	 * Checks the image's file extension and mime type against a whitelist. If they're allowed,
	 * send back the filename, otherwise, return the setting default.
	 *
	 * - Sanitization: image file extension
	 * - Control: text, WP_Customize_Image_Control
	 *
	 * @see wp_check_filetype() https://developer.wordpress.org/reference/functions/wp_check_filetype/
	 *
	 * @param string               $image Image filename.
	 * @param WP_Customize_Setting $setting Setting instance.
	 *
	 * @return string The image filename if the extension is allowed; otherwise, the setting default.
	 */
	public function sanitize_image( $image, $setting ) {

		/**
		 * Array of valid image file types.
		 *
		 * The array includes image mime types that are included in wp_get_mime_types()
		 */
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',
			'webp'         => 'image/webp',
			'avif'         => 'image/avif',
		);

		// Merge image mime types allowed on the site (e.g. svg, webp, avif).
		$allowed_mime = get_allowed_mime_types();

		if ( is_array( $allowed_mime ) ) {
			foreach ( $allowed_mime as $ext => $mime ) {
				if ( 0 === strpos( $mime, 'image/' ) ) {
					$mimes[ $ext ] = $mime;
				}
			}
		}

		// Return an array with file extension and mime_type.
		$file = wp_check_filetype( $image, $mimes );

		// If $image has a valid mime_type, return it; otherwise, return the default.
		return esc_url_raw( ( $file['ext'] ? $image : $setting->default ) );

	}

	/**
	 * Get the editor/builder of the given post.
	 *
	 * @param int $post_id ID of the post being checked.
	 *
	 * @return string The content editor name.
	 */
	public function get_content_editor( $post_id ) {
		$content_editor = 'default';

		return apply_filters( 'udb_content_editor', $content_editor, $post_id );
	}

	/**
	 * Strip tags and its content from the given string.
	 *
	 * @link https://stackoverflow.com/questions/14684077/remove-all-html-tags-from-php-string/#answer-39320168
	 *
	 * @param string $text The string being stripped.
	 *
	 * @return string The stripped string.
	 */
	public function strip_tags_content( $text ) {

		if ( is_null( $text ) ) {
			return '';
		}

		$cleanup = preg_replace( '@<(\w+)\b.*?>.*?</\1>@si', '', $text );
		$cleanup = wp_strip_all_tags( $cleanup );

		return trim( $cleanup );

	}

	/**
	 * Sanitize css content (not a real sanitizing).
	 *
	 * @deprecated 3.7.12 Use sanitize_css() instead.
	 * @see https://github.com/WordPress/WordPress/blob/56c162fbc9867f923862f64f1b4570d885f1ff03/wp-includes/customize/class-wp-customize-custom-css-setting.php#L157
	 *
	 * @param string $text The string being sanitized.
	 * @return string The sanitized string.
	 */
	public function sanitize_css_content( $text ) {

		return $this->sanitize_css( $text );

	}

	/**
	 * Sanitize css.
	 *
	 * @param string $text The string being sanitized.
	 *
	 * @return string The sanitized string.
	 */
	public function sanitize_css( $text ) {

		$text = wp_unslash( $text );

		$sanitized_css = str_ireplace( '\\', 'backslash', $text );
		$sanitized_css = wp_strip_all_tags( $sanitized_css );
		$sanitized_css = wp_filter_nohtml_kses( $sanitized_css );
		$sanitized_css = strtr(
			$sanitized_css,
			array(
				'&gt;'  => '>',
				'&amp;' => '&',
				"\'"    => "'",
				'\"'    => '"',
			)
		);

		return str_ireplace( 'backslash', '\\', $sanitized_css );

	}

	/**
	 * Sanitize a color before printing it into CSS.
	 *
	 * Accepts hex (#rgb, #rgba, #rrggbb, #rrggbbaa), rgb() / rgba() and plain color keywords.
	 * Anything else returns an empty string, so a saved value can't break out of its CSS rule.
	 *
	 * @param mixed $color The color.
	 * @return string The color, or an empty string if it's not a valid color.
	 */
	public function sanitize_color( $color ) {

		if ( ! is_string( $color ) ) {
			return '';
		}

		$color = trim( $color );

		if ( preg_match( '/^#([0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $color ) ) {
			return $color;
		}

		if ( preg_match( '/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*(,\s*(\d*\.?\d+%?)\s*)?\)$/i', $color ) ) {
			return $color;
		}

		if ( preg_match( '/^[a-z]+$/i', $color ) ) {
			return $color;
		}

		return '';

	}

	/**
	 * Sanitize a plain CSS value (size, position, keyword) before printing it into CSS.
	 *
	 * Keeps letters, numbers, spaces and the characters used in sizes and positions (". % # , ( ) -").
	 * Removes everything that could end a CSS rule or start a new one (; { } : / quotes, < >).
	 *
	 * @param mixed $value The value.
	 * @return string The sanitized value.
	 */
	public function sanitize_css_value( $value ) {

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		return trim( preg_replace( '/[^a-zA-Z0-9.%#,()\s\-]/', '', (string) $value ) );

	}

	/**
	 * Sanitize a CSS size (a number with an optional unit), e.g. for the customizer's range controls.
	 *
	 * @param mixed $value The value, e.g. "40%" or "320px".
	 * @return string The size, or an empty string if it's not a valid size.
	 */
	public function sanitize_css_size( $value ) {

		if ( ! is_scalar( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );

		if ( preg_match( '/^\d*\.?\d+(px|%|em|rem|vw|vh)?$/i', $value ) ) {
			return $value;
		}

		return '';

	}

	/**
	 * Allow iframes & attributes in HTML widget.
	 *
	 * Only for the "post" context, which is what widgets and admin pages use.
	 * Other contexts (comments, user & term descriptions, "strip" …) stay untouched.
	 *
	 * And only when saved by someone who can manage Ultimate Dashboard,
	 * or when content is displayed in wp-admin (widgets & admin pages are shown to all roles).
	 * So authors & contributors saving their own posts get WordPress' default rules.
	 *
	 * @param array  $tags    The allowed tags & attributes.
	 * @param string $context The kses context.
	 *
	 * @return array $tags The modified $tags.
	 */
	public function allow_iframes_in_html( $tags, $context = 'post' ) {

		if ( 'post' !== $context ) {
			return $tags;
		}

		$can_manage = current_user_can( apply_filters( 'udb_settings_capability', 'manage_options' ) );
		$is_display = is_admin() && isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'];

		if ( ! $can_manage && ! $is_display ) {
			return $tags;
		}

		$tags['iframe'] = array(
			'align'               => true,
			'width'               => true,
			'height'              => true,
			'frameborder'         => true,
			'name'                => true,
			'src'                 => true,
			'id'                  => true,
			'class'               => true,
			'style'               => true,
			'scrolling'           => true,
			'marginwidth'         => true,
			'marginheight'        => true,
			'allow'               => true,
			'allowfullscreen'     => true,
			'allowpaymentrequest' => true,
			'picture-in-picture'  => true,
		);

		$tags['source'] = array(
			'src'    => true,
			'type'   => true,
			'srcset' => true,
			'media'  => true,
		);

		return $tags;

	}

}
