<?php
/**
 * User tab menu template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-admin-menu--tab-menu-item" data-udb-tab-content="udb-admin-menu--{user_id}-edit-area" data-user-id="{user_id}">
	<button type="button">
		{display_name}
	</button>
</li>

<?php
return ob_get_clean();
