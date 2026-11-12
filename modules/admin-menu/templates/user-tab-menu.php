<?php
/**
 * User tab menu template to be rendered via JS.
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

ob_start();
?>

<li class="udb-menu-builder--tab-menu-item is-active" data-udb-tab-content="udb-menu-builder--user-{user_id}-edit-area" data-user-id="{user_id}">
	<button type="button">
		{display_name}
	</button>
	<button type="button" class="dashicons dashicons-no-alt delete-icon udb-menu-builder--remove-tab" aria-label="<?php esc_attr_e( 'Remove user', 'ultimate-dashboard' ); ?>"></button>
</li>

<?php
return ob_get_clean();
