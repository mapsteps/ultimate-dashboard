<?php
/**
 * Gutenberg blocks helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

use WP_Post;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Class to set up Blocks helper.
 */
class Block_Helper {

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

		if ( version_compare( $GLOBALS['wp_version'], '5.0', '<' ) ) {
			return false;
		}

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
	public function built_with_block() {

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

		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );

	}

	/**
	 * Do some actions on admin_head hook.
	 */
	public function admin_head() {

		wp_enqueue_script(
			'udb-admin-page-blocks',
			ULTIMATE_DASHBOARD_PLUGIN_URL . '/modules/admin-page/assets/js/admin-page-blocks.js',
			array(),
			ULTIMATE_DASHBOARD_PLUGIN_VERSION,
			false
		);

	}

	/**
	 * Do some actions on admin_footer hook.
	 */
	public function admin_footer() {

		// Maybe do something.
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

		$post_url = get_permalink( $this->post->ID ) . '&udb-inside-iframe=1';
		?>

		<iframe src="<?php echo esc_url( $post_url ); ?>" width="100%"
				id="udb-admin-page-blocks-iframe"
				style="position: relative; border: 0; margin: 0; padding: 0; overflow: hidden !important;"></iframe>

		<?php

	}

}
