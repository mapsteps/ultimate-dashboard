<?php
/**
 * Customize API: Custom CSS setting.
 *
 * This handles validation, sanitization and saving of the value.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\LoginCustomizer;

use Udb\Helpers\Content_Helper;
use WP_Customize_Setting;
use WP_Error;

/**
 * Custom CSS Setting to handle login customizer's custom CSS.
 *
 * @see wp-includes/customize/class-wp-customize-custom-css-setting.php
 */
class Custom_Css_Setting extends WP_Customize_Setting {

	/**
	 * The setting type.
	 *
	 * @var string
	 */
	public $type = 'option';

	/**
	 * Setting Transport
	 *
	 * @var string
	 */
	public $transport = 'postMessage';

	/**
	 * Capability required to edit this setting.
	 *
	 * @var string
	 */
	public $capability = 'edit_css';

	/**
	 * Stylesheet
	 *
	 * @var string
	 */
	public $stylesheet = '';

	/**
	 * Custom_Css_Setting constructor.
	 *
	 * @throws Exception If the setting ID does not match the pattern `custom_css[$stylesheet]`.
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      A specific ID of the setting.
	 *                                      Can be a theme mod or option name.
	 * @param array                $args    Setting arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$this->stylesheet = $this->id_data['keys'][0];
	}

	/**
	 * Add filter to preview post value.
	 *
	 * @return bool False when preview short-circuits due no change needing to be previewed.
	 */
	public function preview() {
		if ( $this->is_previewed ) {
			return false;
		}
		$this->is_previewed = true;
		add_filter( 'wp_get_custom_css', array( $this, 'filter_previewed_wp_get_custom_css' ), 9, 2 );
		return true;
	}

	/**
	 * Filters `wp_get_custom_css` for applying the customized value.
	 *
	 * This is used in the preview when `wp_get_custom_css()` is called for rendering the styles.
	 *
	 * @see wp_get_custom_css()
	 *
	 * @param string $css        Original CSS.
	 * @param string $stylesheet Current stylesheet.
	 * @return string CSS.
	 */
	public function filter_previewed_wp_get_custom_css( $css, $stylesheet ) {
		if ( $stylesheet === $this->stylesheet ) {
			$customized_value = $this->post_value( null );
			if ( ! is_null( $customized_value ) ) {
				$css = $customized_value;
			}
		}
		return $css;
	}

	/**
	 * Validate CSS.
	 *
	 * Checks for imbalanced braces, brackets, and comments.
	 * Notifications are rendered when the customizer state is saved.
	 *
	 * @param string $css The input string.
	 * @return true|WP_Error True if the input was validated, otherwise WP_Error.
	 */
	public function validate( $css ) {
		$validity = new WP_Error();

		if ( preg_match( '#</?\w+#', $css ) ) {
			$validity->add( 'illegal_markup', __( 'Markup is not allowed in CSS.', 'ultimate-dashboard' ) );
		}

		if ( ! $validity->has_errors() ) {
			$validity = parent::validate( $css );
		}

		return $validity;
	}

	/**
	 * Sanitize an input.
	 *
	 * @param string|array $value The value to sanitize.
	 * @return string|array|null|WP_Error Sanitized value, or `null`/`WP_Error` if invalid.
	 */
	public function sanitize( $value ) {

		$content_helper = new Content_Helper();

		$value = $content_helper->sanitize_css( $value );

		/**
		 * Filters a Customize setting value in un-slashed form.
		 *
		 * @since 3.4.0
		 *
		 * @param mixed                $value   Value of the setting.
		 * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
		 */
		return apply_filters( "customize_sanitize_{$this->id}", $value, $this );

	}
}
