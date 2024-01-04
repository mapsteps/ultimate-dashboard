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

		if ( ! get_post_meta( $this->post->ID, 'breakdance_dependency_cache', true ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Prepare blocks output.
	 */
	public function prepare_output() {

		if ( ! $this->is_active() ) {
			return;
		}

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

}
