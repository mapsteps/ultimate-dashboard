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
use Udb\Udb_Customize_Toggle_Switch_Control;

$wp_customize->add_setting(
	'udb_login[footer_link_color]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => '#555d66',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[footer_link_color]',
		array(
			'label'    => __( 'Link Color', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_form_footer_section',
			'settings' => 'udb_login[footer_link_color]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[footer_link_color_hover]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => ( $has_accent_color ? $accent_color : '#00a0d2' ),
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Color_Control(
		$wp_customize,
		'udb_login[footer_link_color_hover]',
		array(
			'label'    => __( 'Link Color (Hover)', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_form_footer_section',
			'settings' => 'udb_login[footer_link_color_hover]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[remove_register_lost_pw_link]',
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
		'udb_login[remove_register_lost_pw_link]',
		array(
			'section'  => 'udb_login_customizer_form_footer_section',
			'settings' => 'udb_login[remove_register_lost_pw_link]',
			'label'    => __( 'Remove "Register | Lost your password?" link', 'ultimate-dashboard' ),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[remove_back_to_site_link]',
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
		'udb_login[remove_back_to_site_link]',
		array(
			'section'  => 'udb_login_customizer_form_footer_section',
			'settings' => 'udb_login[remove_back_to_site_link]',
			'label'    => __( 'Remove "Back to website" link', 'ultimate-dashboard' ),
		)
	)
);


$wp_customize->add_setting(
	'udb_login[remove_lang_switcher]',
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
		'udb_login[remove_lang_switcher]',
		array(
			'section'  => 'udb_login_customizer_form_footer_section',
			'settings' => 'udb_login[remove_lang_switcher]',
			'label'    => __( 'Remove language switcher', 'ultimate-dashboard' ),
		)
	)
);
