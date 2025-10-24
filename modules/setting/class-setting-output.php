<?php
/**
 * Settings output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Setting;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use WP_Admin_Bar;
use Udb\Base\Base_Output;
use Udb\Helpers\Content_Helper;
use Udb\Widget\Widget_Output;
use Udb\Helpers\Admin_Bar_Helper;

/**
 * Class to set up setting output.
 */
class Setting_Output extends Base_Output {

	/**
	 * The class instance.
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * The current module url.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * Get instance of the class.
	 *
	 * @return self
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**
	 * Module constructor.
	 */
	public function __construct() {

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/setting';

	}

	/**
	 * Init the class setup.
	 */
	public static function init() {

		$class = new self();
		$class->setup();

	}

	/**
	 * Setup widgets output.
	 */
	public function setup() {

		add_action( 'admin_init', array( self::get_instance(), 'custom_welcome_panel' ) );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'dashboard_custom_css' ), 200 );
		add_action( 'admin_head', array( self::get_instance(), 'admin_custom_css' ), 200 );
		add_action( 'admin_head', array( self::get_instance(), 'change_dashboard_headline' ) );
		add_action( 'admin_bar_menu', array( self::get_instance(), 'change_howdy_text' ), 10000 );
		add_action( 'admin_head', array( self::get_instance(), 'remove_help_tab' ) );
		add_filter( 'screen_options_show_screen', array( self::get_instance(), 'remove_screen_options_tab' ) );
		add_action( 'init', array( self::get_instance(), 'remove_admin_bar' ) );
		add_action( 'admin_init', array( self::get_instance(), 'remove_font_awesome' ) );

	}

	/**
	 * Add dashboard custom CSS.
	 */
	public function dashboard_custom_css() {

		$settings = get_option( 'udb_settings' );

		if ( empty( $settings['custom_css'] ) ) {
			return;
		}

		$content_helper = new Content_Helper();

		$custom_css = $settings['custom_css'];
		$custom_css = $content_helper->sanitize_css( $custom_css );

		wp_add_inline_style( 'udb-dashboard', esc_html( $custom_css ) );

	}

	/**
	 * Add admin custom CSS.
	 */
	public function admin_custom_css() {

		$settings = get_option( 'udb_settings' );

		if ( empty( $settings['custom_admin_css'] ) ) {
			return;
		}

		$content_helper = new Content_Helper();

		$custom_css = $settings['custom_admin_css'];
		?>

		<style>
			<?php echo $content_helper->sanitize_css( $custom_css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</style>

		<?php

	}

	/**
	 * Change Dashboard's headline.
	 */
	public function change_dashboard_headline() {

		if ( isset( $GLOBALS['title'] ) && 'Dashboard' !== $GLOBALS['title'] ) {
			return;
		}

		$settings = get_option( 'udb_settings' );

		if ( empty( $settings['dashboard_headline'] ) ) {
			return;
		}

		$GLOBALS['title'] = $settings['dashboard_headline'];

	}

	/**
	 * Change Howdy text.
	 *
	 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance.
	 */
	public function change_howdy_text( $wp_admin_bar ) {

		$settings = get_option( 'udb_settings' );

		if ( empty( $settings['howdy_text'] ) ) {
			return;
		}

		$my_account = $wp_admin_bar->get_node( 'my-account' );

		if ( is_null( $my_account ) || ! is_object( $my_account ) || ! property_exists( $my_account, 'title' ) ) {
			return;
		}

		$my_account->title = str_ireplace( 'Howdy', esc_html( $settings['howdy_text'] ), $my_account->title );

		$wp_admin_bar->remove_node( 'my-account' );

		$wp_admin_bar->add_node( $my_account );

	}

	/**
	 * Remove help tab on admin area.
	 */
	public function remove_help_tab() {

		$current_screen = get_current_screen();

		$settings = get_option( 'udb_settings' );

		if ( ! isset( $settings['remove_help_tab'] ) ) {
			return;
		}

		if ( $current_screen ) {
			$current_screen->remove_help_tabs();
		}

	}

	/**
	 * Remove screen options on admin area.
	 */
	public function remove_screen_options_tab() {

		$settings = get_option( 'udb_settings' );

		return ! isset( $settings['remove_screen_options'] );

	}

	/**
	 * Check if we have custom welcome panel.
	 */
	public function custom_welcome_panel() {

		$settings = get_option( 'udb_settings' );

		// Stop if remove-all widget is checked or if remove welcome_panel is checked.
		if ( isset( $settings['remove-all'] ) || isset( $settings['welcome_panel'] ) ) {
			return;
		}

		if ( empty( $settings['welcome_panel_content'] ) ) {
			return;
		}

		remove_action( 'welcome_panel', 'wp_welcome_panel' );
		add_action( 'welcome_panel', array( self::get_instance(), 'welcome_panel_content' ) );

	}

	/**
	 * Output welcome panel content.
	 */
	public function welcome_panel_content() {

		$settings = get_option( 'udb_settings' );
		$content  = empty( $settings['welcome_panel_content'] ) ? '' : $settings['welcome_panel_content'];

		if ( empty( $content ) ) {
			do_action( 'udb_ms_switch_blog' );

			$settings = get_option( 'udb_settings' );
			// This would never be empty because we already did cheking on `custom_welcome_panel` function above from multisite.
			$content = $settings['welcome_panel_content'];

			do_action( 'udb_ms_restore_blog' );
		}

		$widget_output = Widget_Output::get_instance();

		$content = $widget_output->convert_placeholder_tags( $content );
		$content = do_shortcode( $content );
		$content = wpautop( $content );

		echo wp_kses_post( $content );

	}

	/**
	 * Remove admin bar from frontend.
	 *
	 * @param string[] $roles The roles to remove the admin bar for.
	 */
	public function remove_admin_bar( $roles = array() ) {

		$admin_bar_helper = new Admin_Bar_Helper();

		// Check if the admin bar should be removed.
		if ( $admin_bar_helper->should_remove_admin_bar( $roles ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}

	}

	/**
	 * Remove Font Awesome.
	 */
	public function remove_font_awesome() {

		$settings = get_option( 'udb_settings' );

		if ( isset( $settings['remove_font_awesome'] ) ) {
			add_filter( 'udb_font_awesome', '__return_false' );
		}

	}

}
