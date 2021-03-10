<?php
/**
 * Menu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-bar--menu-item" data-hidden="{menu_is_hidden}" data-added="{menu_was_added}" data-default-id="{default_menu_id}" data-default-href="{default_menu_href}">
	<div class="udb-admin-bar--control-panel">
		<div class="udb-admin-bar--menu-drag">
			<span></span>
		</div>
		<div class="udb-admin-bar--menu-icon">
			{menu_icon}
		</div>
		<div class="udb-admin-bar--menu-name">
			{parsed_menu_title}
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

		<div class="udb-admin-bar--tabs udb-admin-bar--menu-item-tabs">

			<ul class="udb-admin-bar--tab-menu">
				<li class="udb-admin-bar--tab-menu-item is-active" data-udb-tab-content="udb-admin-bar--settings-tab--{role}">
					<button type="button">
						<?php _e( 'Settings', 'ultimate-dashboard' ); ?>
					</button>
				</li>
				<li class="udb-admin-bar--tab-menu-item" data-udb-tab-content="udb-admin-bar--submenu-tab--{role}">
					<button type="button">
						<?php _e( 'Submenu', 'ultimate-dashboard' ); ?>
					</button>
				</li>
			</ul><!-- .udb-admin-bar--tab-menu -->

			<div class="udb-admin-bar--tab-content">
				<div id="udb-admin-bar--settings-tab--{role}" class="udb-admin-bar--tab-content-item is-active">
					<div class="udb-admin-bar--fields">
						<div class="field">
							<label for="menu_title_{role}_{default_menu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Menu Title' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="menu_title_{role}_{default_menu_id}" 
									id="menu_title_{role}_{default_menu_id}" 
									value="{menu_title}" 
									placeholder="{default_menu_title}" 
									class="udb-admin-bar--text-field"
									data-name="menu_title"
								>
							</div>
						</div>
						<div class="field">
							<label for="menu_href_{role}_{default_menu_id}" class="label udb-admin-bar--label">
								<?php _e( 'Menu URL' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="menu_href_{role}_{default_menu_id}" 
									id="menu_href_{role}_{default_menu_id}" 
									value="{menu_href}" 
									placeholder="{default_menu_href}" 
									class="udb-admin-bar--text-field"
									data-name="menu_href"
								>
							</div>
						</div>
						<div class="is-nested">
							<div class="field">
								<label for="menu_icon_{role}_{default_menu_id}" class="label udb-admin-bar--label">
									<?php _e( 'Menu Icon', 'ultimate-dashboard' ); ?>
								</label>
							</div>
							<div class="udb-admin-bar--tabs udb-admin-bar--icon-switcher">
								<ul class="udb-admin-bar--tab-menu">
									<li class="udb-admin-bar--tab-menu-item {dashicon_tab_is_active}" data-udb-tab-content="udb-admin-bar--dashicon-tab--{role}" data-tab-name="dashicon">
										<button type="button">Dashicons</button>
									</li>
									<li class="udb-admin-bar--tab-menu-item {icon_svg_tab_is_active}" data-udb-tab-content="udb-admin-bar--icon-svg-tab--{role}" data-tab-name="icon_svg">
										<button type="button">SVG Code</button>
									</li>
								</ul>
								<div class="udb-admin-bar--tab-content">
									<div id="udb-admin-bar--dashicon-tab--{role}" class="udb-admin-bar--tab-content-item {dashicon_tab_is_active}">
										<div class="field">
											<div class="control">
												<input type="text" class="udb-admin-bar--text-field udb-admin-bar--icon-field dashicons-picker" data-width="100%" name="menu_dashicon_{role}_{default_menu_id}" id="menu_dashicon_{role}_{default_menu_id}" value="{menu_dashicon}" placeholder="{default_menu_dashicon}" data-name="dashicon" />
											</div>
										</div>
									</div>
									<div id="udb-admin-bar--icon-svg-tab--{role}" class="udb-admin-bar--tab-content-item {icon_svg_tab_is_active}">
										<textarea name="menu_icon_svg_{role}_{default_menu_id}" id="menu_icon_svg_{role}_{default_menu_id}" class="udb-admin-bar--textarea-field udb-admin-bar--icon-field" placeholder="{default_menu_icon_svg}" data-name="icon_svg">{menu_icon_svg}</textarea>
										<p class="description">
											<?php _e( "Paste a base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'. Or you can use this tool to generate it: https://dopiaza.org/tools/datauri/index.php", 'ultimate-dashboard' ); ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div><!-- .udb-admin-bar--fields -->
				</div><!-- #udb-admin-bar--settings-tab -->
				<div id="udb-admin-bar--submenu-tab--{role}" class="udb-admin-bar--tab-content-item udb-admin-bar--edit-area">
					<ul class="udb-admin-bar--menu-list udb-admin-bar--submenu-list">
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
