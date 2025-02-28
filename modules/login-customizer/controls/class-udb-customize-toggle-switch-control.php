<?php
/**
 * Custom toggle switch control.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb;

/**
 * Custom toggle switch control.
 */
class Udb_Customize_Toggle_Switch_Control extends \WP_Customize_Control {
	/**
	 * Control's type.
	 *
	 * @var string
	 */
	public $type = 'toggle-switch';

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

		$this->input_attrs['class'] .= ' udb-customize-field udb-customize-range-field';
		?>

		<div class="udb-customize-control--cols">
			<div class="udb-customize-control--left-col">

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

			</div>
			<div class="udb-customize-control--right-col">

				<div class="switch-control heatbox-wrap">
					<label for="<?php echo esc_attr( $input_id ); ?>" class="toggle-switch">
						<input
							type="checkbox"
							id="<?php echo esc_attr( $input_id ); ?>"
							class="<?php echo esc_attr( $this->input_attrs['class'] ); ?>"
							value="<?php echo esc_attr( $this->value() ); ?>"
							<?php $this->link(); ?>
							<?php checked( $this->value() ); ?>
						>
						<div class="switch-track">
							<div class="switch-thumb"></div>
						</div>
					</label>
				</div>

			</div>
		</div>

		<?php
	}
}
