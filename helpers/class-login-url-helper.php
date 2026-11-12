<?php
/**
 * Login URL helper.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb\Helpers;

defined( 'ABSPATH' ) || die( "Can't access directly" );

use WP_Error;

/**
 * Checks the slugs used by the Login Redirect module (new login URL, wp-admin redirect).
 */
class Login_Url_Helper {

	/**
	 * Paths WordPress itself needs. A login or redirect slug on one of these breaks the site.
	 *
	 * @var string[]
	 */
	private $reserved = array(
		'wp-admin',
		'wp-login',
		'wp-login.php',
		'wp-content',
		'wp-includes',
		'wp-json',
		'wp-register.php',
		'wp-signup.php',
		'wp-activate.php',
		'wp-cron.php',
		'wp-trackback.php',
		'wp-comments-post.php',
		'xmlrpc.php',
		'index.php',
	);

	/**
	 * Check a new login URL slug.
	 *
	 * @param string $slug The slug (empty = keep the default wp-login.php).
	 *
	 * @return true|WP_Error True when it can be used.
	 */
	public function validate_login_slug( $slug ) {

		$slug = trim( (string) $slug, " \t\n\r\0\x0B/" );

		if ( '' === $slug ) {
			return true;
		}

		$reserved = $this->check_reserved( $slug );

		if ( is_wp_error( $reserved ) ) {
			return $reserved;
		}

		$post_types = get_post_types( array( 'public' => true ) );
		$existing   = get_page_by_path( $slug, OBJECT, array_values( $post_types ) );

		if ( $existing && 'publish' === $existing->post_status ) {
			return new WP_Error(
				'udb_login_slug_taken',
				// translators: %s: The login URL slug.
				sprintf( __( 'The login URL "%s" is already used by a page or post on your site. Please choose another one.', 'ultimate-dashboard' ), $slug )
			);
		}

		return true;

	}

	/**
	 * Check a wp-admin redirect slug. Redirecting to an existing page is the point, so only WordPress' own paths are blocked.
	 *
	 * @param string $slug The slug.
	 *
	 * @return true|WP_Error True when it can be used.
	 */
	public function validate_redirect_slug( $slug ) {

		$slug = trim( (string) $slug, " \t\n\r\0\x0B/" );

		if ( '' === $slug ) {
			return true;
		}

		return $this->check_reserved( $slug );

	}

	/**
	 * Check a slug against WordPress' own paths.
	 *
	 * @param string $slug The slug, without leading/trailing slashes.
	 *
	 * @return true|WP_Error
	 */
	private function check_reserved( $slug ) {

		$first_segment = strtolower( strtok( $slug, '/?#' ) );

		if ( in_array( $first_segment, $this->reserved, true ) ) {
			return new WP_Error(
				'udb_login_slug_reserved',
				// translators: %s: The slug.
				sprintf( __( '"%s" is used by WordPress itself and can\'t be used here. Please choose another one.', 'ultimate-dashboard' ), $slug )
			);
		}

		return true;

	}

}
