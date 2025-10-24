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

use Udb\Helpers\Content_Helper;
use Udb\Udb_Customize_Control;
use Udb\Udb_Customize_Pro_Control;
use Udb\Udb_Customize_Image_Control;
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
				'description' => __( 'More layouts (left & right) available in Ultimate Dashboard PRO.', 'ultimate-dashboard' ),
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

$content_helper = new Content_Helper();

$wp_customize->add_setting(
	'udb_login[form_bg_image]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( $content_helper, 'sanitize_image' ),
	)
);

$wp_customize->add_control(
	new Udb_Customize_Image_Control(
		$wp_customize,
		'udb_login[form_bg_image]',
		array(
			'label'    => __( 'Background Image', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_bg_image]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bg_repeat]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => 'no-repeat',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_bg_repeat]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_bg_repeat]',
			'label'    => __( 'Background Repeat', 'ultimate-dashboard' ),
			'choices'  => array(
				'no-repeat' => __( 'no-repeat', 'ultimate-dashboard' ),
				'repeat'    => __( 'repeat', 'ultimate-dashboard' ),
				'repeat-x'  => __( 'repeat-x', 'ultimate-dashboard' ),
				'repeat-y'  => __( 'repeat-y', 'ultimate-dashboard' ),
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bg_position]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => 'center center',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_bg_position]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_bg_position]',
			'label'    => __( 'Background Position', 'ultimate-dashboard' ),
			'choices'  => array(
				'left top'      => __( 'left top', 'ultimate-dashboard' ),
				'left center'   => __( 'left center', 'ultimate-dashboard' ),
				'left bottom'   => __( 'left bottom', 'ultimate-dashboard' ),
				'center top'    => __( 'center top', 'ultimate-dashboard' ),
				'center center' => __( 'center center', 'ultimate-dashboard' ),
				'center bottom' => __( 'center bottom', 'ultimate-dashboard' ),
				'right top'     => __( 'right top', 'ultimate-dashboard' ),
				'right center'  => __( 'right center', 'ultimate-dashboard' ),
				'right bottom'  => __( 'right bottom', 'ultimate-dashboard' ),
				'custom'        => __( 'custom', 'ultimate-dashboard' ),
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bg_custom_position]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_bg_custom_position]',
		array(
			'type'        => 'text',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_bg_custom_position]',
			'label'       => __( 'Custom Background Position', 'ultimate-dashboard' ),
			'description' => '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/background-position" target="_blank">Click here</a> for more information.',
			'input_attrs' => array(
				'placeholder' => '0% 0%',
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bg_size]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => 'cover',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_bg_size]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_bg_size]',
			'label'    => __( 'Background Size', 'ultimate-dashboard' ),
			'choices'  => array(
				'auto'    => __( 'auto', 'ultimate-dashboard' ),
				'cover'   => __( 'cover', 'ultimate-dashboard' ),
				'contain' => __( 'contain', 'ultimate-dashboard' ),
				'custom'  => __( 'custom', 'ultimate-dashboard' ),
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_bg_custom_size]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[form_bg_custom_size]',
		array(
			'type'        => 'text',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_bg_custom_size]',
			'label'       => __( 'Custom Background Size', 'ultimate-dashboard' ),
			'description' => '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/background-size" target="_blank">Click here</a> for more information.',
			'input_attrs' => array(
				'placeholder' => 'auto auto',
			),
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
			'priority'    => 15, // We need to insert "Box Width" control before this one.
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
			'priority'    => 15,
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
			'priority'    => 15,
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
			'priority'    => 15,
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
			'priority'    => 15,
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
			'priority' => 15,
			'label'    => __( 'Border Style', 'ultimate-dashboard' ),
			'choices'  => array(
				'solid'  => __( 'solid', 'ultimate-dashboard' ),
				'dotted' => __( 'dotted', 'ultimate-dashboard' ),
				'dashed' => __( 'dashed', 'ultimate-dashboard' ),
				'double' => __( 'double', 'ultimate-dashboard' ),
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
			'priority' => 15,
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
			'priority'    => 15,
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
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[enable_form_shadow]',
			'priority' => 15,
			'label'    => __( 'Box Shadow', 'ultimate-dashboard' ),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[form_shadow_blur]',
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
		'udb_login[form_shadow_blur]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_layout_section',
			'settings'    => 'udb_login[form_shadow_blur]',
			'priority'    => 15,
			'label'       => __( 'Width', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
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
			'label'    => __( 'Box Shadow Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_layout_section',
			'settings' => 'udb_login[form_shadow_color]',
			'priority' => 15,
		)
	)
);
