<?php
/**
 * Logo section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use UdbPro\Udb_Customize_Control;
use UdbPro\Udb_Customize_Range_Control;
use UdbPro\Udb_Customize_Image_Control;

$wp_customize->add_setting(
	'udb_login[logo_image]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'udb_sanitize_image',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Image_Control(
		$wp_customize,
		'udb_login[logo_image]',
		array(
			'label'    => __( 'Logo Image', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_logo_section',
			'settings' => 'udb_login[logo_image]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[logo_height]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '100%',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_attr',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Range_Control(
		$wp_customize,
		'udb_login[logo_height]',
		array(
			'type'        => 'range',
			'section'     => 'udb_login_customizer_logo_section',
			'settings'    => 'udb_login[logo_height]',
			'label'       => __( 'Logo Height', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[logo_url]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[logo_url]',
		array(
			'type'        => 'url',
			'section'     => 'udb_login_customizer_logo_section',
			'settings'    => 'udb_login[logo_url]',
			'label'       => __( 'Logo Link', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'placeholder' => home_url(),
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[logo_title]',
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
		'udb_login[logo_title]',
		array(
			'type'        => 'text',
			'section'     => 'udb_login_customizer_logo_section',
			'settings'    => 'udb_login[logo_title]',
			'label'       => __( 'Logo Title', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'placeholder' => ucfirst( get_bloginfo( 'name' ) ),
			),
		)
	)
);
