<?php
/**
 * User tab menu template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-bar--tab-menu-item is-active" data-udb-tab-content="udb-admin-bar--user-{user_id}-edit-area" data-user-id="{user_id}">
	<button type="button">
		{display_name}
	</button>
	<i class="dashicons dashicons-no-alt delete-icon udb-admin-bar--remove-tab"></i>
</li>

<?php
return ob_get_clean();
