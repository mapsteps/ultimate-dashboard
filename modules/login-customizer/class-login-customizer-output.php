<?php
/**
 * Login customizer output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\LoginCustomizer;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Base_Output;
use Udb\Helpers\Content_Helper;

/**
 * Class to setup login customizer output.
 */
class Login_Customizer_Output extends Base_Output {

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
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup login customizer output.
	 */
	public function setup() {

		add_filter( 'login_headertext', array( self::get_instance(), 'login_headertext' ), 20 );
		add_filter( 'login_headerurl', array( self::get_instance(), 'login_logo_url' ), 20 );
		add_action( 'login_header', array( self::get_instance(), 'add_bg_overlay' ) );
		add_action( 'login_head', array( self::get_instance(), 'print_login_styles' ), 20 );
		add_action( 'login_head', array( self::get_instance(), 'print_login_live_styles' ), 30 );

	}

	/**
	 * Set login page header text.
	 *
	 * @param string $text The existing header text.
	 *
	 * @return string The modified header text.
	 */
	public function login_headertext( $text ) {

		$login = get_option( 'udb_login', array() );
		$text  = isset( $login['logo_title'] ) && ! empty( $login['logo_title'] ) ? $login['logo_title'] : $text;

		return $text;

	}

	/**
	 * Change login logo url.
	 *
	 * @param string $url The existing login logo url.
	 *
	 * @return string The modified login logo url.
	 */
	public function login_logo_url( $url ) {

		$login = get_option( 'udb_login', array() );

		if ( isset( $login['logo_url'] ) && ! empty( $login['logo_url'] ) ) {
			$url = str_ireplace( '{home_url}', home_url(), $login['logo_url'] );
		}

		return $url;

	}

	/**
	 * Add background overlay markup.
	 */
	public function add_bg_overlay() {

		echo '<div class="udb-bg-overlay"></div>';

	}

	/**
	 * Print login styles.
	 */
	public function print_login_styles() {

		echo '<style>';
		ob_start();
		require __DIR__ . '/inc/login.css.php';

		$css = ob_get_clean();

		$login      = get_option( 'udb_login', array() );
		$custom_css = isset( $login['custom_css'] ) ? $login['custom_css'] : '';

		$css .= $custom_css;

		$css = apply_filters( 'udb_login_styles', $css );

		$content_helper = new Content_Helper();

		echo $content_helper->sanitize_css( $css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '</style>';

	}

	/**
	 * Print login live styles.
	 */
	public function print_login_live_styles() {

		if ( ! is_customize_preview() ) {
			return;
		}

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[logo_height]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[bg_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[bg_image]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[bg_repeat]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[bg_position]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[bg_size]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[bg_overlay_color]"></style>';

		do_action( 'udb_login_customizer_live_styles' );

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_position]"></style>';

		// This box_width tag is for the pro version but needs to be placed below form_position style tag.
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[box_width]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_bg_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_bg_image]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_bg_repeat]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_bg_position]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_bg_size]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_width]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_top_padding]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_bottom_padding]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_horizontal_padding]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_border_width]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_border_style]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_border_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_border_radius]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[form_shadow]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_height]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_horizontal_padding]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_border_width]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_border_radius]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_font_size]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_text_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_text_color_focus]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_bg_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_bg_color_focus]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_border_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[fields_border_color_focus]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[labels_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[labels_font_size]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[button_height]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[button_horizontal_padding]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[button_text_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[button_text_color_hover]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[button_bg_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[button_bg_color_hover]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[button_border_radius]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[footer_link_color]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[footer_link_color_hover]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[remove_register_lost_pw_link]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[remove_back_to_site_link]"></style>';
		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[remove_lang_switcher]"></style>';

		echo '<style class="udb-login-customizer-live-style" data-listen-value="udb_login[custom_css]"></style>';

	}

}
