<?php
/**
 * Remove admin bar metabox
 *
 * @package Ultimate_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

use Udb\Helpers\Admin_Bar_Helper;

$admin_bar_helper = new Admin_Bar_Helper();
$saved_roles      = $admin_bar_helper->roles_to_remove();

?>

<div class="heatbox admin-bar-visibility-box">

	<h2>
		<?php esc_html_e( 'Visibility', 'ultimate-dashboard' ); ?>
	</h2>

	<div class="heatbox-content">
		<h3><label for="remove_by_roles"><?php esc_html_e( 'Hide the admin bar on the frontend for:', 'ultimate-dashboard' ); ?></label></h3>
		<p class="description"><?php esc_html_e( 'It stays visible inside the WordPress admin area.', 'ultimate-dashboard' ); ?></p>
		<div class="field">
			<label class="label select2-label">
				<select name="remove_by_roles[]" id="remove_by_roles" class="multiselect remove-admin-bar use-select2 is-fullwidth" multiple>
					<option value="all" <?php selected( in_array( 'all', $saved_roles, true ) ); ?>><?php esc_html_e( 'All', 'ultimate-dashboard' ); ?></option>
					<?php foreach ( wp_roles()->roles as $role_key => $role_data ) : ?>
						<option value="<?php echo esc_attr( $role_key ); ?>" <?php selected( in_array( $role_key, $saved_roles, true ) ); ?>><?php echo esc_html( translate_user_role( $role_data['name'] ) ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>
	</div>

	<div class="heatbox-footer">
		<div class="field">
			<button type="button" class="button button-primary button-larger js-save-remove-admin-bar">
				<?php esc_html_e( 'Save Changes', 'ultimate-dashboard' ); ?>
			</button>
		</div>
	</div>

</div>
