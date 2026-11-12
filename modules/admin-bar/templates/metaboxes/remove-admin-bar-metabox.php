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

	<h2><?php esc_html_e( 'Visibility', 'ultimate-dashboard' ); ?></h2>

	<div class="heatbox-content">
		<h3 id="udb-admin-bar-visibility-label"><?php esc_html_e( 'Hide the admin bar on the frontend for:', 'ultimate-dashboard' ); ?></h3>
		<p class="description"><?php esc_html_e( 'It stays visible inside the WordPress admin area.', 'ultimate-dashboard' ); ?></p>

		<div class="setting-fields" role="group" aria-labelledby="udb-admin-bar-visibility-label">
			<?php
			$roles = array( 'all' => __( 'All', 'ultimate-dashboard' ) );

			foreach ( wp_roles()->roles as $role_key => $role_data ) {
				$roles[ $role_key ] = translate_user_role( $role_data['name'] );
			}

			foreach ( $roles as $role_key => $role_name ) :
				?>
				<div class="setting-field">
					<label class="label checkbox-label">
						<?php echo esc_html( $role_name ); ?>
						<input type="checkbox" class="udb-remove-admin-bar-role" value="<?php echo esc_attr( $role_key ); ?>" <?php checked( in_array( $role_key, $saved_roles, true ) ); ?>>
						<div class="indicator"></div>
					</label>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="heatbox-margin-top">
			<button type="button" class="button button-primary js-save-remove-admin-bar"><?php esc_html_e( 'Save Changes', 'ultimate-dashboard' ); ?></button>
			<span class="heatbox-notice" role="status"></span>
		</p>
	</div>

</div>
