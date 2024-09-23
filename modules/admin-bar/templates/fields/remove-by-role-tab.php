<?php
/**
 * Admin bar settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Admin_Bar_Helper;

$settings           = new Admin_Bar_Helper();
$admin_bar_settings = $settings->get_admin_bar_settings();

$roles_obj = new \WP_Roles();
$roles     = $roles_obj->role_names;
?>

<div class="heatbox admin-bar-settings-box"> 

	<div class="setting-fields">

		<div class="field">
			<label for="remove_by_roles" class="label select2-label">
				<select name="remove_by_roles[]" id="remove_by_roles"
						class="ultiselect remove-admin-bar use-select2 is-fullwidth" multiple>
					<option
						value="all" <?php echo esc_attr( in_array( 'all', $admin_bar_settings['remove_by_roles'], true ) ? 'selected' : '' ); ?>><?php _e( 'All', 'hide-admin-bar' ); ?></option>

					<?php foreach ( $roles as $role_key => $role_name ) : ?>
						<?php
						$selected_attr = '';

						if ( in_array( $role_key, $admin_bar_settings['remove_by_roles'], true ) ) {
							$selected_attr = 'selected';
						}
						?>
						<option
							value="<?php echo esc_attr( $role_key ); ?>" <?php echo esc_attr( $selected_attr ); ?>><?php echo esc_attr( $role_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
	</div>		 
	 
</div>
