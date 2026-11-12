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

	<?php
	$settings   = get_option( 'udb_settings' );
	$editor_id  = 'udb_settings--welcome_panel_content';
	$content    = isset( $settings['welcome_panel_content'] ) ? $settings['welcome_panel_content'] : '';
	$is_default = false;

	if ( empty( $content ) ) {
		do_action( 'udb_ms_switch_blog' );

		ob_start();
		do_action( 'welcome_panel' );
		$content = ob_get_clean();

		do_action( 'udb_ms_restore_blog' );

		$is_default = true;
	}

	$content = trim( $content );

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

	if ( $is_default ) {
		$args['tinymce']['setup'] = 'function(editor){editor.on("input keyup paste ExecCommand",function(){var f=document.getElementById("udb-welcome-panel-is-default");if(f){f.value="0";}});}';
	}

	wp_editor( $content, $editor_id, $args );

	if ( $is_default ) {
		/*
		 * The editor shows WordPress' default welcome panel until it's edited.
		 * The flag tells the save routine to keep using the default.
		 * Any edit clears it: in the Visual tab (TinyMCE setup above) and in the Text tab (below).
		 */
		echo '<input type="hidden" id="udb-welcome-panel-is-default" name="udb_settings[welcome_panel_is_default]" value="1">';
		?>
		<script>
		( function () {
			var flag = document.getElementById( 'udb-welcome-panel-is-default' );
			var textarea = document.getElementById( <?php echo wp_json_encode( $editor_id ); ?> );
			var toolbar = document.getElementById( <?php echo wp_json_encode( 'qt_' . $editor_id . '_toolbar' ); ?> );
			var markEdited = function () { flag.value = '0'; };

			if ( textarea ) {
				textarea.addEventListener( 'input', markEdited );
				textarea.addEventListener( 'change', markEdited );
			}

			// Text tab buttons (b, i, link …) change the text without an input event.
			if ( toolbar ) {
				toolbar.addEventListener( 'click', markEdited );
			}
		}() );
		</script>
		<?php
	}

};
