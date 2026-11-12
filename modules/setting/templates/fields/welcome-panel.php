<?php
/**
 * Welcome panel field.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Widget\Widget_Output;

return function () {

	$widget_output    = Widget_Output::get_instance();
	$placeholder_tags = $widget_output->placeholder_tags;
	$placeholder_tags = apply_filters( 'udb_widgets_placeholder_tags', $placeholder_tags );
	$total_tags       = count( $placeholder_tags );
	?>

	<p><?php esc_html_e( 'Use the placeholder tags below to display certain information dynamically.', 'ultimate-dashboard' ); ?></p>

	<p>
		<?php
		foreach ( $placeholder_tags as $tag_index => $placeholder_tag ) {
			?>
			<code><?php echo esc_html( $placeholder_tag ); ?></code><?php echo ( $total_tags - 1 === $tag_index ? '' : ',' ); ?>
			<?php
		}
		?>
	</p>

	<p class="description">
		<?php esc_html_e( 'Leave empty to show WordPress\' default welcome panel.', 'ultimate-dashboard' ); ?>
	</p>

	<?php
	$settings  = get_option( 'udb_settings' );
	$editor_id = 'udb_settings--welcome_panel_content';
	$content   = isset( $settings['welcome_panel_content'] ) ? trim( $settings['welcome_panel_content'] ) : '';

	/**
	 * Based on Keypress UI code.
	 *
	 * @see wp-content/plugins/keypress-ui/includes/modules/dashboard/class-kpui-widgets.php
	 */
	global $compress_css;

	$wp_styles = wp_styles();
	$dir       = 'ltr' === $wp_styles->text_direction ? '' : '-rtl';
	$ver       = $wp_styles->default_version;
	$min       = ! defined( 'SCRIPT_DEBUG' ) && $compress_css ? '.min' : '';

	$args = array(
		'textarea_name' => 'udb_settings[welcome_panel_content]',
		'media_buttons' => false,
		'editor_height' => 300,
		'tinymce'       => array(
			'body_class'    => 'wp-core-ui welcome-panel',
			'content_style' => '.welcome-panel-content .hide-if-customize {display: none;} .welcome-panel {border: none;}',
			'content_css'   => implode(
				',',
				array(
					includes_url( "css/dashicons{$dir}{$min}.css?ver={$ver}" ),
					includes_url( "css/buttons{$dir}{$min}.css?ver={$ver}" ),
					admin_url( "css/common{$dir}{$min}.css?ver={$ver}" ),
					admin_url( "css/dashboard{$dir}{$min}.css?ver={$ver}" ),
				)
			),
		),
	);

	wp_editor( $content, $editor_id, $args );
	?>

	<p>
		<button type="button" class="button udb-welcome-panel-reset" data-editor="<?php echo esc_attr( $editor_id ); ?>">
			<?php esc_html_e( 'Reset to WordPress default', 'ultimate-dashboard' ); ?>
		</button>
	</p>

	<?php

};
