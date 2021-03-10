<?php
/**
 * Submenu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-bar--menu-item udb-admin-bar--submenu-item" data-hidden="{submenu_is_hidden}" data-added="{submenu_was_added}" data-default-url="{default_submenu_url}" data-submenu-id="{submenu_id}">
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

		<div class="udb-admin-bar--fields udb-admin-bar--submenu-fields">
			<div class="field">
				<label for="submenu_title_{role}_{default_menu_id}_{submenu_id}" class="label udb-admin-bar--label">
					<?php _e( 'Submenu Title' ); ?>
				</label>
				<div class="control">
					<input 
						type="text" 
						name="submenu_title_{role}_{default_menu_id}_{submenu_id}" 
						id="submenu_title_{role}_{default_menu_id}_{submenu_id}" 
						value="{submenu_title}" 
						placeholder="{default_submenu_title}" 
						class="udb-admin-bar--text-field"
						data-name="submenu_title"
					>
				</div>
			</div>
			<div class="field">
				<label for="submenu_url_{role}_{default_menu_id}_{submenu_id}" class="label udb-admin-bar--label">
					<?php _e( 'Submenu URL' ); ?>
				</label>
				<div class="control">
					<input 
						type="text" 
						name="submenu_url_{role}_{default_menu_id}_{submenu_id}" 
						id="submenu_url_{role}_{default_menu_id}_{submenu_id}" 
						value="{submenu_url}" 
						placeholder="{default_submenu_url}" 
						class="udb-admin-bar--text-field"
						data-name="submenu_url"
					>
				</div>
			</div>
		</div><!-- .udb-admin-bar--fields -->

	</div><!-- .udb-admin-bar--expanded-panel -->
</li>

<?php
return ob_get_clean();
