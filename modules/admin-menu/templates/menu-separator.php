<?php
/**
 * Menu separator template to be rendered via JS.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-menu-builder--menu-item udb-menu-builder--separator-item" data-hidden="{menu_is_hidden}" data-added="{menu_was_added}" data-default-id="{default_menu_id}" data-default-url="{default_menu_url}">
	<div class="udb-menu-builder--control-panel">
		<div class="udb-menu-builder--menu-drag">
			<span></span>
		</div>
		<div class="udb-menu-builder--menu-icon">
			<i class="dashicons dashicons-minus"></i>
		</div>
		<div class="udb-menu-builder--menu-name">{separator}</div>
		<span class="udb-menu-builder--menu-actions">
			{trash_icon}
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
		</span>
	</div><!-- .udb-menu-builder--control-panel -->
</li>

<?php
return ob_get_clean();
