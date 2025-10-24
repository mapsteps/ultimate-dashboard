<?php
/**
 * Branding output.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Widget;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use WP_Query;
use Udb\Base\Base_Output;

/**
 * Class to setup widgets output.
 */
class Widget_Output extends Base_Output {

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
	 * The default placeholder tags.
	 *
	 * @var array
	 */
	public $placeholder_tags;

	/**
	 * The default placeholder tag values.
	 *
	 * @var array
	 */
	public $placeholder_values;

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

		$this->url = ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/widget';

		$this->placeholder_tags = [
			'{first_name}',
			'{last_name}',
			'{display_name}',
			'{user_email}',

			'{admin_email}',
			'{site_name}',
			'{site_url}',
		];

		$current_user = wp_get_current_user();

		$this->placeholder_values = [
			$current_user->first_name,
			$current_user->last_name,
			$current_user->display_name,
			$current_user->user_email,

			get_option( 'admin_email' ),
			get_bloginfo( 'name' ),
			site_url(),
		];

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

		add_action( 'wp_dashboard_setup', array( self::get_instance(), 'add_dashboard_widgets' ) );
		add_action( 'wp_dashboard_setup', array( self::get_instance(), 'remove_default_dashboard_widgets' ), 100 );
		add_action( 'admin_enqueue_scripts', array( self::get_instance(), 'dashboard_styles' ), 100 );

	}

	/**
	 * Add dashboard widgets.
	 *
	 * @param array $user_roles Current user roles.
	 */
	public function add_dashboard_widgets( $user_roles = array() ) {

		$current_user = wp_get_current_user();

		/**
		 * We pass the current user role here to check against in the PRO add-on.
		 * Note: a better variable for $user_roles would be $current_roles as that's really what it actually is.
		 * Shouldn't be changed as parameter passed is called $user_roles.
		 */
		if ( empty( $user_roles ) ) {
			$user_roles = $current_user->roles;
		}

		// Currently only used to add the super admin to current roles.
		$user_roles = apply_filters( 'udb_widget_user_roles', $user_roles );

		$args = array(
			'post_type'      => 'udb_widgets',
			'posts_per_page' => 100,
			'meta_key'       => 'udb_is_active',
			'meta_value'     => 1,
		);

		$loop = new WP_Query( $args );

		while ( $loop->have_posts() ) :

			$loop->the_post();

			$post_id     = get_the_ID();
			$title       = get_the_title();
			$title       = do_shortcode( $title );
			$title       = $this->convert_placeholder_tags( $title );
			$icon        = get_post_meta( $post_id, 'udb_icon_key', true );
			$link        = get_post_meta( $post_id, 'udb_link', true );
			$target      = get_post_meta( $post_id, 'udb_link_target', true );
			$tooltip     = get_post_meta( $post_id, 'udb_tooltip', true );
			$position    = get_post_meta( $post_id, 'udb_position_key', true );
			$priority    = get_post_meta( $post_id, 'udb_priority_key', true );
			$widget_type = get_post_meta( $post_id, 'udb_widget_type', true );
			$output      = '';

			// Preventing edge case when widget_type is empty.
			if ( ! $widget_type ) {

				do_action( 'udb_compat_widget_type', $post_id );

			}

			$allow_access = apply_filters( 'udb_allow_widget_access', true, $post_id, $user_roles );

			if ( ! $allow_access ) {
				continue;
			}

			if ( 'html' === $widget_type ) {

				$content = get_post_meta( $post_id, 'udb_html', true );

				$output = sprintf(
					'<div class="udb-html-wrapper">%1s</div>',
					wp_kses_post( do_shortcode( $content ) )
				);

				$output = $this->convert_placeholder_tags( $output );

			} elseif ( 'text' === $widget_type ) {

				$content = get_post_meta( $post_id, 'udb_content', true );

				$content_height = get_post_meta( $post_id, 'udb_content_height', true );
				$content_height = $content_height ? $content_height : '';

				$output = sprintf(
					'<div class="udb-content-wrapper"%1s>%2s</div>',
					$content_height ? ' data-udb-content-height="' . esc_attr( $content_height ) . '"' : '',
					wp_kses_post( wpautop( $content ) )
				);

				$output = do_shortcode( $output );
				$output = $this->convert_placeholder_tags( $output );

			} elseif ( 'icon' === $widget_type ) {

				$link = is_string( $link ) ? $link : '';

				if ( 0 === strpos( $link, './wp-admin/' ) ) {
					// Prevent double wp-admin string ('/wp-admin/wp-admin/') when rendering the link.
					$link = str_replace( './wp-admin/', './', $link );
				}

				$output = sprintf(
					'<a href="%1$s" target="%2$s"><i class="%3$s"></i></a>',
					// We don't use esc_url() here since $link can be a relative path.
					esc_attr( $link ),
					esc_attr( $target ),
					esc_attr( $icon )
				);

				if ( $tooltip ) {
					$tooltip = $this->convert_placeholder_tags( $tooltip );

					$output .= sprintf(
						'<i class="udb-info"></i><div class="udb-tooltip"><span>%1s</span></div>',
						esc_html( $tooltip )
					);
				}
			}

			$output_args = array(
				'id'          => $post_id,
				'title'       => $title,
				'position'    => $position,
				'priority'    => $priority,
				'widget_type' => $widget_type,
			);

			$output = apply_filters( 'udb_widget_output', $output, $output_args );

			$output_callback = function () use ( $output ) {
				echo wp_kses_post( $output );
			};

			// Add metabox.
			add_meta_box( 'ms-udb' . $post_id, $title, $output_callback, 'dashboard', $position, $priority );

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
		require __DIR__ . '/inc/widget-styles.css.php';
		$css = ob_get_clean();

		wp_add_inline_style( 'udb-dashboard', $css );

	}

	/**
	 * Convert placeholder tags with their values.
	 *
	 * @param string $str The string to replace the tags in.
	 * @return string The modified string.
	 */
	public function convert_placeholder_tags( $str ) {

		$str = str_replace( $this->placeholder_tags, $this->placeholder_values, $str );
		$str = apply_filters( 'udb_widgets_convert_placeholder_tags', $str );

		return $str;

	}

}
