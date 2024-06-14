<?php
/**
 * User tab content template to be rendered via JS.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<div id="udb-menu-builder--user-{user_id}-edit-area" class="udb-menu-builder--tab-content-item udb-menu-builder--workspace udb-menu-builder--user-workspace is-active" data-user-id="{user_id}">
	<ul class="udb-menu-builder--menu-list">
		<!-- to be re-written via js -->
		<li class="loading"></li>
	</ul>

	<div class="udb-menu-builder--inline-buttons">
		<?php
		do_action( 'udb_admin_menu_add_menu_button' );
		do_action( 'udb_admin_menu_add_separator_button' );
		?>
	</div>
</div>

<?php
return ob_get_clean();
