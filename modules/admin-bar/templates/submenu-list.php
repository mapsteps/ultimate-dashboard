<?php
/**
 * Submenu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-bar--menu-item udb-admin-bar--submenu-item udb-admin-bar--submenu-item-level-{submenu_level}" data-hidden="{submenu_is_hidden}" data-added="{submenu_was_added}" data-default-href="{default_submenu_href}" data-submenu-id="{submenu_id}">
	<div class="udb-admin-bar--control-panel">
		<div class="udb-admin-bar--menu-drag">
			<span></span>
		</div>
		<div class="udb-admin-bar--menu-name">
			{parsed_submenu_title}
		</div>
		<div class="udb-admin-bar--menu-actions">
			{trash_icon}
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
		</div>
		<div class="udb-admin-bar--expand-menu">
			<span class="dashicons dashicons-arrow-down-alt2">
			</span>
		</div>
	</div><!-- .udb-admin-bar--control-panel -->

	<div class="udb-admin-bar--expanded-panel">

		<div class="udb-admin-bar--tabs udb-admin-bar--submenu-item-tabs">

			<ul class="udb-admin-bar--tab-menu">
				<li class="udb-admin-bar--tab-menu-item is-active" data-udb-tab-content="udb-admin-bar--settings-tab--{role}">
					<button type="button">
						<?php _e( 'Settings', 'ultimate-dashboard' ); ?>
					</button>
				</li>
				<li class="udb-admin-bar--tab-menu-item {submenu_tab_is_hidden}" data-udb-tab-content="udb-admin-bar--submenu-tab--{role}">
					<button type="button">
						<?php _e( 'Submenu', 'ultimate-dashboard' ); ?>
					</button>
				</li>
			</ul><!-- .udb-admin-bar--tab-menu -->

			<div class="udb-admin-bar--tab-content">
				<div id="udb-admin-bar--settings-tab--{role}" class="udb-admin-bar--tab-content-item is-active">
					<div class="udb-admin-bar--fields udb-admin-bar--submenu-fields">
						<div class="field">
							<label for="submenu_title_{role}_{default_menu_id}_{submenu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Submenu Title' ); ?>
							</label>
							<div class="control">
								<textarea 
									row="1" 
									name="submenu_title_{role}_{default_menu_id}_{submenu_id}" 
									id="submenu_title_{role}_{default_menu_id}_{submenu_id}" 
									placeholder="{encoded_default_submenu_title}" 
									class="udb-admin-bar--text-field"
									data-name="submenu_title"
								>{submenu_title}</textarea>
							</div>
						</div>
						<div class="field">
							<label for="submenu_href_{role}_{default_menu_id}_{submenu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Submenu URL' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="submenu_href_{role}_{default_menu_id}_{submenu_id}" 
									id="submenu_href_{role}_{default_menu_id}_{submenu_id}" 
									value="{submenu_href}" 
									placeholder="{default_submenu_href}" 
									class="udb-admin-bar--text-field"
									data-name="submenu_href"
								>
							</div>
						</div>
					</div><!-- .udb-admin-bar--fields -->
				</div><!-- #udb-admin-bar--settings-tab -->
				<div id="udb-admin-bar--submenu-tab--{role}" class="udb-admin-bar--tab-content-item udb-admin-bar--edit-area {submenu_tab_is_hidden}">
					<ul class="udb-admin-bar--menu-list udb-admin-bar--submenu-list">
						{submenu_template}
					</ul>

					<?php do_action( 'udb_admin_bar_add_submenu_button' ); ?>
				</div><!-- #udb-admin-bar--submenu-tab -->
			</div><!-- .udb-admin-bar--tab-content -->

		</div>

	</div><!-- .udb-admin-bar--expanded-panel -->
</li>

<?php
return ob_get_clean();
