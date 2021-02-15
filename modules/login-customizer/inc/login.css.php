<?php
/**
 * Login styles.
 *
 * @package Ultimate_Dashboard
 *
 * @subpackage Login_Customizer
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$branding = get_option( 'udb_branding', array() );
$login    = get_option( 'udb_login', array() );

$branding_enabled = isset( $branding['enabled'] ) ? true : false;
$accent_color     = isset( $branding['accent_color'] ) ? $branding['accent_color'] : '';
$has_accent_color = $branding_enabled && ! empty( $accent_color ) ? true : false;

$logo_image  = isset( $login['logo_image'] ) ? $login['logo_image'] : '';
$logo_image  = apply_filters( 'udb_login_logo', $logo_image );
$logo_height = isset( $login['logo_height'] ) ? $login['logo_height'] : '100%';

$bg_color = isset( $login['bg_color'] ) ? $login['bg_color'] : '';

$form_bg_color           = isset( $login['form_bg_color'] ) ? $login['form_bg_color'] : '';
$form_width              = isset( $login['form_width'] ) ? $login['form_width'] : '';
$form_top_padding        = isset( $login['form_top_padding'] ) ? $login['form_top_padding'] : '';
$form_bottom_padding     = isset( $login['form_bottom_padding'] ) ? $login['form_bottom_padding'] : '';
$form_horizontal_padding = isset( $login['form_horizontal_padding'] ) ? $login['form_horizontal_padding'] : ''; // Not in use?
$form_border_color       = isset( $login['form_border_color'] ) ? $login['form_border_color'] : '#dddddd';
$form_border_width       = isset( $login['form_border_width'] ) ? $login['form_border_width'] : '2px';
$form_border_radius      = isset( $login['form_border_radius'] ) ? $login['form_border_radius'] : '4px';

$fields_height             = isset( $login['fields_height'] ) ? $login['fields_height'] : '50px';
$fields_horizontal_padding = isset( $login['fields_horizontal_padding'] ) ? $login['fields_horizontal_padding'] : '10px';
$fields_border_width       = isset( $login['fields_border_width'] ) ? $login['fields_border_width'] : '2px';
$fields_border_radius      = isset( $login['fields_border_radius'] ) ? $login['fields_border_radius'] : '4px';
$fields_text_color         = isset( $login['fields_text_color'] ) ? $login['fields_text_color'] : '';
$fields_text_color_focus   = isset( $login['fields_text_color_focus'] ) ? $login['fields_text_color_focus'] : '';
$fields_bg_color           = isset( $login['fields_bg_color'] ) ? $login['fields_bg_color'] : '';
$fields_bg_color_focus     = isset( $login['fields_bg_color_focus'] ) ? $login['fields_bg_color_focus'] : '';
$fields_border_color       = isset( $login['fields_border_color'] ) ? $login['fields_border_color'] : '#dddddd';
$fields_border_color_focus = isset( $login['fields_border_color_focus'] ) ? $login['fields_border_color_focus'] : '';
$fields_border_color_focus = ! $fields_border_color_focus && $has_accent_color ? $accent_color : $fields_border_color_focus; // Additional checking to inherit wp-admin accent color by default.

$labels_color = isset( $login['labels_color'] ) ? $login['labels_color'] : '';

$fields_height_unit   = preg_replace( '/\d+/', '', $fields_height );
$fields_height_number = str_ireplace( $fields_height_unit, '', $fields_height );
$hide_pw_top          = ( $fields_height_number / 2 ) - 20;
$hide_pw_top          = $hide_pw_top . $fields_height_unit;

$button_height             = isset( $login['button_height'] ) ? $login['button_height'] : '35px';
$button_horizontal_padding = isset( $login['button_horizontal_padding'] ) ? $login['button_horizontal_padding'] : '15px';
$button_text_color         = isset( $login['button_text_color'] ) ? $login['button_text_color'] : '';
$button_text_color_hover   = isset( $login['button_text_color_hover'] ) ? $login['button_text_color_hover'] : '';
$button_bg_color           = isset( $login['button_bg_color'] ) ? $login['button_bg_color'] : '';
$button_bg_color           = ! $button_bg_color && $has_accent_color ? $accent_color : $button_bg_color; // Additional checking to inherit wp-admin accent color by default.
$button_bg_color_hover     = isset( $login['button_bg_color_hover'] ) ? $login['button_bg_color_hover'] : '';
$button_bg_color_hover     = ! $button_bg_color && $has_accent_color ? $accent_color : $button_bg_color_hover; // Additional checking to inherit wp-admin accent color by default.
$button_border_radius      = isset( $login['button_border_radius'] ) ? $login['button_border_radius'] : '';

$footer_link_color       = isset( $login['footer_link_color'] ) ? $login['footer_link_color'] : '';
$footer_link_color_hover = isset( $login['footer_link_color_hover'] ) ? $login['footer_link_color_hover'] : '';
$footer_link_color_hover = ! $footer_link_color_hover && $has_accent_color ? $accent_color : $footer_link_color_hover; // Additional checking to inherit wp-admin accent color by default.
?>

<?php if ( $bg_color ) : ?>
	body.login {
		background-color: <?php echo esc_attr( $bg_color ); ?>;
	}
<?php endif; ?>

.login h1 {
	padding: 0 12px;
}

.login h1 a {
	width: 100%;
	height: 90px;
	background-repeat: no-repeat;
	background-position: center bottom;
	background-size: auto <?php echo esc_attr( $logo_height ); ?>;
}

<?php if ( $logo_image ) : ?>
	.login h1 a {
		background-image: url(<?php echo esc_url( $logo_image ); ?>);
	}
<?php endif; ?>

<?php if ( $labels_color ) : ?>
	#loginform label {
		color: <?php echo esc_attr( $labels_color ); ?>;
	}
<?php endif; ?>

<?php if ( $form_width ) : ?>
	#login {
		width: <?php echo esc_attr( $form_width ); ?>;
	}
<?php endif; ?>

.login form,
#loginform {
	box-shadow: none;
	border-width: <?php echo esc_attr( $form_border_width ); ?>;
	border-color: <?php echo esc_attr( $form_border_color ); ?>;
	border-radius: <?php echo esc_attr( $form_border_radius ); ?>;
	<?php if ( $form_bg_color ) : ?>
		background-color: <?php echo esc_attr( $form_bg_color ); ?>;
	<?php endif; ?>
	<?php if ( $form_top_padding ) : ?>
		padding-top: <?php echo esc_attr( $form_top_padding ); ?>;
	<?php endif; ?>
	<?php if ( $form_bottom_padding ) : ?>
		padding-bottom: <?php echo esc_attr( $form_bottom_padding ); ?>;
	<?php endif; ?>
}

.login input[type=text],
.login input[type=password] {
	border-width: 2px;
	box-shadow: none;
	outline: none;
	transition: all 0.30s ease-in-out;
	border-color: <?php echo esc_attr( $fields_border_color ); ?>;
	border-radius: <?php echo esc_attr( $fields_border_radius ); ?>;
	border-width: <?php echo esc_attr( $fields_border_width ); ?>;
	padding: 0 <?php echo esc_attr( $fields_horizontal_padding ); ?>;
	height: <?php echo esc_attr( $fields_height ); ?>;
	<?php if ( $fields_text_color ) : ?>
		color: <?php echo esc_attr( $fields_text_color ); ?>;
	<?php endif; ?>
	<?php if ( $fields_bg_color ) : ?>
	background-color: <?php echo esc_attr( $fields_bg_color ); ?>;
	<?php endif; ?>
}

.login input[type=text]:focus,
.login input[type=password]:focus {
	<?php if ( $fields_text_color_focus ) : ?>
		color: <?php echo esc_attr( $fields_text_color_focus ); ?>;
	<?php endif; ?>
	<?php if ( $fields_bg_color_focus ) : ?>
		background-color: <?php echo esc_attr( $fields_bg_color_focus ); ?>;
	<?php endif; ?>
	<?php if ( $fields_border_color_focus ) : ?>
		border-color: <?php echo esc_attr( $fields_border_color_focus ); ?>;
	<?php endif; ?>
}

.login .button.wp-hide-pw {
	margin-top: <?php echo esc_attr( $hide_pw_top ); ?>;
}

.login #backtoblog,
.login #nav {
	text-align: center;
}

<?php if ( $footer_link_color ) : ?>
.login #nav a,
.login #backtoblog a {
	color: <?php echo esc_attr( $footer_link_color ); ?>;
}
<?php endif; ?>

<?php if ( $footer_link_color_hover ) : ?>
.login #nav a:hover,
.login #nav a:focus,
.login #backtoblog a:hover,
.login #backtoblog a:focus {
		color: <?php echo esc_attr( $footer_link_color_hover ); ?>;
}
<?php endif; ?>

.wp-core-ui .button {
	padding: 0 <?php echo esc_attr( $button_horizontal_padding ); ?>;
	height: <?php echo esc_attr( $button_height ); ?>;
	line-height: <?php echo esc_attr( $button_height ); ?>;
}

.wp-core-ui .button.button-primary {
	<?php if ( $button_text_color ) : ?>
		color: <?php echo esc_attr( $button_text_color ); ?>;
	<?php endif; ?>
	<?php if ( $button_bg_color ) : ?>
		background-color: <?php echo esc_attr( $button_bg_color ); ?>;
		border-color: <?php echo esc_attr( $button_bg_color ); ?>;
	<?php endif; ?>
	<?php if ( $button_border_radius ) : ?>
		border-radius: <?php echo esc_attr( $button_border_radius ); ?>;
	<?php endif; ?>
}

.wp-core-ui .button.button-primary:hover,
.wp-core-ui .button.button-primary:focus {
	<?php if ( $button_text_color_hover ) : ?>
		color: <?php echo esc_attr( $button_text_color_hover ); ?>;
	<?php endif; ?>
	<?php if ( $button_bg_color_hover ) : ?>
		background-color: <?php echo esc_attr( $button_bg_color_hover ); ?>;
		border-color: <?php echo esc_attr( $button_bg_color_hover ); ?>;
	<?php endif; ?>
}
