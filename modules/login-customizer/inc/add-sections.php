<?php
/**
 * Add customizer sections.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return function ( $wp_customize ) {

	$wp_customize->add_section(
		'udb_login_customizer_template_section',
		array(
			'title' => __( 'Layouts', 'ultimate-dashboard' ),
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
			'title' => __( 'Footer', 'ultimate-dashboard' ),
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
			'title' => __( 'Custom CSS', 'ultimate-dashboard' ),
			'panel' => 'udb_login_customizer_panel',
		)
	);

};
