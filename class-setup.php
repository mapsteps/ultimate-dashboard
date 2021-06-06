<?php
/**
 * Setup Ultimate Dashboard plugin.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper;

/**
 * Class to setup Ultimate Dashboard plugin.
 */
class Setup {

	/**
	 * The class instanace
	 *
	 * @var object
	 */
	public static $instance = null;

	/**
	 * Get the class instance.
	 *
	 * @return object
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

		$instance = new Setup();
		$instance->setup();

	}

	/**
	 * Get saved/default modules.
	 *
	 * @return array The saved/default modules.
	 */
	public function saved_modules() {

		$defaults = array(
			'white_label'       => 'true',
			'login_customizer'  => 'true',
			'admin_pages'       => 'true',
			'admin_menu_editor' => 'true',
			'admin_bar_editor'  => 'true',
		);

		$saved_modules = get_option( 'udb_modules', $defaults );
		$new_modules   = array_diff_key( $defaults, $saved_modules );

		if ( ! empty( $new_modules ) ) {
			$updated_modules = array_merge( $saved_modules, $new_modules );
			update_option( 'udb_modules', $updated_modules );
		}

		return apply_filters( 'udb_saved_modules', get_option( 'udb_modules', $defaults ) );

	}

	/**
	 * Setup the class.
	 */
	public function setup() {

		add_action( 'plugins_loaded', array( $this, 'load_modules' ), 20 );
		add_action( 'init', array( self::get_instance(), 'check_activation_meta' ) );
		add_action( 'admin_menu', array( $this, 'pro_submenu' ), 20 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 20 );
		add_filter( 'plugin_action_links_' . ULTIMATE_DASHBOARD_PLUGIN_FILE, array( $this, 'action_links' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
		add_action( 'admin_notices', array( self::get_instance(), 'review_notice' ) );
		add_action( 'wp_ajax_udb_dismiss_review_notice', array( $this, 'dismiss_review_notice' ) );

		register_deactivation_hook( ULTIMATE_DASHBOARD_PLUGIN_FILE, array( $this, 'deactivation' ), 20 );

		$content_helper = new Content_Helper();
		add_filter( 'wp_kses_allowed_html', array( $content_helper, 'allow_iframes_in_html' ) );

	}

	/**
	 * Check plugin activation meta.
	 */
	public function check_activation_meta() {

		if ( ! current_user_can( 'activate_plugins' ) || get_option( 'udb_plugin_activated' ) ) {
			return;
		}

		update_option( 'udb_install_date', current_time( 'mysql' ) );
		update_option( 'udb_plugin_activated', 1 );

	}

	/**
	 * Admin body class.
	 *
	 * @param string $classes The class names.
	 */
	public function admin_body_class( $classes ) {

		$current_user = wp_get_current_user();
		$classes     .= ' udb-user-' . $current_user->user_nicename;

		$roles = $current_user->roles;
		$roles = $roles ? $roles : array();

		foreach ( $roles as $role ) {
			$classes .= ' udb-role-' . $role;
		}

		$screens = array(
			'udb_widgets_page_udb_features',
			'udb_widgets_page_udb-license',
			'udb_widgets_page_udb_tools',
			'udb_widgets_page_udb_branding',
			'udb_widgets_page_udb_settings',
			'udb_widgets_page_udb_admin_menu',
			'udb_widgets_page_udb_admin_bar',
		);

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, $screens ) ) {
			return $classes;
		}

		$classes .= ' heatbox-admin has-header';

		return $classes;

	}

	/**
	 * Add action links displayed in plugins page.
	 *
	 * @param array $links The action links array.
	 * @return array The modified action links array.
	 */
	public function action_links( $links ) {

		$settings = array( '<a href="' . admin_url( 'edit.php?post_type=udb_widgets&page=settings' ) . '">' . __( 'Settings', 'ultimate-dashboard' ) . '</a>' );

		return array_merge( $links, $settings );

	}

	/**
	 * Load Ultimate Dashboard modules.
	 */
	public function load_modules() {

		$modules['Udb\\Widget\\Widget_Module'] = __DIR__ . '/modules/widget/class-widget-module.php';

		if ( ! defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' ) || ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION >= '3.1' ) {
			$modules['Udb\\Feature\\Feature_Module'] = __DIR__ . '/modules/feature/class-feature-module.php';
		}

		$modules['Udb\\Setting\\Setting_Module'] = __DIR__ . '/modules/setting/class-setting-module.php';

		$saved_modules = $this->saved_modules();

		if ( 'true' === $saved_modules['white_label'] ) {
			$modules['Udb\\Branding\\Branding_Module'] = __DIR__ . '/modules/branding/class-branding-module.php';
		}

		if ( 'true' === $saved_modules['admin_pages'] ) {
			$modules['Udb\\AdminPage\\Admin_Page_Module'] = __DIR__ . '/modules/admin-page/class-admin-page-module.php';
		}

		if ( 'true' === $saved_modules['login_customizer'] ) {
			$modules['Udb\\LoginCustomizer\\Login_Customizer_Module'] = __DIR__ . '/modules/login-customizer/class-login-customizer-module.php';
		}

		if ( 'true' === $saved_modules['admin_menu_editor'] ) {
			$modules['Udb\\AdminMenu\\Admin_Menu_Module'] = __DIR__ . '/modules/admin-menu/class-admin-menu-module.php';
		}

		if ( 'true' === $saved_modules['admin_bar_editor'] ) {
			$modules['Udb\\AdminBar\\Admin_Bar_Module'] = __DIR__ . '/modules/admin-bar/class-admin-bar-module.php';
		}

		$modules['Udb\\Tool\\Tool_Module'] = __DIR__ . '/modules/tool/class-tool-module.php';

		$modules = apply_filters( 'udb_modules', $modules );

		foreach ( $modules as $class => $file ) {
			$splits      = explode( '/', $file );
			$module_name = $splits[ count( $splits ) - 2 ];
			$filter_name = str_ireplace( '-', '_', $module_name );
			$filter_name = 'udb_' . $filter_name;

			// We have a filter here udb_$module_name to allow us to prevent loading modules under certain circumstances.
			// Not sure if currently in use.
			if ( apply_filters( $filter_name, true ) ) {

				require_once $file;
				$module = new $class();
				$module->setup();

			}
		}

	}

