<?php
/**
 * HTML Widget
 *
 * @package Ultimate Dashboard
 */
 
// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function udb_html_widget() { ?>

	<?php global $post; ?>

	<div class="udb-metabox-wrapper udb-html-wrapper">

		<div class="udb-metabox-section">

			<h3><?php _e( 'HTML', 'ultimate-dashboard' ); ?></h3>

			<?php

			$content = get_post_meta( $post->ID, 'udb_html', true );
			$editor = 'udb_html';
			$settings = array(
				'media_buttons' => true,
				'editor_height' => 300,
				'tinymce' => false,
			);
			wp_editor( $content, $editor, $settings );

			?>

		</div>

	</div>

<?php }

add_action( 'udb_metabox_widgets', 'udb_html_widget' );