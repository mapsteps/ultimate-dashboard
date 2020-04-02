<?php
/**
 * Create login customizer page.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Check if we have a login customizer page assigned.
 */
function udb_check_login_customizer_page() {

	$page     = get_page_by_path( 'udb-login-page' );
	$template = 'udb-login-page.php';
	$status   = null;

	// Retrieve the status of the page, if the option is available.
	if ( ! empty( $page ) && is_object( $page ) ) {

		if ( 'draft' === $page->post_status || 'trash' === $page->post_status ) {
			wp_update_post(
				array(
					'ID'          => $page->ID,
					'post_status' => 'publish',
				)
			);
		}

		if ( 'Login Customizer' !== $page->post_title ) {
			wp_update_post(
				array(
					'ID'         => $page->ID,
					'post_title' => 'Login Customizer',
				)
			);
		}

		$page_id = $page->ID;

	} else {

		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_name'    => 'udb-login-page',
				'post_title'   => 'Login Customizer',
				'post_content' => '',
			)
		);

	}

	update_post_meta( $page_id, '_wp_page_template', $template );

}
add_action( 'admin_init', 'udb_check_login_customizer_page' );

/**
 * Add template to the pages cache in order to convince WordPress
 * into thinking the template file exists where it doens't really exist.
 *
 * @param string|string $atts Attributes.
 * @return string $atts Attributes.
 */
function udb_login_customizer_register_templates( $atts ) {

	$login_templates = array(
		'udb-login-page.php' => esc_html__( 'Login Customizer', 'ultimate-dashboard' ),
	);

	// Create the key used for the themes cache.
	$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

	// Retrieve the cache list.
	// If it doesn't exist or it's empty, prepare an array.
	$templates = wp_get_theme()->get_page_templates();

	if ( empty( $templates ) ) {
		$templates = array();
	}

	// New cache, therefore remove the old one.
	wp_cache_delete( $cache_key, 'themes' );

	// Now add our template to the list of templates by merging our templates
	// with the existing templates array from the cache.
	$templates = array_merge( $templates, $login_templates );

	// Add the modified cache to allow WordPress to pick it up for listing available templates.
	wp_cache_add( $cache_key, $templates, 'themes', 1800 );

	return $atts;

}
// Add a filter to the save post to inject out template into the page cache.
add_filter( 'wp_insert_post_data', 'udb_login_customizer_register_templates' );

/**
 * Checks if the template is assigned to the page.
 *
 * @param string $template The template.
 * @return string $template The template.
 */
function udb_login_customizer_view_template( $template ) {

	global $post;

	// Return template if post is empty.
	if ( ! $post ) {
		return $template;
	}

	$login_templates = array(
		'udb-login-page.php' => esc_html__( 'Login Customizer', 'ultimate-dashboard' ),
	);

	// Return default template if we don't have a custom one defined.
	if ( ! isset( $login_templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) ) {
		return $template;
	}

	$file = ULTIMATE_DASHBOARD_PLUGIN_DIR . 'modules/login-customizer/templates/' . get_post_meta(
		$post->ID,
		'_wp_page_template',
		true
	);

	// Just to be safe, we check if the file exist first.
	if ( file_exists( $file ) ) {
		return $file;
	} else {
		echo esc_url( $file );
	}

	// Return template.
	return $template;

}
// Add a filter to the template include to determine if the page has our template assigned and return it's path.
add_filter( 'template_include', 'udb_login_customizer_view_template' );

/**
 * Ensure the Login Designer page is not indexed.
 */
function udb_login_customizer_noindex_meta() {

	remove_action( 'login_head', 'wp_no_robots' );
	echo '<meta name="robots" content="noindex, nofollow" />' . "\n";

}
// Add a noindex meta tag.
add_action( 'login_head', 'udb_login_customizer_noindex_meta', 9 );
