<?php
/**
 * Form footer section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 * @var $branding This variable is brought from login-customizer.php file.
 * @var $branding_enabled This variable is brought from login-customizer.php file.
 * @var $accent_color This variable is brought from login-customizer.php file.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Udb_Customize_Color_Control;
use Udb\Udb_Customize_Range_Control;

$wp_customize->add_setting(
	'udb_login[labels_font_size]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '14px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[labels_font_size]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_labels_section',
			'settings'    => 'udb_login[labels_font_size]',
			'label'       => __( 'Font Size', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[labels_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#444444',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[labels_color]',
		array(
			'label'    => __( 'Text Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_labels_section',
			'settings' => 'udb_login[labels_color]',
		)
	)
);
