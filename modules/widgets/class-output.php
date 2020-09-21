<?php
/**
 * Branding output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Widgets;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Base\Output as Base_Output;
use WP_Query;

/**
 * Class to setup widgets output.
 */
class Output extends Base_Output {

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
	 * @return object
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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/widgets';

	}

	/**
	 * Setup widgets output.
	 */
	public function setup() {

		add_action( 'wp_dashboard_setup', array( $this, 'dashboard_widgets' ) );
		add_action( 'wp_dashboard_setup', array( self::get_instance(), 'remove_default_dashboard_widgets' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'dashboard_styles' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'dashboard_custom_css' ), 200 );
		add_action( 'admin_head', array( $this, 'admin_custom_css' ), 200 );
		add_action( 'admin_head', array( $this, 'change_dashboard_headline' ) );
		add_action( 'admin_head', array( $this, 'remove_help_tab' ) );
		add_filter( 'screen_options_show_screen', array( $this, 'remove_screen_options_tab' ) );
		add_action( 'init', array( $this, 'remove_admin_bar' ) );
		add_action( 'init', array( $this, 'remove_font_awesome' ) );

	}

	/**
	 * Setup dashboard widgets.
	 */
	public function dashboard_widgets() {

		// Loop through udb_widgets CPT to display widgets on the WordPress dashboard.
		$args = array(
			'post_type'      => 'udb_widgets',
			'posts_per_page' => 100,
			'meta_key'       => 'udb_is_active',
			'meta_value'     => 1,
		);

		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) :

			$loop->the_post();

			$id          = get_the_ID();
			$title       = get_the_title();
			$icon        = get_post_meta( $id, 'udb_icon_key', true );
			$link        = get_post_meta( $id, 'udb_link', true );
			$target      = get_post_meta( $id, 'udb_link_target', true );
			$tooltip     = get_post_meta( $id, 'udb_tooltip', true );
			$position    = get_post_meta( $id, 'udb_position_key', true );
			$priority    = get_post_meta( $id, 'udb_priority_key', true );
			$widget_type = get_post_meta( $id, 'udb_widget_type', true );
			$output      = '';

			// Preventing edge case when widget_type is empty.
			if ( ! $widget_type ) {

				do_action( 'udb_compat_widget_type', $id );

			}

			if ( 'html' === $widget_type ) {

				$html   = get_post_meta( $id, 'udb_html', true );
				$output = do_shortcode( '<div class="udb-html-wrapper">' . $html . '</div>' );

			} elseif ( 'text' === $widget_type ) { // Text widget output.

				$content       = get_post_meta( $id, 'udb_content', true );
				$contentheight = get_post_meta( $id, 'udb_content_height', true ) ? ' data-udb-content-height="' . get_post_meta( $id, 'udb_content_height', true ) . '"' : '';

				$output = do_shortcode( '<div class="udb-content-wrapper"' . $contentheight . '>' . wpautop( $content ) . '</div>' );

			} elseif ( 'icon' === $widget_type ) { // Icon widget output.

				$output = '<a href="' . $link . '" target="' . $target . '"><i class="' . $icon . '"></i></a>';

				// Tooltip.
				if ( $tooltip ) {
					$output .= '<i class="udb-info"></i><div class="udb-tooltip"><span>' . $tooltip . '</span></div>';
				}
			}

			$output_args = array(
				'id'          => $id,
				'title'       => $title,
				'position'    => $position,
				'priority'    => $priority,
				'widget_type' => $widget_type,
			);

			$output = apply_filters( 'udb_widget_output', $output, $output_args );

			// Output.
			$function = function() use ( $output ) {
				echo $output;
			};

			// Add metabox.
			add_meta_box( 'ms-udb' . $id, $title, $function, 'dashboard', $position, $priority );

		endwhile;

	}

	/**
	 * Remove default WordPress dashboard widgets.
	 */
	public function remove_default_dashboard_widgets() {

		$saved_widgets   = $this->widget()->get_saved_default();
		$default_widgets = $this->widget()->get_default();
		$settings        = get_option( 'udb_settings' );

		if ( isset( $settings['remove-all'] ) ) {

			remove_action( 'welcome_panel', 'wp_welcome_panel' );

			foreach ( $default_widgets as $id => $widget ) {
				remove_meta_box( $id, 'dashboard', $widget['context'] );
			}
		} else {

			if ( isset( $settings['welcome_panel'] ) ) {
				remove_action( 'welcome_panel', 'wp_welcome_panel' );
			}

			foreach ( $saved_widgets as $id => $widget ) {
				remove_meta_box( $id, 'dashboard', $widget['context'] );
			}
		}

	}

	/**
	 * Add dashboard styles.
	 */
	public function dashboard_styles() {

		$css = '';

		ob_start();
		require ULTIMATE_DASHBOARD_PLUGIN_DIR . '/assets/css/widget-styles.css.php';
		$css = ob_get_clean();

		wp_add_inline_style( 'udb-dashboard', $css );

	}

	/**
	 * Add dashboard custom CSS.
	 */
	public function dashboard_custom_css() {

		$settings = get_option( 'udb_settings' );

		if ( ! isset( $settings['custom_css'] ) || empty( $settings['custom_css'] ) ) {
			return;
		}

		wp_add_inline_style( 'udb-dashboard', $settings['custom_css'] );

	}

	/**
	 * Add admin custom CSS.
	 */
	public function admin_custom_css() {

		$settings = get_option( 'udb_settings' );

		if ( ! isset( $settings['custom_admin_css'] ) || empty( $settings['custom_admin_css'] ) ) {
			return;
		}
		?>

		<style>
			<?php echo $settings['custom_admin_css']; ?>
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

		if ( ! isset( $settings['dashboard_headline'] ) || empty( $settings['dashboard_headline'] ) ) {
			return;
		}

		$GLOBALS['title'] = $settings['dashboard_headline'];

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

		return ( isset( $settings['remove_screen_options'] ) ? false : true );
	}

	/**
	 * Remove admin bar from frontend.
	 *
	 * @return void
	 */
	public function remove_admin_bar() {
		$settings = get_option( 'udb_settings' );

		if ( isset( $settings['remove_admin_bar'] ) ) {
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
