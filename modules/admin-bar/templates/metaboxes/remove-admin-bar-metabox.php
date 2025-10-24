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

$roles_obj = new \WP_Roles();
$roles     = $roles_obj->role_names;
?>

<div class="heatbox admin-bar-visibility-box">

	<h2>
		<?php esc_html_e( 'Visibility', 'ultimate-dashboard' ); ?>
	</h2>

	<div class="heatbox-content">
		<h3><?php esc_html_e( 'Remove Admin Bar for:', 'ultimate-dashboard' ); ?></h3>
		<div class="field">
			<label for="remove_by_roles" class="label select2-label">
				<select name="remove_by_roles[]" id="remove_by_roles" class="ultiselect remove-admin-bar use-select2 is-fullwidth" multiple>
					<option value="all"<?php echo ( in_array( 'all', $saved_roles, true ) ? ' selected' : '' ); ?>>
						<?php esc_html_e( 'All', 'ultimate-dashboard' ); ?>
					</option>
					<?php foreach ( $roles as $role_key => $role_name ) : ?>
						<?php
						$selected_attr = '';

						if ( in_array( $role_key, $saved_roles, true ) ) {
							$selected_attr = ' selected';
						}
						?>
						<option value="<?php echo esc_attr( $role_key ); ?>"<?php echo esc_attr( $selected_attr ); ?>>
							<?php echo esc_attr( $role_name ); ?>
						</option>
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
