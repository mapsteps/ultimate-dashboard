<?php
/**
 * Submenu list template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-menu--menu-item udb-admin-menu--submenu-item" data-hidden="{submenu_is_hidden}" data-added="{submenu_was_added}" data-default-url="{default_submenu_url}">
	<div class="udb-admin-menu--control-panel">
		<div class="udb-admin-menu--menu-drag">
			<span></span>
		</div>
		<div class="udb-admin-menu--menu-name">
			{parsed_submenu_title}
		</div>
		<span class="udb-admin-menu--menu-actions">
			<span class="dashicons dashicons-admin-page"></span>
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
		</span>
		<div class="udb-admin-menu--expand-menu">
			<span class="dashicons dashicons-arrow-down-alt2">
			</span>
		</div>
	</div><!-- .udb-admin-menu--control-panel -->

	<div class="udb-admin-menu--expanded-panel">

		<div class="udb-admin-menu--fields udb-admin-menu--submenu-fields">
			<div class="field">
				<label for="submenu_title_{role}_{default_menu_id}_{default_submenu_url}" class="label udb-admin-menu--label">
					<?php _e( 'Submenu Title' ); ?>
				</label>
				<div class="control">
					<input 
						type="text" 
						name="submenu_title_{role}_{default_menu_id}_{default_submenu_url}" 
						id="submenu_title_{role}_{default_menu_id}_{default_submenu_url}" 
						value="{submenu_title}" 
						placeholder="{default_submenu_title}" 
						class="udb-admin-menu--text-field"
						data-name="submenu_title"
					>
				</div>
			</div>
		</div><!-- .udb-admin-menu--fields -->

	</div><!-- .udb-admin-menu--expanded-panel -->
</li>

<?php
return ob_get_clean();
