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
