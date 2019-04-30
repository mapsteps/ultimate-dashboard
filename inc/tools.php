<?php
/**
 * Settings
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Settings
 */
add_action( 'admin_init', 'udb_tools' );

/**
 * Register setting
 */
function udb_tools() {
	// Register settings.
	register_setting( 'udb-export-group', 'udb_export', [ 'sanitize_callback' => 'udb_process_export' ] );
	register_setting( 'udb-import-group', 'udb_import', [ 'sanitize_callback' => 'udb_process_import' ] );

	// Settings sections.
	add_settings_section( 'udb-export-section', __( 'Export Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard-export' );
	add_settings_section( 'udb-import-section', __( 'Import Widgets', 'ultimate-dashboard' ), '', 'ultimate-dashboard-import' );

	// Settings fields.
	add_settings_field( 'udb-export-field', '', 'udb_render_export_field', 'ultimate-dashboard-export', 'udb-export-section', [ 'class' => 'is-gapless has-small-text' ] );
	add_settings_field( 'udb-import-field', '', 'udb_render_import_field', 'ultimate-dashboard-import', 'udb-import-section', [ 'class' => 'is-gapless has-small-text' ] );
}

/**
 * Render export field
 *
 * @param array $args Setting's argument.
 */
function udb_render_export_field( $args ) {
	?>
	<p><?php esc_html_e( 'Export & move your Dashboard Widgets to a different installation.', 'ultimate-dashboard' ); ?></p>

	<div class="fields">
		<p>
			<label>
				<input type="checkbox" name="udb_export_settings" value="1" />
				<?php esc_html_e( 'Include settings' ); ?>
			</label>
		</p>
	</div>
	<?php
}

/**
 * Render import field
 *
 * @param array $args Setting's argument.
 */
function udb_render_import_field( $args ) {
	?>
	<p><?php esc_html_e( 'Select the JSON file you would like to import.', 'ultimate-dashboard' ); ?></p>

	<div class="fields-wrapper has-top-gap">
		<label class="block-label" for="udb_import_file"><?php esc_html_e( 'Select File', 'ultimate-dashboard' ); ?></label>
		<input type="file" name="udb_import_file">
	</div>
	<?php
}

/**
 * Export processing
 *
 * @return void
 */
function udb_process_export() {
	$settings = [];

	if ( isset( $_POST['udb_export_settings'] ) && $_POST['udb_export_settings'] ) {
		$settings = get_option( 'udb_settings' );
	}

	$widgets = get_posts(
		[
			'post_type'   => 'udb_widgets',
			'numberposts' => -1,
		]
	);

	if ( $widgets ) {
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

			$widget->meta = [
				'udb_widget_type'    => get_post_meta( $widget->ID, 'udb_widget_type', true ),
				'udb_link'           => get_post_meta( $widget->ID, 'udb_link', true ),
				'udb_link_target'    => get_post_meta( $widget->ID, 'udb_link_target', true ),
				'udb_icon_key'       => get_post_meta( $widget->ID, 'udb_icon_key', true ),
				'udb_position_key'   => get_post_meta( $widget->ID, 'udb_position_key', true ),
				'udb_priority_key'   => get_post_meta( $widget->ID, 'udb_priority_key', true ),
				'udb_tooltip'        => get_post_meta( $widget->ID, 'udb_tooltip', true ),
				'udb_content'        => get_post_meta( $widget->ID, 'udb_content', true ),
				'udb_content_height' => get_post_meta( $widget->ID, 'udb_content_height', true ),
			];
		}
	}

	header( 'Content-disposition: attachment; filename=udb-export-' . date( 'Y-m-d-H.i.s', strtotime( 'now' ) ) . '.json' );
	header( 'Content-type: application/json' );
	echo wp_json_encode(
		[
			'widgets'  => $widgets,
			'settings' => $settings,
		]
	);
	exit;
}

/**
 * Import processing
 *
 * @return void
 */
function udb_process_import() {
	$import_file = $_FILES['udb_import_file'];
	$file_name   = basename( sanitize_file_name( wp_unslash( $import_file['name'] ) ) );
	$explodes    = explode( '.', $file_name );
	$ext         = end( $explodes );

	// wp_check_filetype is failed here, so check it manually.
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

	// phpcs:ignore -- im just fetching internal tmp_file, not url.
	$imports = file_get_contents( $tmp_file, true );
	$imports = (array) json_decode( $imports, true );

	// Retrieve settings & widgets.
	$settings = isset( $imports['settings'] ) ? $imports['settings'] : [];
	$widgets  = isset( $imports['widgets'] ) ? $imports['widgets'] : [];

	if ( ! $imports && ! $widgets ) {
		add_settings_error(
			'udb_export',
			esc_attr( 'udb-import' ),
			__( 'Your import is empty', 'ultimate-dashboard' )
		);
		return;
	}

	if ( $settings ) {
		update_option( 'udb_settings', $settings );

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

			// if post exists.
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
}
