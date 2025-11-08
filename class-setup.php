<?php
/**
 * Setup Ultimate Dashboard plugin.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Vars;
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
			'login_redirect'    => 'true',
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

		/**
		 * We use 20 as the priority in the free version
		 * because the PRO version has to run first.
		 *
		 * The PRO version has to run before the free version
		 * because the PRO version hooks some action/filter to the free version.
		 * So in order to make them executed, the PRO version has to run first.
		 */

		register_activation_hook( ULTIMATE_DASHBOARD_PLUGIN_FILE, array( $this, 'on_plugin_activation' ), 20 );

		add_action( 'plugins_loaded', array( $this, 'load_modules' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'load_plugin_onboarding_module' ), 20 );
		add_action( 'plugins_loaded', array( $this, 'load_onboarding_wizard_module' ), 20 );
		add_action( 'init', array( self::get_instance(), 'check_activation_meta' ) );
		add_action( 'init', array( $this, 'register_action_links' ) );
		add_action( 'admin_menu', array( $this, 'pro_submenu' ), 20 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 20 );
		add_action( 'admin_notices', array( self::get_instance(), 'review_notice' ) );
		add_action( 'admin_notices', array( self::get_instance(), 'bfcm_notice' ) );
		add_action( 'wp_ajax_udb_dismiss_review_notice', array( $this, 'dismiss_review_notice' ) );
		add_action( 'wp_ajax_udb_dismiss_bfcm_notice', array( $this, 'dismiss_bfcm_notice' ) );

		register_deactivation_hook( ULTIMATE_DASHBOARD_PLUGIN_FILE, array( $this, 'deactivation' ), 20 );

		$content_helper = new Content_Helper();
		add_filter( 'wp_kses_allowed_html', array( $content_helper, 'allow_iframes_in_html' ) );

	}

	/**
	 * Preload UDB settings.
	 * This will reduce the repeated call of get_option across modules.
	 *
	 * @deprecated 3.7.15 Not good for performance. This method was called both in admin & front area. The benefits were not much, because there was only few places where the preloaded data was re-used.
	 */
	public function set_data() {}

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
			'udb_widgets_page_udb_login_redirect',
			'udb_widgets_page_udb_admin_menu',
			'udb_widgets_page_udb_admin_bar',
			'udb_widgets_page_udb_plugin_onboarding',
			'udb_widgets_page_udb_onboarding_wizard',
		);

		$screen = get_current_screen();

		if ( ! in_array( $screen->id, $screens, true ) ) {
			return $classes;
		}

		$classes .= ' heatbox-admin has-header';

		return $classes;

	}

	/**
	 * Register plugin action links filter after init hook.
	 * This ensures translations are loaded before the callback is executed.
	 */
	public function register_action_links() {
		add_filter( 'plugin_action_links_' . ULTIMATE_DASHBOARD_PLUGIN_FILE, array( $this, 'action_links' ) );
	}

	/**
	 * Add action links displayed in plugins page.
	 *
	 * @param array $links The action links array.
	 * @return array The modified action links array.
	 */
	public function action_links( $links ) {

		$settings = array( '<a href="' . admin_url( 'edit.php?post_type=udb_widgets&page=udb_settings' ) . '">' . __( 'Settings', 'ultimate-dashboard' ) . '</a>' );

		return array_merge( $links, $settings );

	}

	/**
	 * Store an option that tracks the plugin activation.
	 */
	public function on_plugin_activation() {

		// Stop if this is activation from Erident's migration to UDB.
		if ( get_option( 'udb_migration_from_erident' ) ) {
			// Prevent "Setup Wizard" from being shown for Erident's users.
			update_option( 'udb_onboarding_wizard_completed', 1 );
			return;
		}

		// We bail out early in multisite because this function will still be called in the main site.
		if ( is_multisite() || udb_is_pro_active() ) {
			return;
		}

		update_option( 'udb_onboarding_wizard_redirect', 1 );

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

		if ( isset( $saved_modules['white_label'] ) && 'true' === $saved_modules['white_label'] ) {
			$modules['Udb\\Branding\\Branding_Module'] = __DIR__ . '/modules/branding/class-branding-module.php';
		}

		if ( isset( $saved_modules['admin_pages'] ) && 'true' === $saved_modules['admin_pages'] ) {
			$modules['Udb\\AdminPage\\Admin_Page_Module'] = __DIR__ . '/modules/admin-page/class-admin-page-module.php';
		}

		if ( isset( $saved_modules['login_customizer'] ) && 'true' === $saved_modules['login_customizer'] ) {
			$modules['Udb\\LoginCustomizer\\Login_Customizer_Module'] = __DIR__ . '/modules/login-customizer/class-login-customizer-module.php';
		}

		if ( isset( $saved_modules['login_redirect'] ) && 'true' === $saved_modules['login_redirect'] ) {
			$modules['Udb\\LoginRedirect\\Login_Redirect_Module'] = __DIR__ . '/modules/login-redirect/class-login-redirect-module.php';
		}

		if ( isset( $saved_modules['admin_menu_editor'] ) && 'true' === $saved_modules['admin_menu_editor'] ) {
			$modules['Udb\\AdminMenu\\Admin_Menu_Module'] = __DIR__ . '/modules/admin-menu/class-admin-menu-module.php';
		}

		if ( isset( $saved_modules['admin_bar_editor'] ) && 'true' === $saved_modules['admin_bar_editor'] ) {
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
	 * Load plugin onboarding module.
	 */
	public function load_plugin_onboarding_module() {

		$need_setup = false;
		$referrer   = '';

		// Erident's migration takes the highest priority.
		if ( get_option( 'udb_migration_from_erident' ) ) {
			$need_setup = true;
			$referrer   = 'erident';
		} elseif ( get_option( 'udb_referred_by_kirki' ) ) {
			$need_setup = true;
			$referrer   = 'kirki';
		}
		// In the future, we might allow UDB to be installed from other plugins as well.

		if ( ! $need_setup ) {
			return;
		}

		require_once __DIR__ . '/modules/plugin-onboarding/class-plugin-onboarding-module.php';
		$module = new PluginOnboarding\Plugin_Onboarding_Module();
		$module->setup( $referrer );

	}

	/**
	 * Load onboarding wizard module.
	 */
	public function load_onboarding_wizard_module() {

		if ( is_multisite() || udb_is_pro_active() ) {
			return;
		}

		if ( get_option( 'udb_onboarding_wizard_completed' ) ) {
			return;
		}

		if ( get_option( 'udb_onboarding_wizard_redirect' ) ) {
			// Redirect to onboarding wizard page.
			add_action( 'current_screen', array( $this, 'redirect_to_onboarding_wizard_page' ), 20 );
		}

		require_once __DIR__ . '/modules/onboarding-wizard/class-onboarding-wizard-module.php';
		$module = new OnboardingWizard\Onboarding_Wizard_Module();
		$module->setup();

	}

	/**
	 * Redirect to the Onboarding Wizard page after activate the plugin.
	 */
	public function redirect_to_onboarding_wizard_page() {

		$current_screen = get_current_screen();

		if ( is_null( $current_screen ) ) {
			return;
		}

		// Stop if current screen is onboarding wizard page.
		if ( 'udb_widgets_page_udb_onboarding_wizard' === $current_screen->id ) {
			return;
		}

		// Stop if this request is not supposed to be redirected.
		if ( ! get_option( 'udb_onboarding_wizard_redirect' ) ) {
			return;
		}

		// Immediately delete the redirect option because redirect is supposed to happen once.
		delete_option( 'udb_onboarding_wizard_redirect' );

		// Redirect to the Onboarding Wizard page.
		wp_safe_redirect( admin_url( 'edit.php?post_type=udb_widgets&page=udb_onboarding_wizard' ) );
		exit;

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

		$submenu['edit.php?post_type=udb_widgets'][] = array( 'Upgrade to PRO', 'manage_options', 'https://ultimatedashboard.io/pro/' );

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

		// Stop if user isn't an admin.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

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

		echo '<div class="notice udb-notice udb-review-notice notice-success is-dismissible is-permanent-dismissible" data-ajax-action="udb_dismiss_review_notice">';
		echo '<p>' . wp_kses_post( $notice ) . '</p>';
		echo '</div>';

	}

	/**
	 * Dismiss review notice.
	 */
	public function dismiss_review_notice() {

		if ( empty( $_POST['dismiss'] ) ) {
			wp_send_json_error( 'Invalid Request' );
		}

		update_option( 'review_notice_dismissed', 1 );
		wp_send_json_success( 'Review notice has been dismissed.' );

	}

	/**
	 * Show BFCM notice.
	 */
	public function bfcm_notice() {

		// Stop if PRO version is active.
		if ( udb_is_pro_active() ) {
			return;
		}

		// Stop here if we are not on the main site of the network.
		if ( ! is_main_site() ) {
			return;
		}

		// Stop here if current user is not an admin.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Intentional: using manually written string instead of gmdate( 'Y' ).
		$this_year = '2025';
		$last_year = $this_year - 1;
		$start     = strtotime( 'november 24th, ' . $this_year );
		$end       = strtotime( 'december 1st, ' . $this_year );
		$now       = time();

		// Stop here if we are not in the sales period.
		if ( $now < $start || $now > $end ) {
			return;
		}

		// Clean up: Delete initial deal dismissal if triggered.
		if ( ! empty( get_option( 'udb_bfcm_notice_dismissed', 0 ) ) ) {
			delete_option( 'udb_bfcm_notice_dismissed' );
		}

		// Clean up: Delete last years dismissal if triggered.
		if ( ! empty( get_option( 'udb_bfcm_notice_dismissed_' . $last_year, 0 ) ) ) {
			delete_option( 'udb_bfcm_notice_dismissed_' . $last_year );
		}

		// Stop here if notice has been dismissed.
		if ( ! empty( get_option( 'udb_bfcm_notice_dismissed_' . $this_year, 0 ) ) ) {
			return;
		}

		$bfcm_url = 'https://ultimatedashboard.io/pricing/?utm_source=repository&utm_medium=bfcm_banner&utm_campaign=udb';
		?>

		<div class="notice udb-notice udb-bfcm-notice notice-info is-dismissible is-permanent-dismissible" data-ajax-action="udb_dismiss_bfcm_notice">
			<div class="notice-body">
				<div class="notice-icon">
					<img src="<?php echo esc_url( ULTIMATE_DASHBOARD_PLUGIN_URL ); ?>/assets/img/logo.png" alt="Ultimate Dashboard Logo">
				</div>
				<div class="notice-content">
					<h2>
						<?php esc_html_e( 'Black Friday Sale! - Up to 25% Off Ultimate Dashboard PRO', 'ultimate-dashboard' ); ?>
					</h2>
					<p>
						<?php echo wp_kses_post( __( 'Save big & upgrade to <strong>Ultimate Dashboard PRO</strong>, today!', 'ultimate-dashboard' ) ); ?>
					</p>
					<p>
						<?php esc_html_e( 'But hurry up, the deal will expire soon!', 'ultimate-dashboard' ); ?><br>
						<em><?php esc_html_e( 'All prices are reduced. No coupon code required.', 'ultimate-dashboard' ); ?></em>
					</p>
					<p>
						<a target="_blank" href="<?php echo esc_url( $bfcm_url ); ?>" class="button button-primary">
							<?php esc_html_e( 'Learn more', 'ultimate-dashboard' ); ?>
						</a>
						<small><?php esc_html_e( '*Only Administrators will see this message.', 'ultimate-dashboard' ); ?></small>
					</p>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Dismiss BFCM notice.
	 */
	public function dismiss_bfcm_notice() {

		if ( empty( $_POST['dismiss'] ) ) {
			wp_send_json_error( 'Invalid Request' );
		}

		update_option( 'udb_bfcm_notice_dismissed_2025', 1 );
		wp_send_json_success( 'Review notice has been dismissed.' );

	}

	/**
	 * Plugin deactivation.
	 */
	public function deactivation() {

		if ( $this->multisite_supported() ) {
			$blueprint = get_site_option( 'udb_multisite_blueprint' );
			$blueprint = $blueprint ? (int) $blueprint : 0;

			$site_ids = get_sites(
				array(
					'fields' => 'ids',
				)
			);

			if ( $blueprint ) {
				// When blueprint is set, we get the data removal option from blueprint only.
				$settings    = get_blog_option( $blueprint, 'udb_settings', array() );
				$remove_data = isset( $settings['remove-on-uninstall'] ) ? true : false;

				if ( $remove_data ) {
					foreach ( $site_ids as $site_id ) {
						if ( $site_id !== $blueprint ) {
							// Don't restore the data removal option if $site_id is not the blueprint.
							$this->delete_udb_data( $site_id, false );
						} else {
							$this->delete_udb_data( $site_id );
						}
					}
				}
			} else {
				// When blueprint is not set, we check the data removal option per-site id.
				foreach ( $site_ids as $site_id ) {
					$settings    = get_blog_option( $site_id, 'udb_settings', array() );
					$remove_data = isset( $settings['remove-on-uninstall'] ) ? true : false;

					if ( $remove_data ) {
						$this->delete_udb_data( $site_id );
					}
				}
			}
		} else {
			$settings    = get_option( 'udb_settings' );
			$remove_data = isset( $settings['remove-on-uninstall'] ) ? true : false;

			if ( $remove_data ) {
				$this->delete_udb_data( null );
			}
		}

	}

	/**
	 * Delete free-related options on plugin deactivation.
	 *
	 * We still have `udb_multisite_blueprint` option and
	 * it won't be deleted on both free & pro versions deactivation.
	 * So that it wouldn't be a problem if user deactivate free version first or pro version first.
	 * Both versions will be able to get the blueprint value.
	 *
	 * So yea, `udb_multisite_blueprint` will stays in the database.
	 *
	 * @param int|null $site_id The site id or null.
	 * @param bool     $restore_removal_option Whether to restore the data removal option or not.
	 *                                         This is used to handle the case when the free version is de-activated first,
	 *                                         then the pro version is de-activated later.
	 */
	public function delete_udb_data( $site_id, $restore_removal_option = true ) {

		if ( $site_id ) {
			delete_blog_option( $site_id, 'udb_settings' );
			delete_blog_option( $site_id, 'udb_branding' );
			delete_blog_option( $site_id, 'udb_login' );
			delete_blog_option( $site_id, 'udb_login_redirect' );
			delete_blog_option( $site_id, 'udb_import' );
			delete_blog_option( $site_id, 'udb_modules' );
			delete_blog_option( $site_id, 'udb_recent_admin_menu' );

			delete_blog_option( $site_id, 'udb_compat_widget_type' );
			delete_blog_option( $site_id, 'udb_compat_widget_status' );
			delete_blog_option( $site_id, 'udb_compat_delete_login_customizer_page' );
			delete_blog_option( $site_id, 'udb_compat_settings_meta' );
			delete_blog_option( $site_id, 'udb_compat_old_option' );

			delete_blog_option( $site_id, 'udb_migration_from_erident' );
			delete_blog_option( $site_id, 'udb_referred_by_kirki' );

			delete_blog_option( $site_id, 'udb_login_customizer_flush_url' );
			delete_blog_option( $site_id, 'review_notice_dismissed' );

			/**
			 * Backwards compatibility
			 * We will no longer have to remove related data on multisites as from 2022 on we will only show the bfcm notice on the main site.
			 */
			delete_blog_option( $site_id, 'udb_bfcm_notice_dismissed' );

			delete_blog_option( $site_id, 'udb_install_date' );
			delete_blog_option( $site_id, 'udb_plugin_activated' );

			if ( $restore_removal_option && defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' ) ) {
				update_blog_option( $site_id, 'udb_settings', array( 'remove-on-uninstall' => 1 ) );
			}
		} else {
			delete_option( 'udb_settings' );
			delete_option( 'udb_branding' );
			delete_option( 'udb_login' );
			delete_option( 'udb_login_redirect' );
			delete_option( 'udb_import' );
			delete_option( 'udb_modules' );
			delete_option( 'udb_recent_admin_menu' );

			delete_option( 'udb_compat_widget_type' );
			delete_option( 'udb_compat_widget_status' );
			delete_option( 'udb_compat_delete_login_customizer_page' );
			delete_option( 'udb_compat_settings_meta' );
			delete_option( 'udb_compat_old_option' );

			delete_option( 'udb_migration_from_erident' );
			delete_option( 'udb_referred_by_kirki' );

			delete_option( 'udb_login_customizer_flush_url' );
			delete_option( 'review_notice_dismissed' );

			delete_option( 'udb_install_date' );
			delete_option( 'udb_plugin_activated' );

			// These 2 options won't be available in multisite install.
			delete_option( 'udb_onboarding_wizard_redirect' );
			delete_option( 'udb_onboarding_wizard_completed' );

			if ( $restore_removal_option && defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' ) ) {
				update_option( $site_id, 'udb_settings', array( 'remove-on-uninstall' => 1 ) );
			}
		}

	}

	/**
	 * Check whether plugin is active on multisite or not.
	 *
	 * @return bool
	 */
	private function is_network_active() {
		// Load plugin.php if it doesn't already exist.
		if ( ! function_exists( 'is_plugin_active_for_network' ) || ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		return ( is_plugin_active_for_network( 'ultimate-dashboard/ultimate-dashboard.php' ) ? true : false );
	}

	/**
	 * Check whether multisite actions are supported or not.
	 *
	 * But, we don't check for `udb_pro_ms_support` filter here.
	 * That filter belongs to the pro version.
	 *
	 * @return bool
	 */
	private function multisite_supported() {
		return ( $this->is_network_active() ? true : false );
	}

}
