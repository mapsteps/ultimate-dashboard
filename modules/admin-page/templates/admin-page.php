<?php
/**
 * Ultimate Dashboard admin page.
 *
 * $post is brought from udb_render_admin_page($post);
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$editor = udb_get_content_editor( $post->ID );

$remove_page_title  = $post->remove_page_title;
$remove_page_margin = $post->remove_page_margin;

$custom_css = $post->custom_css;
$custom_js  = $post->custom_js;
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
		echo $custom_css;
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
	if ( 'html' === $post->content_type ) {
		echo $post->html_content;
	} else {
		echo apply_filters( 'the_content', $post->post_content );
	}
	?>
</div>
