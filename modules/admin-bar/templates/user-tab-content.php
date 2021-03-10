<?php
/**
 * User tab content template to be rendered via JS.
 *
 * @package Ultimate_Dashboard_Pro
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<div id="udb-admin-bar--user-{user_id}-edit-area" class="udb-admin-bar--tab-content-item udb-admin-bar--workspace udb-admin-bar--user-workspace is-active" data-user-id="{user_id}">
	<ul class="udb-admin-bar--menu-list">
		<!-- to be re-written via js -->
		<li class="loading"></li>
	</ul>

	<?php do_action( 'udb_admin_bar_add_menu_button' ); ?>
</div>

<?php
return ob_get_clean();
