<?php
/**
 * Image list control.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb;

/**
 * Image list control.
 */
class Udb_Customize_Login_Template_Control extends \WP_Customize_Control {

	/**
	 * Control's type.
	 *
	 * @var string
	 */
	public $type = 'login-template';

	/**
	 * Renders the range control wrapper and calls $this->render_content() for the internals.
	 */
	protected function render() {

		$id    = 'customize-control-' . str_replace( array( '[', ']' ), array( '-', '' ), $this->id );
		$class = 'customize-control customize-control-' . $this->type . ' udb-customize-control udb-customize-control-' . $this->type;

		if ( ! isset( $this->input_attrs['class'] ) ) {
			$this->input_attrs['class'] = '';
		}

		$this->input_attrs['class'] .= ' udb-customize-field udb-customize-' . $this->type . '-field';

		printf( '<li id="%s" class="%s" data-control-name="%s">', esc_attr( $id ), esc_attr( $class ), esc_attr( $this->id ) );
		$this->render_content();
		echo '</li>';

	}

	/**
	 * Render the range control's content.
	 *
	 * Allows the content to be overridden without having to rewrite the wrapper in `$this::render()`.
	 * Control content can alternately be rendered in JS. See WP_Customize_Control::print_template().
	 */
	public function render_content() {

		$input_id         = '_customize-input-' . $this->id;
		$description_id   = '_customize-description-' . $this->id;
		$describedby_attr = ( ! empty( $this->description ) ) ? ' aria-describedby="' . esc_attr( $description_id ) . '" ' : '';

		if ( ! isset( $this->input_attrs['class'] ) ) {
			$this->input_attrs['class'] = '';
		}

		$this->input_attrs['class'] .= ' udb-customize-field udb-customize-login-template-field';

		$plugin_url = udb_is_pro_active() ? ULTIMATE_DASHBOARD_PRO_PLUGIN_URL : ULTIMATE_DASHBOARD_PLUGIN_URL;

		$login_templates = array(
			'default' => array(
				'thumbnail' => $plugin_url . '/modules/login-customizer/assets/images/template-default.jpg',
				'bg_image'  => '',
			),
			'left'    => array(
				'thumbnail' => $plugin_url . '/modules/login-customizer/assets/images/template-left.jpg',
				'bg_image'  => '',
			),
			'right'   => array(
				'thumbnail' => $plugin_url . '/modules/login-customizer/assets/images/template-right.jpg',
				'bg_image'  => '',
			),
		);
		?>

		<input
			type="hidden"
			id="<?php echo esc_attr( $input_id ); ?>"
			class="<?php echo esc_attr( $this->input_attrs['class'] ); ?>"
			<?php if ( ! isset( $this->input_attrs['value'] ) ) : ?>
				value="<?php echo esc_attr( $this->value() ); ?>"
			<?php endif; ?>
			<?php $this->link(); ?>
			<?php echo wp_kses_post( $describedby_attr ); ?>
		>

		<header class="udb-customize-control-header">
			<?php if ( ! empty( $this->label ) ) : ?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title udb-customize-control-label udb-customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description udb-customize-control-description">
					<?php echo wp_kses_post( $this->description ); ?>
				</span>
			<?php endif; ?>
		</header>

		<ul class="udb-customize-control-templates">
			<?php foreach ( $login_templates as $template_name => $template ) : ?>
				<?php
				if ( empty( $this->value() ) ) {
					$class = 'default' === $template_name ? ' is-selected' : '';
				} else {
					$class = $template_name === $this->value() ? ' is-selected' : '';
				}
				?>
				<li class="udb-customize-control-template <?php echo esc_attr( $class ); ?>">
					<img src="<?php echo esc_url( $template['thumbnail'] ); ?>" alt="Layout" data-template-name="<?php echo esc_attr( $template_name ); ?>" data-bg-image="<?php echo esc_url( $template['bg_image'] ); ?>">
				</li>
			<?php endforeach; ?>
		</ul>

		<?php
	}

}
