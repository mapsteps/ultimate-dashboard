<?php
/**
 * Setup admin page meta boxes.
 *
 * @package Ultimate Dashboard PRO
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Register metaboxes.
 */
function udb_admin_page_metaboxes() {
	add_meta_box( 'udb-admin-page-status-metabox', __( 'Active Status', 'ultimatedashboard' ), 'udb_admin_page_active_status_metabox_callback', 'udb_admin_page', 'side', 'high' );
	add_meta_box( 'udb-admin-page-content-type-metabox', __( 'Content Type', 'ultimatedashboard' ), 'udb_admin_page_content_type_metabox_callback', 'udb_admin_page', 'side', 'high' );

	add_meta_box( 'udb-admin-page-menu-metabox', __( 'Menu Attributes', 'ultimatedashboard' ), 'udb_admin_page_menu_metabox_callback', 'udb_admin_page', 'side' );
	add_meta_box( 'udb-admin-page-roles-metabox', __( 'User Role Access', 'ultimatedashboard' ), 'udb_admin_page_roles_metabox_callback', 'udb_admin_page', 'side' );

	add_meta_box( 'udb-admin-page-html-metabox', __( 'HTML', 'ultimatedashboard' ), 'udb_admin_page_html_metabox_callback', 'udb_admin_page', 'normal', 'high' );
	add_meta_box( 'udb-admin-page-display-metabox', __( 'Display Options', 'ultimatedashboard' ), 'udb_admin_page_display_metabox_callback', 'udb_admin_page', 'normal' );
	add_meta_box( 'udb-admin-page-advanced-metabox', __( 'Advanced', 'ultimatedashboard' ), 'udb_admin_page_advanced_metabox_callback', 'udb_admin_page', 'normal' );
}
add_action( 'add_meta_boxes', 'udb_admin_page_metaboxes' );

