<?php
/**
 * Custom CSS section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 *
 * @see wp-includes/class-wp-customize-manager.php
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\LoginCustomizer\Custom_Css_Setting;

$custom_css_setting = new Custom_Css_Setting(
	$wp_customize,
	'udb_login[custom_css]',
	array(
		'capability' => 'edit_css',
		'default'    => '',
	)
);
$wp_customize->add_setting( $custom_css_setting );

$wp_customize->add_control(
	new WP_Customize_Code_Editor_Control(
		$wp_customize,
		'udb_login[custom_css]',
		array(
			'label'       => __( 'Custom CSS', 'ultimate-dashboard' ),
			'section'     => 'udb_login_customizer_custom_css_js_section',
			'settings'    => 'udb_login[custom_css]',
			'code_type'   => 'text/css',
			'input_attrs' => array(
				'aria-describedby' => 'editor-keyboard-trap-help-1 editor-keyboard-trap-help-2 editor-keyboard-trap-help-3 editor-keyboard-trap-help-4',
			),
		)
	)
);
