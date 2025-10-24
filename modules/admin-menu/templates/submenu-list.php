<?php
/**
 * Submenu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-menu-builder--menu-item udb-menu-builder--submenu-item" data-hidden="{submenu_is_hidden}" data-added="{submenu_was_added}" data-default-url="{default_submenu_url}" data-submenu-id="{submenu_id}">
	<div class="udb-menu-builder--control-panel">
		<div class="udb-menu-builder--menu-drag">
			<span></span>
		</div>
		<div class="udb-menu-builder--menu-name">
			{parsed_submenu_title}
		</div>
		<div class="udb-menu-builder--menu-actions">
			{trash_icon}
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
			<span class="dashicons dashicons-arrow-down-alt2 expand-menu"></span>
		</div>
	</div><!-- .udb-menu-builder--control-panel -->

	<div class="udb-menu-builder--expanded-panel">

		<div class="udb-menu-builder--fields udb-menu-builder--submenu-fields">
			<div class="field">
				<label for="submenu_title_{role}_{default_menu_id}_{submenu_id}" class="label udb-menu-builder--label">
					<?php esc_html_e( 'Submenu Title', 'ultimate-dashboard' ); ?>
				</label>
				<div class="control">
					<input 
						type="text" 
						name="submenu_title_{role}_{default_menu_id}_{submenu_id}" 
						id="submenu_title_{role}_{default_menu_id}_{submenu_id}" 
						value="{submenu_title}" 
						placeholder="{default_submenu_title}" 
						class="udb-menu-builder--text-field"
						data-name="submenu_title"
					>
				</div>
			</div>
			<div class="field">
				<label for="submenu_url_{role}_{default_menu_id}_{submenu_id}" class="label udb-menu-builder--label">
					<?php esc_html_e( 'Submenu URL', 'ultimate-dashboard' ); ?>
				</label>
				<div class="control">
					<input 
						type="text" 
						name="submenu_url_{role}_{default_menu_id}_{submenu_id}" 
						id="submenu_url_{role}_{default_menu_id}_{submenu_id}" 
						value="{submenu_url}" 
						placeholder="{default_submenu_url}" 
						class="udb-menu-builder--text-field"
						data-name="submenu_url"
					>
				</div>
			</div>
		</div><!-- .udb-menu-builder--fields -->

	</div><!-- .udb-menu-builder--expanded-panel -->
</li>

<?php
return ob_get_clean();
