<?php
/**
 * Output
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Ultimate Dashboard Widget Output
 */
function udb_add_dashboard_widgets() {
	// Custom Post Type Loop.
	$args = array(
		'post_type'      => 'udb_widgets',
		'posts_per_page' => 100,
	);
	$loop = new WP_Query( $args );

	while ( $loop->have_posts() ) :
		$loop->the_post();

		// vars.
		$id            = get_the_ID();
		$title         = get_the_title();
		$icon          = get_post_meta( $id, 'udb_icon_key', true );
		$link          = get_post_meta( $id, 'udb_link', true );
		$target        = get_post_meta( $id, 'udb_link_target', true );
		$tooltip       = get_post_meta( $id, 'udb_tooltip', true );
		$position      = get_post_meta( $id, 'udb_position_key', true );
		$priority      = get_post_meta( $id, 'udb_priority_key', true );
		$widget_type   = get_post_meta( $id, 'udb_widget_type', true );
		$content       = get_post_meta( $id, 'udb_content', true );
		$contentheight = get_post_meta( $id, 'udb_content_height', true ) ? ' data-udb-content-height="' . get_post_meta( $id, 'udb_content_height', true ) . '"' : '';

		// preventing edge case when widget_type is empty.
		if ( ! $widget_type ) {
			do_action( 'udb_compat_widget_type', $id );
		} else {
			if ( 'text' === $widget_type ) {
				$output = do_shortcode( '<div class="udb-content-wrapper"' . $contentheight . '>' . wpautop( $content ) . '</div>' );
			} elseif ( 'icon' === $widget_type ) {
				$output = '<a href="' . $link . '" target="' . $target . '"><i class="' . $icon . '"></i></a>';

				if ( $tooltip ) {
					$output .= '<i class="udb-info"></i><div class="udb-tooltip"><span>' . $tooltip . '</span></div>';
				}
			}
		}

		$function = function() use ( $output ) {
			echo $output;
		};

		// Add Meta Box.
		add_meta_box( 'ms-udb' . $id, $title, $function, 'dashboard', $position, $priority );

	endwhile;

}
add_action( 'wp_dashboard_setup', 'udb_add_dashboard_widgets' );

/**
 * Remove Default WordPress Dashboard Widgets.
 */
function udb_remove_default_dashboard_widgets() {
	// vars.
	$saved_widgets   = udb_get_saved_default_widgets();
	$default_widgets = udb_get_default_widgets();
	$udb_settings    = get_option( 'udb_settings' );

	if ( isset( $udb_settings['remove-all'] ) ) {

		remove_action( 'welcome_panel', 'wp_welcome_panel' );

		foreach ( $default_widgets as $id => $widget ) {
			remove_meta_box( $id, 'dashboard', $widget['context'] );
		}

	} else {

		if ( isset( $udb_settings['welcome_panel'] ) ) {
			remove_action( 'welcome_panel', 'wp_welcome_panel' );
		}

		foreach ( $saved_widgets as $id => $widget ) {
			remove_meta_box( $id, 'dashboard', $widget['context'] );
		}
	}
}
add_action( 'wp_dashboard_setup', 'udb_remove_default_dashboard_widgets', 100 );

/**
 * Custom Dashboard CSS
 */
function udb_add_dashboard_css() {
	$udb_pro_settings = get_option( 'udb_pro_settings' );

	if ( ! isset( $udb_pro_settings['custom_css'] ) ) {
		return;
	}

	$custom_css = $udb_pro_settings['custom_css'];

	wp_add_inline_style( 'ultimate-dashboard-index', $custom_css );

}
add_action( 'admin_enqueue_scripts', 'udb_add_dashboard_css', 200 );
