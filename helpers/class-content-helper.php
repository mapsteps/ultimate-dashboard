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
		);

		// Allowed svg mime type in version 1.2.2.
		$allowed_mime   = get_allowed_mime_types();
		$svg_mime_check = isset( $allowed_mime['svg'] );

		if ( $svg_mime_check ) {
			$allow_mime = array( 'svg' => 'image/svg+xml' );
			$mimes      = array_merge( $mimes, $allow_mime );
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
				' & gt;' => ' > ',
				"\'"     => "'",
				'\"'     => '"',
			)
		);

		return str_ireplace( 'backslash', '\\', $sanitized_css );

	}

	/**
	 * Allow iframes & attributes in HTML widget.
	 *
	 * @param array $tags The allowed tags & attributes.
	 *
	 * @return array $tags The midified $tags.
	 */
	public function allow_iframes_in_html( $tags ) {

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