/**
 * "Content Type" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_admin_page_content_type_metabox_callback( $post ) {
	$content_type = get_post_meta( $post->ID, 'udb_content_type', true );
	$content_type = $content_type ? $content_type : 'builder';
	?>

	<div class="postbox-content">
		<div class="field">
			<div class="control">
				<select name="udb_content_type" id="udb_content_type" class="is-full">
					<option value="builder" <?php selected( $content_type, 'builder' ); ?>><?php _e( 'Default Editor', 'ultimatedashboard' ); ?></option>
					<option value="html" <?php selected( $content_type, 'html' ); ?>><?php _e( 'HTML Editor', 'ultimatedashboard' ); ?></option>
				</select>
			</div>
		</div>
	</div>

	<?php
}

/**
 * "HTML" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_admin_page_html_metabox_callback( $post ) {
	$html_content = get_post_meta( $post->ID, 'udb_html_content', true );
	?>

	<div class="postbox-content">
		<div class="field html-editor-field">
			<div class="control">
				<textarea class="widefat textarea udb-html-editor" name="udb_html_content"><?php echo wp_unslash( $html_content ); ?></textarea>
			</div>
		</div>
	</div>

	<?php
}

/**
 * "Active Status" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_admin_page_active_status_metabox_callback( $post ) {

	$is_active = (int) get_post_meta( $post->ID, 'udb_is_active', true );

	global $current_screen;

	// If this is adding a new post.
	if ( 'add' === $current_screen->action ) {
		$is_active = 1;
	}

	?>

	<div class="postbox-content">
		<?php wp_nonce_field( 'udb_edit_admin_page', 'udb_nonce' ); ?>
		<div class="fields">
			<div class="field">
				<div class="control switch-control is-rounded is-small">
					<label for="udb_is_active">
						<input type="checkbox" name="udb_is_active" id="udb_is_active" value="1" <?php checked( $is_active, 1 ); ?>>
						<span class="switch"></span>
					</label>
				</div>
			</div>
			<p class="description">
				<?php _e( 'This page will only be shown to the admin menu if the status is active.', 'ultimatedashboard' ); ?>
			</p>
		</div>
	</div>

	<?php

}

/**
 * "Menu Attributes" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_admin_page_menu_metabox_callback( $post ) {

	$menu_type   = get_post_meta( $post->ID, 'udb_menu_type', true );
	$menu_parent = get_post_meta( $post->ID, 'udb_menu_parent', true );
	$menu_order  = get_post_meta( $post->ID, 'udb_menu_order', true );
	$menu_order  = $menu_order ? absint( $menu_order ) : 10;
	$menu_icon   = get_post_meta( $post->ID, 'udb_menu_icon', true );

	$admin_menu = $GLOBALS['menu'];

	?>

	<div class="postbox-content has-lines">
		<div class="fields">
			<div class="field">
				<label class="label" for="udb_menu_type"><?php _e( 'Menu Type', 'ultimatedashboard' ); ?></label>
				<div class="control">
					<select name="udb_menu_type" id="udb_menu_type" class="is-full">
						<option value="parent" <?php selected( $menu_type, 'parent' ); ?>>
							<?php _e( 'Top-level Menu', 'ultimatedashboard' ); ?>
						</option>
						<option value="submenu" <?php selected( $menu_type, 'submenu' ); ?>>
							<?php _e( 'Submenu', 'ultimatedashboard' ); ?>
						</option>
					</select>
				</div>
			</div>

			<div class="field" data-show-if-field="udb_menu_type" data-show-if-value="submenu">
				<label class="label" for="udb_menu_parent"><?php _e( 'Parent Menu', 'ultimatedashboard' ); ?></label>
				<div class="control">
					<select name="udb_menu_parent" id="udb_menu_parent" class="is-full">
						<?php foreach ( $admin_menu as $menu ) : ?>
							<?php if ( ! empty( $menu[0] ) ) : ?>
								<option value="<?php echo esc_attr( $menu[2] ); ?>" <?php selected( $menu_parent, $menu[2] ); ?>>
									<?php echo udb_strip_tags_content( $menu[0] ); ?>
								</option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="field">
				<label class="label" for="udb_menu_order"><?php _e( 'Menu Order', 'ultimatedashboard' ); ?></label>
				<div class="control">
					<input type="number" name="udb_menu_order" id="udb_menu_order" class="is-full" value="<?php echo esc_attr( $menu_order ); ?>" min="0" step="1">
				</div>
			</div>

			<?php require __DIR__ . '/icon-selector.php'; ?>
		</div>
	</div>

	<?php

}

/**
 * "Display Options" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_admin_page_display_metabox_callback( $post ) {

	$remove_page_title    = (int) get_post_meta( $post->ID, 'udb_remove_page_title', true );
	$remove_page_margin   = (int) get_post_meta( $post->ID, 'udb_remove_page_margin', true );
	$remove_admin_notices = (int) get_post_meta( $post->ID, 'udb_remove_admin_notices', true );

	?>

	<div class="postbox-content has-lines">
		<div class="fields">
			<div class="field">
				<div class="control">
					<label for="udb_remove_page_title" class="label checkbox-label">
						<input type="checkbox" name="udb_remove_page_title" id="udb_remove_page_title" value="1" <?php checked( $remove_page_title, 1 ); ?>>
						<?php _e( 'Remove Page Title', 'ultimatedashboard' ); ?>
					</label>
					<p class="description">
						<?php _e( 'Remove the page title from the top of the page to make it looks good.', 'ultimatedashboard' ); ?>
					</p>
				</div>
			</div>

			<div class="field">
				<div class="control">
					<label for="udb_remove_page_margin" class="label checkbox-label">
						<input type="checkbox" name="udb_remove_page_margin" id="udb_remove_page_margin" value="1" <?php checked( $remove_page_margin, 1 ); ?>>
						<?php _e( 'Remove Page Margin', 'ultimatedashboard' ); ?>
					</label>
					<p class="description">
						<?php _e( 'Remove the default margins of admin page to make it full.', 'ultimatedashboard' ); ?>
					</p>
				</div>
			</div>

			<div class="field">
				<div class="control">
					<label for="udb_remove_admin_notices" class="label checkbox-label">
						<input type="checkbox" name="udb_remove_admin_notices" id="udb_remove_admin_notices" value="1" <?php checked( $remove_admin_notices, 1 ); ?>>
						<?php _e( 'Remove Admin Notices', 'ultimatedashboard' ); ?>
					</label>
					<p class="description">
						<?php _e( 'Remove the admin notices (if any) from the page to make it cleaner.', 'ultimatedashboard' ); ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<?php
}

/**
 * "User Role Access" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_admin_page_roles_metabox_callback( $post ) {

	$allowed_roles = get_post_meta( $post->ID, 'udb_allowed_roles', true );
	$allowed_roles = '' === $allowed_roles ? array( 'all' ) : $allowed_roles;

	$roles_obj = new \WP_Roles();
	$roles     = $roles_obj->role_names;
	?>

	<div class="postbox-content has-lines">
		<div class="fields">
			<div class="field">
				<label class="label">
					<?php _e( 'Allow these roles to access the page', 'ultimatedashboard' ); ?>
				</label>
				<div class="control role-picker-control">
					<select class="udb-widget-roles-field" name="udb_allowed_roles[]" multiple>

						<option value="all"  <?php echo esc_attr( in_array( 'all', $allowed_roles, true ) ? 'selected' : '' ); ?>>
							<?php _e( 'All', 'ultimatedashboard' ); ?>
						</option>

						<?php foreach ( $roles as $role_key => $role_name ) : ?>
							<?php
							$selected_attr = '';
							$selected_attr = in_array( $role_key, $allowed_roles, true ) ? 'selected' : '';
							?>
							<option value="<?php echo esc_attr( $role_key ); ?>" <?php echo esc_attr( $selected_attr ); ?>>
								<?php echo esc_attr( $role_name ); ?>
							</option>
						<?php endforeach; ?>

					</select>
				</div>
			</div>
		</div>
	</div>

	<?php
}

/**
 * "Advanced" metabox callback.
 *
 * @param object $post The post object.
 */
