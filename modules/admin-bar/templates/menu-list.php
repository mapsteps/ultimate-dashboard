<?php
/**
 * Menu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-bar--menu-item" data-default-parent="{default_menu_parent}" data-added="{menu_was_added}"  data-hidden="{menu_is_hidden}" data-default-id="{default_menu_id}" data-default-href="{default_menu_href}" data-default-group="{default_menu_group}" data-menu-id="{menu_id}">
	<div class="udb-admin-bar--control-panel">
		<div class="udb-admin-bar--menu-drag">
			<span></span>
		</div>
		<div class="udb-admin-bar--menu-icon">
			{render_menu_icon}
		</div>
		<div class="udb-admin-bar--menu-name">
			{parsed_menu_title}
		</div>
		<div class="udb-admin-bar--menu-actions">
			{frontend_only_indicator}
			{group_indicator}
			{trash_icon}
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
		</div>
		<div class="udb-admin-bar--expand-menu">
			<span class="dashicons dashicons-arrow-down-alt2">
			</span>
		</div>
	</div><!-- .udb-admin-bar--control-panel -->

	<div class="udb-admin-bar--expanded-panel">

		<div class="udb-admin-bar--tabs udb-admin-bar--menu-item-tabs">

			<ul class="udb-admin-bar--tab-menu">
				<li class="udb-admin-bar--tab-menu-item is-active" data-udb-tab-content="udb-admin-bar--settings-tab--{default_menu_id}">
					<button type="button">
						<?php _e( 'Settings', 'ultimate-dashboard' ); ?>
					</button>
				</li>
				<li class="udb-admin-bar--tab-menu-item" data-udb-tab-content="udb-admin-bar--submenu-tab--{default_menu_id}">
					<button type="button">
						<?php _e( 'Submenu', 'ultimate-dashboard' ); ?>
					</button>
				</li>
			</ul><!-- .udb-admin-bar--tab-menu -->

			<div class="udb-admin-bar--tab-content">
				<div id="udb-admin-bar--settings-tab--{default_menu_id}" class="udb-admin-bar--tab-content-item is-active">
					<div class="udb-admin-bar--fields">
						<div class="field {menu_title_field_is_hidden}">
							<label for="menu_title_{default_menu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Menu Title' ); ?>
							</label>
							<div class="control">
								<textarea 
									row="1" 
									name="menu_title_{default_menu_id}" 
									id="menu_title_{default_menu_id}"
									class="udb-admin-bar--text-field"
									data-name="menu_title"
									placeholder="{encoded_default_menu_title}"
									{menu_title_is_disabled}
								>{menu_title}</textarea>
							</div>
						</div>
						<div class="field {menu_href_field_is_hidden}">
							<label for="menu_href_{default_menu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Menu URL' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="menu_href_{default_menu_id}" 
									id="menu_href_{default_menu_id}" 
									value="{menu_href}" 
									placeholder="{default_menu_href}" 
									class="udb-admin-bar--text-field"
									data-name="menu_href"
									{menu_href_is_disabled}
								>
							</div>
						</div>

						<!--
						These fields are not being used currently.
						But leave it here because in the future, if requested, it would be used for
						"hide menu item for specific role(s) / user(s)" functionality.
						-->
						<!--
						<div class="field">
							<label for="disallowed_roles_{default_menu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Hide from specific role(s):' ); ?>
							</label>
							<div class="control">
								<select
									name="disallowed_roles_{default_menu_id}" 
									id="disallowed_roles_{default_menu_id}" 
									class="udb-admin-bar--select-field udb-admin-bar--select2-field udb-admin-bar--roles-select2-field"
									data-placeholder="<?php _e( 'Select a role' ); ?>"
									data-name="disallowed_roles"
									data-disallowed-roles="{disallowed_roles}"
									multiple
								>
								</select>
							</div>
						</div>
						<div class="field">
							<label for="disallowed_users_{default_menu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Hide from specific user(s):' ); ?>
							</label>
							<div class="control">
								<select
									name="disallowed_users_{default_menu_id}" 
									id="disallowed_users_{default_menu_id}" 
									class="udb-admin-bar--select-field udb-admin-bar--select2-field udb-admin-bar--users-select2-field"
									data-placeholder="<?php _e( 'Select a user' ); ?>"
									data-name="disallowed_users"
									data-disallowed-users="{disallowed_users}"
									multiple
								>
								</select>
							</div>
						</div>
						-->

						<div class="field {menu_icon_field_is_hidden}">
							<label for="menu_icon_{default_menu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Menu Icon', 'ultimate-dashboard' ); ?>
							</label>
							<div class="control">
								<input type="text" class="udb-admin-bar--text-field udb-admin-bar--icon-field dashicons-picker" data-width="100%" name="menu_icon_{default_menu_id}" id="menu_icon_{default_menu_id}" value="{menu_icon}" placeholder="<?php _e( 'Choose an icon', 'ultimate-dashboard' ); ?>" data-name="menu_icon" {menu_icon_is_disabled} />
							</div>
						</div>

						{empty_menu_settings_text}
					</div><!-- .udb-admin-bar--fields -->
				</div><!-- #udb-admin-bar--settings-tab -->
				<div id="udb-admin-bar--submenu-tab--{default_menu_id}" class="udb-admin-bar--tab-content-item udb-admin-bar--edit-area">
					<ul class="udb-admin-bar--menu-list udb-admin-bar--submenu-list" data-menu-type="submenu" data-submenu-level="1">
						{submenu_template}
					</ul>

					<?php do_action( 'udb_admin_bar_add_submenu_button' ); ?>
				</div><!-- #udb-admin-bar--submenu-tab -->
			</div><!-- .udb-admin-bar--tab-content -->

		</div><!-- .udb-admin-bar--tabs -->

	</div><!-- .udb-admin-bar--expanded-panel -->
</li>

<?php
return ob_get_clean();
