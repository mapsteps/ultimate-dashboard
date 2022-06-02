<?php
/**
 * Fields section of Login Customizer.
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
	'udb_login[fields_height]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '50px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[fields_height]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_fields_section',
			'settings'    => 'udb_login[fields_height]',
			'label'       => __( 'Height', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 20,
				'max'  => 80,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_horizontal_padding]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '10px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[fields_horizontal_padding]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_fields_section',
			'settings'    => 'udb_login[fields_horizontal_padding]',
			'label'       => __( 'Side Padding', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 80,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_border_width]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '2px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[fields_border_width]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_fields_section',
			'settings'    => 'udb_login[fields_border_width]',
			'label'       => __( 'Border Width', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 30,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_border_radius]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '4px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[fields_border_radius]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_fields_section',
			'settings'    => 'udb_login[fields_border_radius]',
			'label'       => __( 'Border Radius', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_font_size]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '24px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[fields_font_size]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_fields_section',
			'settings'    => 'udb_login[fields_font_size]',
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
	'udb_login[fields_text_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#32373c',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[fields_text_color]',
		array(
			'label'    => __( 'Text Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_fields_section',
			'settings' => 'udb_login[fields_text_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_text_color_focus]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#32373c',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[fields_text_color_focus]',
		array(
			'label'    => __( 'Text Color (Focus)', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_fields_section',
			'settings' => 'udb_login[fields_text_color_focus]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_bg_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[fields_bg_color]',
		array(
			'label'    => __( 'Background Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_fields_section',
			'settings' => 'udb_login[fields_bg_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_bg_color_focus]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#ffffff',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[fields_bg_color_focus]',
		array(
			'label'    => __( 'Background Color (Focus)', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_fields_section',
			'settings' => 'udb_login[fields_bg_color_focus]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_border_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#dddddd',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[fields_border_color]',
		array(
			'label'    => __( 'Border Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_fields_section',
			'settings' => 'udb_login[fields_border_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[fields_border_color_focus]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => $has_accent_color ? $accent_color : '#007cba',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[fields_border_color_focus]',
		array(
			'label'    => __( 'Border Color (Focus)', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_fields_section',
			'settings' => 'udb_login[fields_border_color_focus]',
		)
	)
);
