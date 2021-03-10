<?php
/**
 * Menu separator template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-bar--menu-item udb-admin-bar--separator-item" data-hidden="{menu_is_hidden}" data-added="{menu_was_added}" data-default-id="{default_menu_id}" data-default-url="{default_menu_url}">
	<div class="udb-admin-bar--control-panel">
		<div class="udb-admin-bar--menu-drag">
			<span></span>
		</div>
		<div class="udb-admin-bar--menu-icon">
			<i class="dashicons dashicons-minus"></i>
		</div>
		<div class="udb-admin-bar--menu-name">{separator}</div>
		<span class="udb-admin-bar--menu-actions">
			<span class="dashicons dashicons-{hidden_icon} hide-menu"></span>
		</span>
	</div><!-- .udb-admin-bar--control-panel -->
</li>

<?php
return ob_get_clean();
