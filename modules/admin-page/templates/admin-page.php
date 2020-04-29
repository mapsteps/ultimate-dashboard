<?php
/**
 * Ultimate Dashboard admin page.
 *
 * $post_id is brought from udb_render_admin_page($post_id);
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$editor     = udb_get_content_editor( $post_id );
$admin_page = get_post( $post_id );

$remove_page_title    = (int) get_post_meta( $post_id, 'udb_remove_page_title', true );
$remove_page_margin   = (int) get_post_meta( $post_id, 'udb_remove_page_margin', true );
$remove_admin_notices = (int) get_post_meta( $post_id, 'udb_remove_admin_notices', true );

$custom_css = get_post_meta( $post_id, 'udb_custom_css', true );
$custom_js  = get_post_meta( $post_id, 'udb_custom_js', true );
?>

<style>
	<?php if ($remove_page_margin) : ?>
		#wpcontent {
			padding-left: 0;
		}

		.wrap {
			margin: 0;
		}
	<?php endif; ?>

	<?php
	if ($custom_css) {
		echo $custom_css;
	}
	?>
</style>

<div class="wrap">
	<?php if ( ! $remove_page_title) : ?>
		<h1><?php echo esc_html( $admin_page->post_title ); ?></h1>
	<?php else : ?>
		<h1 style="display: none;"></h1>
	<?php endif; ?>

	<?php
	if ('html' === get_post_meta( $post_id, 'udb_content_type', true )) {
		echo get_post_meta( $post_id, 'udb_html_content', true );
	} else {
		echo apply_filters( 'the_content', $admin_page->post_content );
	}
	?>
</div>
