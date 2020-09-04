<?php
/**
 * Login customizer.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

add_action(
	'customize_register',
	function () {
		// Customize control classes.
		require __DIR__ . '/controls/class-udb-customize-control.php';
		require __DIR__ . '/controls/class-udb-customize-pro-control.php';
		require __DIR__ . '/controls/class-udb-customize-range-control.php';
		require __DIR__ . '/controls/class-udb-customize-image-control.php';
		require __DIR__ . '/controls/class-udb-customize-color-control.php';
		require __DIR__ . '/controls/class-udb-customize-login-template-control.php';
	}
);

// Setup files.
require __DIR__ . '/inc/create-page.php';
require __DIR__ . '/inc/redirect.php';
require __DIR__ . '/inc/enqueue.php';
require __DIR__ . '/inc/submenu.php';
require __DIR__ . '/inc/output.php';

/**
 * Register "Login Customizer" panel in WP Customizer.
 *
 * @param WP_Customize $wp_customize The WP_Customize instance.
 */
function udb_login_customizer_panel( $wp_customize ) {
	$wp_customize->add_panel(
		'udb_login_customizer_panel',
		array(
			'title'      => __( 'Login Customizer', 'ultimate-dashboard' ),
			'capability' => 'manage_options',
			'priority'   => 30,
		)
	);
}
add_action( 'customize_register', 'udb_login_customizer_panel' );

/**
 * Register login customizer's sections in WP Customizer.
 *
 * @param WP_Customize $wp_customize The WP_Customize instance.
 */
function udb_login_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		'udb_login_customizer_template_section',
		array(
			'title' => __( 'Templates', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_logo_section',
		array(
			'title' => __( 'Logo', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_bg_section',
		array(
			'title' => __( 'Background', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_layout_section',
		array(
			'title' => __( 'Login Form', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_fields_section',
		array(
			'title' => __( 'Input Fields', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_labels_section',
		array(
			'title' => __( 'Labels', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_button_section',
		array(
			'title' => __( 'Log In Button', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_form_footer_section',
		array(
			'title' => __( 'Footer Links', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_welcome_messages_section',
		array(
			'title' => __( 'Welcome Messages', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_error_messages_section',
		array(
			'title' => __( 'Error Messages', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_redirect_section',
		array(
			'title' => __( 'Redirect', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

	$wp_customize->add_section(
		'udb_login_customizer_custom_css_js_section',
		array(
			'title' => __( 'Custom CSS & JS', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);
}
add_action( 'customize_register', 'udb_login_customizer_sections' );

/**
 * Register login customizer's settings & controls in WP Customizer.
 *
 * @param WP_Customize $wp_customize The WP_Customize instance.
 */
function udb_login_customizer_controls( $wp_customize ) {
	$branding         = get_option( 'udb_branding', array() );
	$branding_enabled = isset( $branding['enabled'] ) ? true : false;
	$accent_color     = isset( $branding['accent_color'] ) ? $branding['accent_color'] : '';
	$has_accent_color = $branding_enabled && ! empty( $accent_color ) ? true : false;

	require __DIR__ . '/sections/template.php';
	require __DIR__ . '/sections/logo.php';
	require __DIR__ . '/sections/bg.php';
	require __DIR__ . '/sections/layout.php';
	require __DIR__ . '/sections/fields.php';
	require __DIR__ . '/sections/labels.php';
	require __DIR__ . '/sections/button.php';
	require __DIR__ . '/sections/form-footer.php';
}
add_action( 'customize_register', 'udb_login_customizer_controls' );
