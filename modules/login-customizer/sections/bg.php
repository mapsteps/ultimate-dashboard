<?php
/**
 * Background section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Udb_Customize_Pro_Control;
use Udb\Udb_Customize_Color_Control;

$wp_customize->add_setting(
	'udb_login[bg_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#f1f1f1',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[bg_color]',
		array(
			'label'    => __( 'Background Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_bg_section',
			'settings' => 'udb_login[bg_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[pro_bg]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Pro_Control(
		$wp_customize,
		'udb_login[pro_bg]',
		array(
			'label'       => __( 'Background Image', 'ultimate-dashboard' ),
			'description' => __( 'This feature is available in Ultimate Dashboard PRO.' ),
			'section'     => 'udb_login_customizer_bg_section',
			'settings'    => 'udb_login[pro_bg]',
		)
	)
);
