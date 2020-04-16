<?php
/**
 * Icon widget.
 *
 * @package Ultimate Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

function udb_icon_widget() {

	global $post;

	?>

	<div data-type="icon">

		<div class="subbox">
			<h2><?php _e( 'Icon', 'utimate-dashboard' ); ?></h2>

			<?php

			$stored_meta = get_post_meta( $post->ID, 'udb_icon_key', true );
			$dashicons   = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'assets/json/dashicons.json' );
			$dashicons   = json_decode( $dashicons, true );
			$dashicons   = $dashicons ? $dashicons : array();
			$fontawesome = file_get_contents( ULTIMATE_DASHBOARD_PLUGIN_DIR . 'assets/json/fontawesome4.json' );
			$fontawesome = json_decode( $fontawesome, true );
			$fontawesome = $fontawesome ? $fontawesome : array();
			$udb_icons   = array();
			$selected    = array(
				'id'   => 'dashicons dashicons-menu',
				'text' => 'Menu',
			);

			// loop over dashicons.
			foreach ( $dashicons as $icon_category => $icons ) {

				$category_name = str_ireplace( '_', ' ', $icon_category );
				$category_name = ucwords( $category_name );
				$category_name = 'Dashicons: ' . $category_name;
				$icons         = $icons && is_array( $icons ) ? $icons : array();

				$items = array();

				foreach ( $icons as $icon_class ) {

					$icon_name = explode( ' ', $icon_class );
					$icon_name = $icon_name[1];
					$splits    = explode( '-', $icon_name );
					$icon_name = str_ireplace( $splits[0] . '-', '', $icon_name );
					$icon_name = str_ireplace( '-', ' ', $icon_name );
					$icon_name = ucwords( $icon_name ) . ' (<code style="font-size:10px">' . $icon_class . '</code>)';
					$icon_text = '<i class="' . $icon_class . '"></i> ' . $icon_name;

					if ( $icon_class === $stored_meta ) {

						$selected = array(
							'id'   => $icon_class,
							'text' => $icon_text,
						);

					}

					array_push(
						$items,
						array(
							'id'   => $icon_class,
							'text' => $icon_text,
						)
					);

				}

				array_push(
					$udb_icons,
					array(
						'text'     => $category_name,
						'children' => $items,
					)
				);

			}

			// Loop over FontAwesome.
			foreach ( $fontawesome as $icon_category => $icons ) {

				$category_name = str_ireplace( '_', ' ', $icon_category );
				$category_name = ucwords( $category_name );
				$category_name = 'Font Awesome 4: ' . $category_name;
				$icons         = $icons && is_array( $icons ) ? $icons : array();

				$items = array();

				foreach ( $icons as $icon_class ) {

					$icon_name = explode( ' ', $icon_class );
					$icon_name = $icon_name[1];
					$splits    = explode( '-', $icon_name );
					$icon_name = str_ireplace( $splits[0] . '-', '', $icon_name );
					$icon_name = str_ireplace( '-', ' ', $icon_name );
					$icon_name = ucwords( $icon_name ) . ' (<code style="font-size:10px">' . $icon_class . '</code>)';
					$icon_text = '<i class="' . $icon_class . '"></i> ' . $icon_name;

					if ( $icon_class === $stored_meta ) {

						$selected = array(
							'id'   => $icon_class,
							'text' => $icon_text,
						);

					}

					array_push(
						$items,
						array(
							'id'   => $icon_class,
							'text' => $icon_text,
						)
					);

				}

				array_push(
					$udb_icons,
					array(
						'text'     => $category_name,
						'children' => $items,
					)
				);

			}

			wp_localize_script(
				'udb-edit-widget',
				'udbIcons',
				array(
					'icons'    => $udb_icons,
					'selected' => $selected,
				)
			);

			?>

			<div class="field">
				<div class="input-control">
					<div class="icon-preview"></div>
				</div>
			</div>
			<div class="field">
				<div class="label-control">
					<label for="udb_link_target"><?php _e( 'Select Icon', 'ultimate-dashboard' ); ?></label>
				</div>
				<div class="input-control">
					<select name="udb_icon" id="udb-icon">
						<?php if ( $stored_meta ) { ?>
							<option value="<?php echo esc_attr( $stored_meta ); ?>" selected><?php echo esc_html( $stored_meta ); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>

		<div class="subbox">
			<h2><?php _e( 'Tooltip', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_tooltip', true );
				?>
				<div class="input-control">
					<textarea style="width: 100%; height: 100px;" id="udb-tooltip" name="udb_tooltip"><?php echo esc_html( $stored_meta ? $stored_meta : '' ); ?></textarea>
				</div>
			</div>
		</div>

		<div class="subbox">
			<h2><?php _e( 'Link', 'utimate-dashboard' ); ?></h2>
			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_link', true );
				?>
				<div class="label-control">
					<p class="description"><?php _e( "Absolute URL's (incl. http:// or https://) or relative URL's (./post-new.php) are allowed.", 'ultimate-dashboard' ); ?></p>
				</div>
				<div class="input-control">
					<input id="udb_link" type="text" name="udb_link" value="<?php echo esc_attr( $stored_meta ? $stored_meta : '' ); ?>">
				</div>
			</div>

			<div class="field">
				<?php
				$stored_meta = get_post_meta( $post->ID, 'udb_link_target', true );
				?>
				<div class="label-control">
					<label for="udb_link_target"><?php _e( 'Target', 'ultimate-dashboard' ); ?></label>
				</div>
				<div class="input-control">
					<label>
						<input id="udb_link_target" type="checkbox" name="udb_link_target" <?php checked( $stored_meta, '_blank' ); ?>>
						<span class="message"><?php _e( 'Open link in a new tab.', 'ultimate-dashboard' ); ?></span>
					</label>
				</div>
			</div>
		</div>

	</div>

	<?php

}
add_action( 'udb_metabox_widgets', 'udb_icon_widget' );
