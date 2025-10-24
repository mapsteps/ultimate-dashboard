<?php
/**
 * Background section of Login Customizer.
 *
 * @var $wp_customize This variable is brought from login-customizer.php file.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper;
use Udb\Udb_Customize_Control;
use Udb\Udb_Customize_Image_Control;
use Udb\Udb_Customize_Color_Control;
use Udb\Udb_Customize_Toggle_Switch_Control;
use Udb\Udb_Customize_Color_Picker_Control;

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

$content_helper = new Content_Helper();

$wp_customize->add_setting(
	'udb_login[bg_image]',
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
		'udb_login[bg_image]',
		array(
			'label'    => __( 'Upload Background', 'ultimate-dashboard' ),
			'section'  => 'udb_login_customizer_bg_section',
			'settings' => 'udb_login[bg_image]',
		)
	)
);

$wp_customize->add_setting(
	'udb_login[bg_repeat]',
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
		'udb_login[bg_repeat]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_bg_section',
			'settings' => 'udb_login[bg_repeat]',
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
	'udb_login[bg_position]',
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
		'udb_login[bg_position]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_bg_section',
			'settings' => 'udb_login[bg_position]',
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
	'udb_login[bg_custom_position]',
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
		'udb_login[bg_custom_position]',
		array(
			'type'        => 'text',
			'section'     => 'udb_login_customizer_bg_section',
			'settings'    => 'udb_login[bg_custom_position]',
			'label'       => __( 'Custom Background Position', 'ultimate-dashboard' ),
			'description' => '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/background-position" target="_blank">Click here</a> for more information.',
			'input_attrs' => array(
				'placeholder' => '0% 0%',
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[bg_size]',
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
		'udb_login[bg_size]',
		array(
			'type'     => 'select',
			'section'  => 'udb_login_customizer_bg_section',
			'settings' => 'udb_login[bg_size]',
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
	'udb_login[bg_custom_size]',
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
		'udb_login[bg_custom_size]',
		array(
			'type'        => 'text',
			'section'     => 'udb_login_customizer_bg_section',
			'settings'    => 'udb_login[bg_custom_size]',
			'label'       => __( 'Custom Background Size', 'ultimate-dashboard' ),
			'description' => '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/background-size" target="_blank">Click here</a> for more information.',
			'input_attrs' => array(
				'placeholder' => 'auto auto',
			),
		)
	)
);

$wp_customize->add_setting(
	'udb_login[enable_bg_overlay_color]',
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
		'udb_login[enable_bg_overlay_color]',
		array(
			'settings' => 'udb_login[enable_bg_overlay_color]',
			'section'  => 'udb_login_customizer_bg_section',
			'label'    => __( 'Background Overlay', 'ultimate-dashboard' ),
		)
	)
);

$setting_args = array(
	'type'              => 'option',
	'capability'        => 'edit_theme_options',
	'transport'         => 'postMessage',
	// @todo Provide proper sanitize based on WPTT color alpha repo.
	'sanitize_callback' => 'sanitize_text_field', // Because sanitize_hex_color wouldn't work on rgba.
);

$opts = get_option( 'udb_login', array() );

if ( isset( $opts['bg_overlay_color'] ) ) {
	$setting_args['default'] = $opts['bg_overlay_color'];
}

$wp_customize->add_setting(
	'udb_login[bg_overlay_color]',
	$setting_args
);

$wp_customize->add_control(
	new Udb_Customize_Color_Picker_Control(
		$wp_customize,
		'udb_login[bg_overlay_color]',
		array(
			'settings' => 'udb_login[bg_overlay_color]',
			'section'  => 'udb_login_customizer_bg_section',
			'label'    => __( 'Overlay Color', 'ultimate-dashboard' ),
			'alpha'    => true,
		)
	)
);
