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

$bg_color    = isset( $login['bg_color'] ) ? $login['bg_color'] : '';
$bg_image    = isset( $login['bg_image'] ) ? $login['bg_image'] : '';
$bg_position = isset( $login['bg_position'] ) ? $login['bg_position'] : 'center';
$bg_size     = isset( $login['bg_size'] ) ? $login['bg_size'] : 'cover';
$bg_repeat   = isset( $login['bg_repeat'] ) ? $login['bg_repeat'] : 'no-repeat';

$bg_custom_size     = isset( $login['bg_custom_size'] ) ? $login['bg_custom_size'] : '';
$bg_custom_position = isset( $login['bg_custom_position'] ) ? $login['bg_custom_position'] : '';

// The udb toggle switch control saves the value as 0 if it's un-checked.
$bg_overlay_enabled = isset( $login['enable_bg_overlay_color'] ) ? absint( $login['enable_bg_overlay_color'] ) : 0;
$bg_overlay_color   = isset( $login['bg_overlay_color'] ) ? $login['bg_overlay_color'] : '';

$form_bg_color    = isset( $login['form_bg_color'] ) ? $login['form_bg_color'] : '';
$form_bg_image    = isset( $login['form_bg_image'] ) ? $login['form_bg_image'] : '';
$form_bg_position = isset( $login['form_bg_position'] ) ? $login['form_bg_position'] : 'center';
$form_bg_size     = isset( $login['form_bg_size'] ) ? $login['form_bg_size'] : 'cover';
$form_bg_repeat   = isset( $login['form_bg_repeat'] ) ? $login['form_bg_repeat'] : 'no-repeat';

$form_bg_custom_size     = isset( $login['form_bg_custom_size'] ) ? $login['form_bg_custom_size'] : '';
$form_bg_custom_position = isset( $login['form_bg_custom_position'] ) ? $login['form_bg_custom_position'] : '';

$form_width              = isset( $login['form_width'] ) ? $login['form_width'] : '';
$form_top_padding        = isset( $login['form_top_padding'] ) ? $login['form_top_padding'] : '';
$form_bottom_padding     = isset( $login['form_bottom_padding'] ) ? $login['form_bottom_padding'] : '';
$form_horizontal_padding = isset( $login['form_horizontal_padding'] ) ? $login['form_horizontal_padding'] : ''; // Not in use?
$form_border_width       = isset( $login['form_border_width'] ) ? $login['form_border_width'] : '2px';
$form_border_style       = isset( $login['form_border_style'] ) ? $login['form_border_style'] : 'solid';
$form_border_color       = isset( $login['form_border_color'] ) ? $login['form_border_color'] : '#dddddd';
$form_border_radius      = isset( $login['form_border_radius'] ) ? $login['form_border_radius'] : '4px';

$enable_form_shadow = isset( $login['enable_form_shadow'] ) ? $login['enable_form_shadow'] : 0;
$form_shadow_blur   = isset( $login['form_shadow_blur'] ) ? $login['form_shadow_blur'] : '10px';
$form_shadow_color  = isset( $login['form_shadow_color'] ) ? $login['form_shadow_color'] : '#cccccc';

$fields_height             = isset( $login['fields_height'] ) ? $login['fields_height'] : '50px';
$fields_horizontal_padding = isset( $login['fields_horizontal_padding'] ) ? $login['fields_horizontal_padding'] : '10px';
$fields_border_width       = isset( $login['fields_border_width'] ) ? $login['fields_border_width'] : '2px';
$fields_border_radius      = isset( $login['fields_border_radius'] ) ? $login['fields_border_radius'] : '4px';
$fields_font_size          = isset( $login['fields_font_size'] ) ? $login['fields_font_size'] : '';
$fields_text_color         = isset( $login['fields_text_color'] ) ? $login['fields_text_color'] : '';
$fields_text_color_focus   = isset( $login['fields_text_color_focus'] ) ? $login['fields_text_color_focus'] : '';
$fields_bg_color           = isset( $login['fields_bg_color'] ) ? $login['fields_bg_color'] : '';
$fields_bg_color_focus     = isset( $login['fields_bg_color_focus'] ) ? $login['fields_bg_color_focus'] : '';
$fields_border_color       = isset( $login['fields_border_color'] ) ? $login['fields_border_color'] : '#dddddd';
$fields_border_color_focus = isset( $login['fields_border_color_focus'] ) ? $login['fields_border_color_focus'] : '';
$fields_border_color_focus = ! $fields_border_color_focus && $has_accent_color ? $accent_color : $fields_border_color_focus; // Additional checking to inherit wp-admin accent color by default.

$labels_color     = isset( $login['labels_color'] ) ? $login['labels_color'] : '';
$labels_font_size = isset( $login['labels_font_size'] ) ? $login['labels_font_size'] : '';

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

