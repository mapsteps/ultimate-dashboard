<?php
/**
 * Settings.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Register setting.
 */
function udb_tools() {

	// Register settings.
	register_setting( 'udb-export-group', 'udb_export', array( 'sanitize_callback' => 'udb_process_export' ) );
	register_setting( 'udb-import-group', 'udb_import', array( 'sanitize_callback' => 'udb_process_import' ) );

	// Settings sections.
	add_settings_section( 'udb-export-section', __( 'Export Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard-export' );
	add_settings_section( 'udb-import-section', __( 'Import Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard-import' );

	// Settings fields.
	add_settings_field( 'udb-export-field', '', 'udb_render_export_field', 'ultimate-dashboard-export', 'udb-export-section', array( 'class' => 'is-gapless has-small-text' ) );
	add_settings_field( 'udb-import-field', '', 'udb_render_import_field', 'ultimate-dashboard-import', 'udb-import-section', array( 'class' => 'is-gapless has-small-text' ) );

}
add_action( 'admin_init', 'udb_tools' );

/**
 * Render export field.
 *
 * @param array $args setting's argument.
 */
function udb_render_export_field( $args ) {

	?>

	<p><?php _e( 'Export & move your Dashboard Widgets to a different installation.', 'ultimate-dashboard' ); ?></p>

	<div class="fields">
		<p>
			<label>
				<input type="checkbox" name="udb_export_settings" value="1" />
				<?php _e( 'Include Settings', 'ultimate-dashboard' ); ?>
			</label>
		</p>
	</div>

	<?php

}

/**
 * Render import field.
 *
 * @param array $args setting's argument.
 */
function udb_render_import_field( $args ) {

	?>

	<p><?php _e( 'Select the JSON file you would like to import.', 'ultimate-dashboard' ); ?></p>

	<div class="fields-wrapper has-top-gap">
		<label class="block-label" for="udb_import_file"><?php _e( 'Select File', 'ultimate-dashboard' ); ?></label>
		<input type="file" name="udb_import_file">
	</div>

	<?php

}

/**
 * Export processing.
 *
 * @return void
 */
function udb_process_export() {

	$settings          = array();
	$branding_settings = array();
	$login_settings    = array();

	if ( isset( $_POST['udb_export_settings'] ) && $_POST['udb_export_settings'] ) {
		$settings          = get_option( 'udb_settings' );
		$branding_settings = get_option( 'udb_branding' );
		$login_settings    = get_option( 'udb_login' );
	}

	$widgets = get_posts(
		array(
			'post_type'   => 'udb_widgets',
			'numberposts' => -1,
		)
	);
	$widgets = $widgets ? $widgets : array();

	foreach ( $widgets as &$widget ) {

		unset(
			$widget->to_ping,
			$widget->pinged,
			$widget->guid,
			$widget->filter,
			$widget->ancestors,
			$widget->page_template,
			$widget->post_category,
			$widget->tags_input,
			$widget->post_type
		);

		$widget->meta = array(
			'udb_widget_type'    => get_post_meta( $widget->ID, 'udb_widget_type', true ),
			'udb_link'           => get_post_meta( $widget->ID, 'udb_link', true ),
			'udb_link_target'    => get_post_meta( $widget->ID, 'udb_link_target', true ),
			'udb_icon_key'       => get_post_meta( $widget->ID, 'udb_icon_key', true ),
			'udb_position_key'   => get_post_meta( $widget->ID, 'udb_position_key', true ),
			'udb_priority_key'   => get_post_meta( $widget->ID, 'udb_priority_key', true ),
			'udb_tooltip'        => get_post_meta( $widget->ID, 'udb_tooltip', true ),
			'udb_content'        => get_post_meta( $widget->ID, 'udb_content', true ),
			'udb_content_height' => get_post_meta( $widget->ID, 'udb_content_height', true ),
		);

	}

	$admin_pages = get_posts(
		array(
			'post_type'   => 'udb_admin_page',
			'numberposts' => -1,
		)
	);
	$admin_pages = $admin_pages ? $admin_pages : array();

	foreach ( $admin_pages as &$admin_page ) {
		$meta = get_post_meta( $admin_page->ID );

		foreach ( $meta as $meta_key => $meta_value ) {
			$admin_page->meta = count( $meta_value ) > 1 ? $meta_value : $meta_value[0];
		}
	}

	header( 'Content-disposition: attachment; filename=udb-export-' . date( 'Y-m-d-H.i.s', strtotime( 'now' ) ) . '.json' );
	header( 'Content-type: application/json' );

	echo wp_json_encode(
		array(
			'widgets'           => $widgets,
			'settings'          => $settings,
			'branding_settings' => $branding_settings,
			'login_settings'    => $login_settings,
			'admin_pages'       => $admin_pages,
		)
	);

	exit;

}

/**
 * Import processing.
 *
 * @return void
 */
function udb_process_import() {

	$import_file = $_FILES['udb_import_file'];
	$file_name   = basename( sanitize_file_name( wp_unslash( $import_file['name'] ) ) );
	$explodes    = explode( '.', $file_name );
	$ext         = end( $explodes );

	// wp_check_filetype fails here, so let's check it manually.
	if ( 'json' !== $ext ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Please upload a valid .json file', 'ultimate-dashboard' )
		);

		return;

	}

	$tmp_file = $import_file['tmp_name'];

	if ( empty( $tmp_file ) ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Please upload a file to import', 'ultimate-dashboard' )
		);

		return;

	}

	$imports = file_get_contents( $tmp_file, true );
	$imports = (array) json_decode( $imports, true );

	// Retrieve settings & widgets.
	$settings          = isset( $imports['settings'] ) ? $imports['settings'] : array();
	$branding_settings = isset( $imports['branding_settings'] ) ? $imports['branding_settings'] : array();
	$login_settings    = isset( $imports['login_settings'] ) ? $imports['login_settings'] : array();
	$widgets           = isset( $imports['widgets'] ) ? $imports['widgets'] : array();
	$admin_pages       = isset( $imports['admin_pages'] ) ? $imports['admin_pages'] : array();

	if ( ! $imports && ! $widgets && ! $admin_pages ) {

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Your import is empty', 'ultimate-dashboard' )
		);

		return;

	}

	if ( $settings || $branding_settings || $login_settings ) {

		if ( $settings ) {
			update_option( 'udb_settings', $settings );
		}

		if ( $branding_settings ) {
			update_option( 'udb_branding', $branding_settings );
		}

		if ( $login_settings ) {
			update_option( 'udb_login', $login_settings );
		}

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Settings imported', 'ultimate-dashboard' ),
			'updated'
		);

	}

	if ( $widgets ) {

		foreach ( $widgets as $widget ) {

			$widget['post_type'] = 'udb_widgets';

			$post = get_page_by_path( $widget['post_name'], OBJECT, 'udb_widgets' );
			$meta = $widget['meta'];

			unset( $widget['meta'] );

			if ( $post ) {

				$post_id      = $post->ID;
				$widget['ID'] = $post->ID;

				wp_update_post( $widget );

			} else {

				unset( $widget['ID'] );

				$post_id = wp_insert_post( $widget );

			}

			foreach ( $meta as $meta_key => $meta_value ) {
				update_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Widgets imported', 'ultimate-dashboard' ),
			'updated'
		);

	}

	if ( $admin_pages ) {

		foreach ( $admin_pages as $admin_page ) {
			$post = get_page_by_path( $admin_page['post_name'], OBJECT, 'udb_admin_page' );
			$meta = $admin_page['meta'];

			unset( $admin_page['meta'] );

			// if post exists.
			if ( $post ) {
				$post_id          = $post->ID;
				$admin_page['ID'] = $post->ID;

				wp_update_post( $admin_page );
			} else {
				unset( $admin_page['ID'] );

				$post_id = wp_insert_post( $admin_page );
			}

			foreach ( $meta as $meta_key => $meta_value ) {
				update_post_meta( $post_id, $meta_key, $meta_value );
			}
		}

		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Admin pages imported', 'ultimate-dashboard' ),
			'updated'
		);

	}

}
