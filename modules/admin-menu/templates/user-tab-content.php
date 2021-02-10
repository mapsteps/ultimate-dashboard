<?php
/**
 * User tab content template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<div id="udb-admin-menu--user-{user_id}-edit-area" class="udb-admin-menu--tab-content-item udb-admin-menu--user-workspace is-active" data-user-id="{user_id}">
	<ul class="udb-admin-menu--menu-list">
		<!-- to be re-written via js -->
		<li class="loading"></li>
	</ul>
</div>

<?php
return ob_get_clean();
