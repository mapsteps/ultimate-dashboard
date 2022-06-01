<?php
/**
 * Layout section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 * @var $branding This variable is brought from login-customizer.php file.
 * @var $branding_enabled This variable is brought from login-customizer.php file.
 * @var $accent_color This variable is brought from login-customizer.php file.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Udb_Customize_Control;
use Udb\Udb_Customize_Pro_Control;
use Udb\Udb_Customize_Color_Control;
use Udb\Udb_Customize_Range_Control;
use Udb\Udb_Customize_Toggle_Switch_Control;

if ( ! defined( 'ULTIMATE_DASHBOARD_PRO_PLUGIN_VERSION' ) ) {
	$wp_customize->add_setting(
		'udb_login[form_position]',
		array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'default'           => 'default',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		new Udb_Customize_Control(
			$wp_customize,
			'udb_login[form_position]',
			array(
				'type'     => 'select',
				'section'  => 'udb_login_customizer_layout_section',
				'settings' => 'udb_login[form_position]',
				'label'    => __( 'Layout', 'ultimate-dashboard' ),
				// The PRO version contains more than "default".
				'choices'  => array(
					'default' => __( 'Default', 'ultimate-dashboard' ),
				),
			)
		)
	);

	$wp_customize->add_setting(
		'udb_login[pro_layout]',
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
			'udb_login[pro_layout]',
			array(
				'label'       => '',
				'description' => __( 'More layouts (left & right) available in Ultimate Dashboard PRO.' ),
				'section'     => 'udb_login_customizer_layout_section',
				'settings'    => 'udb_login[pro_layout]',
			)
		)
	);
}


$wp_customize->add_setting(
	'udb_login[form_bg_color]',
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
		'udb_login[form_bg_color]',
		array(
			'label'    => __( 'Background Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_bg_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_width]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '320px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_width]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_width]',
			'label'       => __( 'Width', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 200,
				'max'  => 1000,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_top_padding]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '26px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_top_padding]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_top_padding]',
			'label'       => __( 'Top Padding', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bottom_padding]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '46px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[form_bottom_padding]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_bottom_padding]',
			'label'       => __( 'Bottom Padding', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_horizontal_padding]',
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
		'udb_login[form_horizontal_padding]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_horizontal_padding]',
			'label'       => __( 'Side Padding', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_border_width]',
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
		'udb_login[form_border_width]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_border_width]',
			'label'       => __( 'Border Width', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_border_style]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => 'solid',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_border_style]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_border_style]',
			'label'    => __( 'Border Style', 'ultimate-dashboard' ),
			'choices'  => array(
				'solid'  => __( 'solid', 'ultimate-dashboard' ),
				'dotted' => __( 'dotted', 'ultimate-dashboard' ),
				'dashed' => __( 'dashed', 'ultimate-dashboard' ),
				'none'   => __( 'none', 'ultimate-dashboard' ),
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_border_color]',
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
		'udb_login[form_border_color]',
		array(
			'label'    => __( 'Border Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_border_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_border_radius]',
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
		'udb_login[form_border_radius]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_border_radius]',
			'label'       => __( 'Border Radius', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 80,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[enable_form_shadow]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => 0,
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Toggle_Switch_Control(
		$wp_customize,
		'udb_login[enable_form_shadow]',
		array(
			'settings' => 'udb_login[enable_form_shadow]',
			'section'  => 'udb_login_customizer_layout_section',
			'label'    => __( "Enable form's box shadow", 'ultimate-dashboard' ),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_shadow_structure]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '0 4px 10px -1px',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_shadow_structure]',
		array(
			'type'        => 'text',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_shadow_structure]',
			'label'       => __( 'Shadow Structure', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'placeholder' => 'Powered by WordPress',
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_shadow_color]',
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
		'udb_login[form_shadow_color]',
		array(
			'label'    => __( 'Shadow Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_shadow_color]',
		)
	)
);
