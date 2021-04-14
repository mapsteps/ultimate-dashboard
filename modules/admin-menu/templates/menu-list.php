<?php
/**
 * Menu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-menu--menu-item" data-hidden="{menu_is_hidden}" data-added="{menu_was_added}" data-default-id="{default_menu_id}" data-default-url="{default_menu_url}">
	<div class="udb-admin-menu--control-panel">
		<div class="udb-admin-menu--menu-drag">
			<span></span>
		</div>
		<div class="udb-admin-menu--menu-icon">
			{menu_icon}
		</div>
		<div class="udb-admin-menu--menu-name">
			{parsed_menu_title}
		</div>
		<div class="udb-admin-menu--menu-actions">
			{trash_icon}
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
		</div>
		<div class="udb-admin-menu--expand-menu">
			<span class="dashicons dashicons-arrow-down-alt2">
			</span>
		</div>
	</div><!-- .udb-admin-menu--control-panel -->

	<div class="udb-admin-menu--expanded-panel">

		<div class="udb-admin-menu--tabs udb-admin-menu--menu-item-tabs">

			<ul class="udb-admin-menu--tab-menu">
				<li class="udb-admin-menu--tab-menu-item is-active" data-udb-tab-content="udb-admin-menu--settings-tab--{role}">
					<button type="button">
						<?php _e( 'Settings', 'ultimate-dashboard' ); ?>
					</button>
				</li>
				<li class="udb-admin-menu--tab-menu-item" data-udb-tab-content="udb-admin-menu--submenu-tab--{role}">
					<button type="button">
						<?php _e( 'Submenu', 'ultimate-dashboard' ); ?>
					</button>
				</li>
			</ul><!-- .udb-admin-menu--tab-menu -->

			<div class="udb-admin-menu--tab-content">
				<div id="udb-admin-menu--settings-tab--{role}" class="udb-admin-menu--tab-content-item is-active">
					<div class="udb-admin-menu--fields">
						<div class="field">
							<label for="menu_title_{role}_{default_menu_id}" class="label udb-admin-menu--label">
								<?php _e( 'Menu Title' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="menu_title_{role}_{default_menu_id}" 
									id="menu_title_{role}_{default_menu_id}" 
									value="{menu_title}" 
									placeholder="{default_menu_title}" 
									class="udb-admin-menu--text-field"
									data-name="menu_title"
								>
							</div>
						</div>
						<div class="field">
							<label for="menu_url_{role}_{default_menu_id}" class="label udb-admin-menu--label">
								<?php _e( 'Menu URL' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="menu_url_{role}_{default_menu_id}" 
									id="menu_url_{role}_{default_menu_id}" 
									value="{menu_url}" 
									placeholder="{default_menu_url}" 
									class="udb-admin-menu--text-field"
									data-name="menu_url"
								>
							</div>
						</div>
						<div class="is-nested">
							<div class="field">
								<label for="menu_icon_{role}_{default_menu_id}" class="label udb-admin-menu--label">
									<?php _e( 'Menu Icon', 'ultimate-dashboard' ); ?>
								</label>
							</div>
							<div class="udb-admin-menu--tabs udb-admin-menu--icon-switcher">
								<ul class="udb-admin-menu--tab-menu">
									<li class="udb-admin-menu--tab-menu-item {dashicon_tab_is_active}" data-udb-tab-content="udb-admin-menu--dashicon-tab--{role}" data-tab-name="dashicon">
										<button type="button">Dashicons</button>
									</li>
									<li class="udb-admin-menu--tab-menu-item {icon_svg_tab_is_active}" data-udb-tab-content="udb-admin-menu--icon-svg-tab--{role}" data-tab-name="icon_svg">
										<button type="button">SVG Code</button>
									</li>
								</ul>
								<div class="udb-admin-menu--tab-content">
									<div id="udb-admin-menu--dashicon-tab--{role}" class="udb-admin-menu--tab-content-item {dashicon_tab_is_active}">
										<div class="field">
											<div class="control">
												<input type="text" class="udb-admin-menu--text-field udb-admin-menu--icon-field dashicons-picker" data-width="100%" name="menu_dashicon_{role}_{default_menu_id}" id="menu_dashicon_{role}_{default_menu_id}" value="{menu_dashicon}" placeholder="{default_menu_dashicon}" data-name="dashicon" />
											</div>
										</div>
									</div>
									<div id="udb-admin-menu--icon-svg-tab--{role}" class="udb-admin-menu--tab-content-item {icon_svg_tab_is_active}">
										<textarea name="menu_icon_svg_{role}_{default_menu_id}" id="menu_icon_svg_{role}_{default_menu_id}" class="udb-admin-menu--textarea-field udb-admin-menu--icon-field" placeholder="{default_menu_icon_svg}" data-name="icon_svg">{menu_icon_svg}</textarea>
										<p class="description">
											<?php _e( "Paste a base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'. Or you can use this tool to generate it: https://dopiaza.org/tools/datauri/index.php", 'ultimate-dashboard' ); ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div><!-- .udb-admin-menu--fields -->
				</div><!-- #udb-admin-menu--settings-tab -->
				<div id="udb-admin-menu--submenu-tab--{role}" class="udb-admin-menu--tab-content-item udb-admin-menu--edit-area">
					<ul class="udb-admin-menu--menu-list udb-admin-menu--submenu-list">
						{submenu_template}
					</ul>

					<?php do_action( 'udb_admin_menu_add_submenu_button' ); ?>
				</div><!-- #udb-admin-menu--submenu-tab -->
			</div><!-- .udb-admin-menu--tab-content -->

		</div><!-- .udb-admin-menu--tabs -->

	</div><!-- .udb-admin-menu--expanded-panel -->
</li>

<?php
return ob_get_clean();
