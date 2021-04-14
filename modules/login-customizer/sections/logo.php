<?php
/**
 * Logo section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Udb_Customize_Control;
use Udb\Udb_Customize_Range_Control;
use Udb\Udb_Customize_Image_Control;

use Udb\Helpers\Content_Helper;

$content_helper = new Content_Helper();

$wp_customize->add_setting(
	'udb_login[logo_image]',
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
		'udb_login[logo_image]',
		array(
			'label'    => __( 'Logo', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_logo_section',
			'settings' => 'udb_login[logo_image]',
		)
	)
);

$default_logo_height = '100%';

// Used to provide multisite support in the PRO version.
$default_logo_height = apply_filters( 'udb_login_customizer_default_logo_height', $default_logo_height );

$wp_customize->add_setting(
	'udb_login[logo_height]',
	array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'default'           => $default_logo_height,
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
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Udb_Customize_Control(
		$wp_customize,
		'udb_login[logo_url]',
		array(
			'type'        => 'text',
			'section'     => 'udb_login_customizer_logo_section',
			'settings'    => 'udb_login[logo_url]',
			'label'       => __( 'Logo URL', 'ultimate-dashboard' ),
			'description' => __( 'Available template tags: {home_url}', 'ultimate-dashboard' ),
			'input_attrs' => array(
				'placeholder' => 'https://wordpress.org/',
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
				'placeholder' => 'Powered by WordPress',
			),
		)
	)
);
