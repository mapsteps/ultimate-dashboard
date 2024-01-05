<?php
/**
 * Gutenberg blocks helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

use WP_Block_Styles_Registry;
use WP_Block_Type_Registry;
use WP_Post;
use WP_Screen;
use WP_Styles;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to set up Blocks helper.
 */
class Blocks_Helper {

	/**
	 * WP_Post instance.
	 *
	 * @var WP_Post
	 */
	private $post;

	/**
	 * Class constructor.
	 *
	 * @param WP_Post|null $post Instance of WP_Post.
	 */
	public function __construct( $post = null ) {

		$this->post = $post;

	}

	/**
	 * Check whether Gutenberg's block feature is active.
	 *
	 * @return bool
	 */
	public function is_active() {

		if ( function_exists( '\has_blocks' ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Verify if a post was built with Blocks editor.
	 *
	 * @return bool
	 */
	public function built_with_blocks() {

		if ( ! $this->is_active() ) {
			return false;
		}

		if ( ! has_blocks( $this->post ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Do stuff before output.
	 */
	public function prepare_hooks() {

		if ( ! $this->is_active() ) {
			return;
		}

//		$this->enqueue_global_styles();
//		$this->enqueue_block_styles_assets();
//		$this->enqueue_global_styles_custom_css();
//		$this->enqueue_global_styles_css_custom_properties();
//		$this->enqueue_registered_block_scripts_and_styles();

		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );

	}

	/**
	 * Do some actions on admin_head hook.
	 */
	public function admin_head() {

		wp_enqueue_style( 'wp-block-library' );

		$stylesheet = wp_get_global_stylesheet();

		wp_register_style( 'global-styles', false );
		wp_add_inline_style( 'global-styles', $stylesheet );
		wp_enqueue_style( 'global-styles' );

		// Add each block as an inline css.
		wp_add_global_styles_for_blocks();

	}

	/**
	 * Do some actions on admin_footer hook.
	 */
	public function admin_footer() {

		do_action( 'enqueue_block_assets' );

	}

	/**
	 * Do stuff before output.
	 */
	public function before_output() {

		if ( ! $this->is_active() ) {
			return;
		}

		// Maybe do something.
	}

	/**
	 * Do stuff after output.
	 */
	public function after_output() {

		if ( ! $this->is_active() ) {
			return;
		}

		// Maybe do something.
	}

	/**
	 * Render the content of a post.
	 */
	public function render_content() {

		if ( ! $this->post ) {
			echo '';
		}

		echo apply_filters( 'the_content', $this->post->post_content );

	}

	private function enqueue_global_styles() {

		/*
		 * If loading the CSS for each block separately, then load the theme.json CSS conditionally.
		 * This removes the CSS from the global-styles stylesheet and adds it to the inline CSS for each block.
		 * This filter must be registered before calling wp_get_global_stylesheet();
		 */
		add_filter( 'wp_theme_json_get_style_nodes', 'wp_filter_out_block_nodes' );

		$stylesheet = wp_get_global_stylesheet();

		if ( empty( $stylesheet ) ) {
			return;
		}

		wp_register_style( 'global-styles', false );
		wp_add_inline_style( 'global-styles', $stylesheet );
		wp_enqueue_style( 'global-styles' );

		// Add each block as an inline css.
		wp_add_global_styles_for_blocks();

	}

	/**
	 * Enqueues the global styles custom css defined via theme.json.
	 *
	 * @since 6.2.0
	 */
	private function enqueue_global_styles_custom_css() {

		// Don't enqueue Customizer's custom CSS separately.
		remove_action( 'admin_head', 'wp_custom_css_cb', 101 );

		$custom_css  = wp_get_custom_css();
		$custom_css .= wp_get_global_styles_custom_css();

		if ( ! empty( $custom_css ) ) {
			wp_add_inline_style( 'global-styles', $custom_css );
		}

	}

	/**
	 * Function that enqueues the CSS Custom Properties coming from theme.json.
	 *
	 * @since 5.9.0
	 */
	private function enqueue_global_styles_css_custom_properties() {

		wp_register_style( 'global-styles-css-custom-properties', false );
		wp_add_inline_style( 'global-styles-css-custom-properties', wp_get_global_stylesheet( array( 'variables' ) ) );
		wp_enqueue_style( 'global-styles-css-custom-properties' );

	}

	/**
	 * Enqueues registered block scripts and styles, depending on current rendered
	 * context (only enqueuing editor scripts while in context of the editor).
	 *
	 * @since 5.0.0
	 *
	 * @global WP_Screen $current_screen WordPress current screen object.
	 */
	private function enqueue_registered_block_scripts_and_styles() {

		global $current_screen;

		if ( wp_should_load_separate_core_block_assets() ) {
			return;
		}

		$block_registry = WP_Block_Type_Registry::get_instance();

		foreach ( $block_registry->get_all_registered() as $block_name => $block_type ) {
			// Front-end and editor styles.
			foreach ( $block_type->style_handles as $style_handle ) {
				wp_enqueue_style( $style_handle );
			}

			// Front-end and editor scripts.
			foreach ( $block_type->script_handles as $script_handle ) {
				wp_enqueue_script( $script_handle );
			}
		}

	}

	/**
	 * Function responsible for enqueuing the styles required for block styles functionality on the editor and on the frontend.
	 *
	 * @since 5.3.0
	 *
	 * @global WP_Styles $wp_styles
	 */
	private function enqueue_block_styles_assets() {

		global $wp_styles;

		$block_styles = WP_Block_Styles_Registry::get_instance()->get_all_registered();

		foreach ( $block_styles as $block_name => $styles ) {
			foreach ( $styles as $style_properties ) {
				if ( isset( $style_properties['style_handle'] ) ) {

					// If the site loads separate styles per-block, enqueue the stylesheet on render.
					if ( wp_should_load_separate_core_block_assets() ) {
						add_filter(
							'render_block',
							static function ( $html, $block ) use ( $block_name, $style_properties ) {
								if ( $block['blockName'] === $block_name ) {
									wp_enqueue_style( $style_properties['style_handle'] );
								}

								return $html;
							},
							10,
							2
						);
					} else {
						wp_enqueue_style( $style_properties['style_handle'] );
					}
				}

				if ( isset( $style_properties['inline_style'] ) ) {

					// Default to "wp-block-library".
					$handle = 'wp-block-library';

					// If the site loads separate styles per-block, check if the block has a stylesheet registered.
					if ( wp_should_load_separate_core_block_assets() ) {
						$block_stylesheet_handle = generate_block_asset_handle( $block_name, 'style' );

						if ( isset( $wp_styles->registered[ $block_stylesheet_handle ] ) ) {
							$handle = $block_stylesheet_handle;
						}
					}

					// Add inline styles to the calculated handle.
					wp_add_inline_style( $handle, $style_properties['inline_style'] );
				}
			}
		}

	}

}