	/**
	 * Generate PRO submenu link.
	 */
	public function pro_submenu() {

		// Stop if PRO version is active.
		if ( udb_is_pro_active() ) {
			return;
		}

		// Stop if user isn't an admin.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $submenu;

		$submenu['edit.php?post_type=udb_widgets'][] = array( 'PRO', 'manage_options', 'https://ultimatedashboard.io/pro/' );

	}

	/**
	 * Enqueue admin styles.
	 */
	public function admin_styles() {

		wp_enqueue_style( 'udb-admin', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/css/admin.css', array(), ULTIMATE_DASHBOARD_PLUGIN_VERSION );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_scripts() {

		wp_enqueue_script( 'udb-notice-dismissal', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/notice-dismissal.js', array( 'jquery' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );

	}

	/**
	 * Show review notice after certain number of day(s).
	 */
	public function review_notice() {

		// Stop if review notice had been dismissed.
		if ( get_option( 'review_notice_dismissed' ) ) {
			return;
		}

		$install_date = get_option( 'udb_install_date' );

		// Stop if there's no install date.
		if ( empty( $install_date ) ) {
			return;
		}

		$diff = round( ( time() - strtotime( $install_date ) ) / 24 / 60 / 60 );

		// Don't show the notice if Ultimate Dashboard is running not more than 5 days.
		if ( $diff < 5 ) {
			return;
		}

		$emoji      = '😍';
		$review_url = 'https://wordpress.org/support/plugin/ultimate-dashboard/reviews/?rate=5#new-post';
		$link_start = '<a href="' . $review_url . '" target="_blank">';
		$link_end   = '</a>';
		// translators: %1$s: Emoji, %2$s: Link start tag, %3$s: Link end tag.
		$notice   = sprintf( __( '%1$s Love using Ultimate Dashboard? - That\'s Awesome! Help us spread the word and leave us a %2$s 5-star review %3$s in the WordPress repository.', 'ultimate-dashboard' ), $emoji, $link_start, $link_end );
		$btn_text = __( 'Sure! You deserve it!', 'ultimate-dashboard' );
		$notice  .= '<br/>';
		$notice  .= "<a href=\"$review_url\" style=\"margin-top: 15px;\" target='_blank' class=\"button-primary\">$btn_text</a>";

		echo '<div class="notice udb-notice review-notice notice-success is-dismissible is-permanent-dismissible" data-ajax-action="udb_dismiss_review_notice">';
		echo '<p>' . $notice . '</p>';
		echo '</div>';

	}

	/**
	 * Dismiss review notice.
	 */
	public function dismiss_review_notice() {

		$dismiss = isset( $_POST['dismiss'] ) ? absint( $_POST['dismiss'] ) : 0;

		if ( empty( $dismiss ) ) {
			wp_send_json_error( __( 'Invalid Request', 'ultimate-dashboard' ) );
		}

		update_option( 'review_notice_dismissed', 1 );
		wp_send_json_success( __( 'Review notice has been dismissed', 'ultimate-dashboard' ) );

	}

	/**
	 * Plugin deactivation.
	 */
	public function deactivation() {

		$settings = get_option( 'udb_settings' );

		$remove_on_uninstall = isset( $settings['remove-on-uninstall'] ) ? true : false;
		$remove_on_uninstall = apply_filters( 'udb_clean_uninstall', $remove_on_uninstall );

		if ( $remove_on_uninstall ) {

			delete_option( 'udb_settings' );
			delete_option( 'udb_branding' );
			delete_option( 'udb_login' );
			delete_option( 'udb_import' );
			delete_option( 'udb_modules' );

			delete_option( 'udb_compat_widget_type' );
			delete_option( 'udb_compat_widget_status' );
			delete_option( 'udb_compat_delete_login_customizer_page' );
			delete_option( 'udb_compat_settings_meta' );
			delete_option( 'udb_compat_old_option' );

			delete_option( 'udb_login_customizer_flush_url' );
			delete_option( 'review_notice_dismissed' );

			delete_option( 'udb_install_date' );
			delete_option( 'udb_plugin_activated' );

		}

	}

}
