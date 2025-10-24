<?php
/**
 * Ultimate Dashboard admin page.
 *
 * Variables brought from "render_admin_page($post, $multisite)" function.
 * - $post
 * - $from_multisite
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Content_Helper;

$content_helper = new Content_Helper();

do_action( 'udb_ms_switch_blog' );

$editor = $content_helper->get_content_editor( $post->ID );

do_action( 'udb_ms_restore_blog' );

$remove_page_title  = $post->remove_page_title;
$remove_page_margin = $post->remove_page_margin;

$custom_css = $post->custom_css;
?>

<style>
	<?php if ( $remove_page_margin ) : ?>
	#wpcontent {
		padding-left: 0;
	}

	.wrap {
		margin: 0;
	}

	<?php endif; ?>

	<?php
	if ( $custom_css ) {
		echo $content_helper->sanitize_css( $custom_css ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
</style>

<div class="wrap">
	<?php if ( ! $remove_page_title ) : ?>
		<h1><?php echo esc_html( $post->post_title ); ?></h1>
	<?php else : ?>
		<h1 style="display: none;"></h1>
	<?php endif; ?>

	<?php
	if ( ! udb_is_pro_active() ) {
		if ( 'html' === $post->content_type ) {
			echo wp_kses_post( $post->html_content );
		} else {
			echo wp_kses_post( apply_filters( 'the_content', $post->post_content ) );
		}
	}

	do_action( 'udb_admin_page_content_output', $post, $editor, $from_multisite );
	?>
</div>