$remove_register_lost_pw_link = isset( $login['remove_register_lost_pw_link'] ) ? absint( $login['remove_register_lost_pw_link'] ) : 0;
$remove_back_to_site_link     = isset( $login['remove_back_to_site_link'] ) ? absint( $login['remove_back_to_site_link'] ) : 0;
$remove_lang_switcher         = isset( $login['remove_lang_switcher'] ) ? absint( $login['remove_lang_switcher'] ) : 0;
?>

body.login {
	<?php if ( $bg_color ) : ?>
		background-color: <?php echo esc_attr( $bg_color ); ?>;
	<?php endif; ?>

	<?php if ( $bg_image ) : ?>
		background-image: url(<?php echo esc_attr( $bg_image ); ?>);

		<?php if ( 'custom' === $bg_position ) : ?>
			<?php if ( ! empty( $bg_custom_position ) ) : ?>
				background-position: <?php echo esc_attr( $bg_custom_position ); ?>;
			<?php endif; ?>
		<?php else : ?>
			background-position: <?php echo esc_attr( $bg_position ); ?>;
		<?php endif; ?>

		<?php if ( 'custom' === $bg_size ) : ?>
			<?php if ( ! empty( $bg_custom_size ) ) : ?>
				background-size: <?php echo esc_attr( $bg_custom_size ); ?>;
			<?php endif; ?>
		<?php else : ?>
			background-size: <?php echo esc_attr( $bg_size ); ?>;
		<?php endif; ?>

		background-repeat: <?php echo esc_attr( $bg_repeat ); ?>;
	<?php endif; ?>
}

.udb-bg-overlay {
	position: fixed;
	width: 100%;
	height: 100%;
	<?php if ( $bg_image && $bg_overlay_enabled && $bg_overlay_color ) : ?>
		background-color: <?php echo esc_attr( $bg_overlay_color ); ?>;
	<?php endif; ?>
	z-index: 0;
}

#login {
	position: relative;
}

.login h1 {
	padding: 0 12px;
}

.login h1 a,
.login .wp-login-logo a {
	width: 100%;
	height: 90px;
	background-repeat: no-repeat;
	background-position: center bottom;
	background-size: auto <?php echo esc_attr( $logo_height ); ?>;
}

<?php if ( $logo_image ) : ?>
	.login h1 a,
	.login .wp-login-logo a {
		background-image: url(<?php echo esc_url( $logo_image ); ?>);
	}
<?php endif; ?>

<?php if ( $labels_color || $labels_font_size ) : ?>
	#loginform label {

		<?php if ( $labels_color ) : ?>
			color: <?php echo esc_attr( $labels_color ); ?>;
		<?php endif; ?>

		<?php if ( $labels_font_size ) : ?>
			font-size: <?php echo esc_attr( $labels_font_size ); ?>;
		<?php endif; ?>
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
	border-style: <?php echo esc_attr( $form_border_style ); ?>;
	border-color: <?php echo esc_attr( $form_border_color ); ?>;
	border-radius: <?php echo esc_attr( $form_border_radius ); ?>;

	<?php if ( $enable_form_shadow ) : ?>
		box-shadow: 0 0 <?php echo esc_attr( $form_shadow_blur ); ?> 0 <?php echo esc_attr( $form_shadow_color ); ?>;
	<?php endif; ?>

	<?php if ( $form_bg_color ) : ?>
		background-color: <?php echo esc_attr( $form_bg_color ); ?>;
	<?php endif; ?>

	<?php if ( $form_bg_image ) : ?>
		background-image: url(<?php echo esc_attr( $form_bg_image ); ?>);

		<?php if ( 'custom' === $form_bg_position ) : ?>
			<?php if ( ! empty( $form_bg_custom_position ) ) : ?>
				background-position: <?php echo esc_attr( $form_bg_custom_position ); ?>;
			<?php endif; ?>
		<?php else : ?>
			background-position: <?php echo esc_attr( $form_bg_position ); ?>;
		<?php endif; ?>

		<?php if ( 'custom' === $form_bg_size ) : ?>
			<?php if ( ! empty( $form_bg_custom_size ) ) : ?>
				background-size: <?php echo esc_attr( $form_bg_custom_size ); ?>;
			<?php endif; ?>
		<?php else : ?>
			background-size: <?php echo esc_attr( $form_bg_size ); ?>;
		<?php endif; ?>

		background-repeat: <?php echo esc_attr( $form_bg_repeat ); ?>;
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

	<?php if ( $fields_font_size ) : ?>
		font-size: <?php echo esc_attr( $fields_font_size ); ?>;
	<?php endif; ?>

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

<?php if ( $remove_register_lost_pw_link ) : ?>
	.login #nav {
		display: none;
	}
<?php endif; ?>

<?php if ( $remove_back_to_site_link ) : ?>
	.login #backtoblog {
		display: none;
	}
<?php endif; ?>

<?php if ( $remove_lang_switcher ) : ?>
	#login .language-switcher {
		display: none;
	}
<?php endif; ?>