function udb_admin_page_advanced_metabox_callback( $post ) {

	$custom_css = get_post_meta( $post->ID, 'udb_custom_css', true );
	$custom_js  = get_post_meta( $post->ID, 'udb_custom_js', true );
	?>

	<div class="postbox-content has-lines">
		<div class="fields">
			<div class="field">
				<label class="label" for="udb_custom_css">
					<?php _e( 'Custom CSS', 'ultimatedashboard' ); ?>
				</label>
				<p class="description">
					<?php _e( 'You can provide custom CSS for the generated page. Please be careful not to break the styles of admin panel.', 'ultimatedashboard' ); ?>
				</p>
				<div class="control">
					<textarea id="udb_custom_css" class="widefat textarea udb-custom-css" name="udb_custom_css"><?php echo wp_unslash( $custom_css ); ?></textarea>
				</div>
			</div>

			<div class="field">
				<label class="label" for="udb_custom_js">
					<?php _e( 'Custom JS', 'ultimatedashboard' ); ?>
				</label>
				<p class="description">
					<?php _e( 'You can provide custom JavaScript for the generated page. Please be careful!', 'ultimatedashboard' ); ?>
				</p>
				<div class="control">
					<textarea id="udb_custom_js" class="widefat textarea udb-custom-js" name="udb_custom_js"><?php echo wp_unslash( $custom_js ); ?></textarea>
				</div>
			</div>
		</div>
	</div>

	<?php
}

/**
 * Save postmeta data.
 *
 * @param int $post_id The post ID.
 */
function udb_admin_page_save_postmeta( $post_id ) {
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( 'udb_admin_page' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['udb_nonce'] ) || ! wp_verify_nonce( $_POST['udb_nonce'], 'udb_edit_admin_page' )) {
		return;
	}

	// Active status.
	if ( isset( $_POST['udb_is_active'] ) ) {
		update_post_meta( $post_id, 'udb_is_active', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_is_active' );
	}

	// Content type.
	if ( isset( $_POST['udb_content_type'] ) ) {
		update_post_meta( $post_id, 'udb_content_type', sanitize_text_field( $_POST['udb_content_type'] ) );
	}

	// HTML content.
	if ( isset( $_POST['udb_html_content'] ) ) {
		update_post_meta( $post_id, 'udb_html_content', $_POST['udb_html_content'] );
	}

	// Menu type.
	if ( isset( $_POST['udb_menu_type'] ) ) {
		update_post_meta( $post_id, 'udb_menu_type', sanitize_text_field( $_POST['udb_menu_type'] ) );
	}

	// Menu parent.
	if ( isset( $_POST['udb_menu_parent'] ) ) {
		update_post_meta( $post_id, 'udb_menu_parent', sanitize_text_field( $_POST['udb_menu_parent'] ) );
	}

	// Menu order.
	if ( isset( $_POST['udb_menu_order'] ) ) {
		update_post_meta( $post_id, 'udb_menu_order', sanitize_text_field( $_POST['udb_menu_order'] ) );
	}

	// Menu icon.
	if ( isset( $_POST['udb_menu_icon'] ) ) {
		update_post_meta( $post_id, 'udb_menu_icon', sanitize_text_field( $_POST['udb_menu_icon'] ) );
	}

	// Display page title.
	if ( isset( $_POST['udb_remove_page_title'] ) ) {
		update_post_meta( $post_id, 'udb_remove_page_title', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_remove_page_title' );
	}

	// Add page margin.
	if ( isset( $_POST['udb_remove_page_margin'] ) ) {
		update_post_meta( $post_id, 'udb_remove_page_margin', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_remove_page_margin' );
	}

	// Display admin notices.
	if ( isset( $_POST['udb_remove_admin_notices'] ) ) {
		update_post_meta( $post_id, 'udb_remove_admin_notices', 1 );
	} else {
		delete_post_meta( $post_id, 'udb_remove_admin_notices' );
	}

	// Allowed roles.
	if ( isset( $_POST['udb_allowed_roles'] ) ) {
		$allowed_roles = is_array( $_POST['udb_allowed_roles'] ) ? $_POST['udb_allowed_roles'] : array();

		foreach ( $allowed_roles as &$allowed_role ) {
			$allowed_role = sanitize_text_field( $allowed_role );
		}

		update_post_meta( $post_id, 'udb_allowed_roles', $allowed_roles );
	}

	// Custom css.
	if ( isset( $_POST['udb_custom_css'] ) ) {
		update_post_meta( $post_id, 'udb_custom_css', $_POST['udb_custom_css'] );
	}

	// Custom js.
	if ( isset( $_POST['udb_custom_js'] ) ) {
		update_post_meta( $post_id, 'udb_custom_js', $_POST['udb_custom_js'] );
	}
}
add_action( 'save_post', 'udb_admin_page_save_postmeta' );