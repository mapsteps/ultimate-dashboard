<?php
/**
 * Menu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-menu-builder--menu-item" data-hidden="{menu_is_hidden}" data-added="{menu_was_added}" data-default-id="{default_menu_id}" data-default-url="{default_menu_url}">
	<div class="udb-menu-builder--control-panel">
		<div class="udb-menu-builder--menu-drag">
			<span></span>
		</div>
		<div class="udb-menu-builder--menu-icon">
			{menu_icon}
		</div>
		<div class="udb-menu-builder--menu-name">
			{parsed_menu_title}
		</div>
		<div class="udb-menu-builder--menu-actions">
			{trash_icon}
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
			<span class="dashicons dashicons-arrow-down-alt2 expand-menu"></span>
		</div>
	</div><!-- .udb-menu-builder--control-panel -->

	<div class="udb-menu-builder--expanded-panel">

		<div class="udb-menu-builder--tabs udb-menu-builder--menu-item-tabs">

			<ul class="udb-menu-builder--tab-menu">
				<li class="udb-menu-builder--tab-menu-item is-active" data-udb-tab-content="udb-menu-builder--settings-tab--{role}">
					<button type="button">
						<?php _e( 'Settings', 'ultimate-dashboard' ); ?>
					</button>
				</li>
				<li class="udb-menu-builder--tab-menu-item" data-udb-tab-content="udb-menu-builder--submenu-tab--{role}">
					<button type="button">
						<?php _e( 'Submenu', 'ultimate-dashboard' ); ?>
					</button>
				</li>
			</ul><!-- .udb-menu-builder--tab-menu -->

			<div class="udb-menu-builder--tab-content">
				<div id="udb-menu-builder--settings-tab--{role}" class="udb-menu-builder--tab-content-item is-active">
					<div class="udb-menu-builder--fields">
						<div class="field">
							<label for="menu_title_{role}_{default_menu_id}" class="label udb-menu-builder--label">
								<?php _e( 'Menu Title' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="menu_title_{role}_{default_menu_id}" 
									id="menu_title_{role}_{default_menu_id}" 
									value="{menu_title}" 
									placeholder="{default_menu_title}" 
									class="udb-menu-builder--text-field"
									data-name="menu_title"
								>
							</div>
						</div>
						<div class="field">
							<label for="menu_url_{role}_{default_menu_id}" class="label udb-menu-builder--label">
								<?php _e( 'Menu URL' ); ?>
							</label>
							<div class="control">
								<input 
									type="text" 
									name="menu_url_{role}_{default_menu_id}" 
									id="menu_url_{role}_{default_menu_id}" 
									value="{menu_url}" 
									placeholder="{default_menu_url}" 
									class="udb-menu-builder--text-field"
									data-name="menu_url"
								>
							</div>
						</div>
						<div class="is-nested">
							<div class="field">
								<label for="menu_icon_{role}_{default_menu_id}" class="label udb-menu-builder--label">
									<?php _e( 'Menu Icon', 'ultimate-dashboard' ); ?>
								</label>
							</div>
							<div class="udb-menu-builder--tabs udb-menu-builder--icon-switcher">
								<ul class="udb-menu-builder--tab-menu">
									<li class="udb-menu-builder--tab-menu-item {dashicon_tab_is_active}" data-udb-tab-content="udb-menu-builder--dashicon-tab--{role}" data-tab-name="dashicon">
										<button type="button">Dashicons</button>
									</li>
									<li class="udb-menu-builder--tab-menu-item {icon_svg_tab_is_active}" data-udb-tab-content="udb-menu-builder--icon-svg-tab--{role}" data-tab-name="icon_svg">
										<button type="button">SVG Code</button>
									</li>
								</ul>
								<div class="udb-menu-builder--tab-content">
									<div id="udb-menu-builder--dashicon-tab--{role}" class="udb-menu-builder--tab-content-item {dashicon_tab_is_active}">
										<div class="field">
											<div class="control">
												<input type="text" class="udb-menu-builder--text-field udb-menu-builder--icon-field dashicons-picker" data-width="100%" name="menu_dashicon_{role}_{default_menu_id}" id="menu_dashicon_{role}_{default_menu_id}" value="{menu_dashicon}" placeholder="{default_menu_dashicon}" data-name="dashicon" />
											</div>
										</div>
									</div>
									<div id="udb-menu-builder--icon-svg-tab--{role}" class="udb-menu-builder--tab-content-item {icon_svg_tab_is_active}">
										<textarea name="menu_icon_svg_{role}_{default_menu_id}" id="menu_icon_svg_{role}_{default_menu_id}" class="udb-menu-builder--textarea-field udb-menu-builder--icon-field" placeholder="{default_menu_icon_svg}" data-name="icon_svg">{menu_icon_svg}</textarea>
										<p class="description">
											<?php _e( "Paste a base64-encoded SVG using a data URI, which will be colored to match the color scheme. This should begin with 'data:image/svg+xml;base64,'. Or you can use this tool to generate it: https://dopiaza.org/tools/datauri/index.php", 'ultimate-dashboard' ); ?>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div><!-- .udb-menu-builder--fields -->
				</div><!-- #udb-menu-builder--settings-tab -->
				<div id="udb-menu-builder--submenu-tab--{role}" class="udb-menu-builder--tab-content-item udb-menu-builder--edit-area">
					<ul class="udb-menu-builder--menu-list udb-menu-builder--submenu-list udb-menu-builder-sortable">
						{submenu_template}
					</ul>

					<?php do_action( 'udb_admin_menu_add_submenu_button' ); ?>
				</div><!-- #udb-menu-builder--submenu-tab -->
			</div><!-- .udb-menu-builder--tab-content -->

		</div><!-- .udb-menu-builder--tabs -->

	</div><!-- .udb-menu-builder--expanded-panel -->
</li>

<?php
return ob_get_clean();
