<?php
/**
 * Custom control.
 *
 * @package Ultimate_Dashboard
 */

namespace Udb;

/**
 * Custom control.
 */
class Udb_Customize_Color_Picker_Control extends \WP_Customize_Control {
	/**
	 * Type.
	 *
	 * @var string
	 */
	public $type = 'color-picker';

	/**
	 * Statuses.
	 *
	 * @var array
	 */
	public $statuses;

	/**
	 * Whether to enable alpha opacity or not.
	 *
	 * @var string
	 */
	public $enable_alpha = false;

	/**
	 * Constructor.
	 *
	 * @since 3.4.0
	 *
	 * @see WP_Customize_Control::__construct()
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 *                                      See WP_Customize_Control::__construct() for information
	 *                                      on accepted arguments. Default empty array.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		if ( isset( $args['alpha'] ) ) {
			$this->enable_alpha = true;
		}

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Enqueue scripts/styles for the color picker.
	 *
	 * @since 3.4.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'wp-color-picker' );

		// ! patch: deregister old color picker alpha such as Kirki's color picker alpha, it's still using the old version.
		// wp_deregister_script( 'wp-color-picker-alpha' );

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha', ULTIMATE_DASHBOARD_PLUGIN_URL . '/assets/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), ULTIMATE_DASHBOARD_PLUGIN_VERSION, true );
	}

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

		printf( '<li id="%s" class="%s" data-control-name="%s" data-default-value="%s">', esc_attr( $id ), esc_attr( $class ), esc_attr( $this->id ), esc_attr( $this->value() ) );
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
		$input_id       = '_customize-input-' . $this->id;
		$description_id = '_customize-description-' . $this->id;

		if ( ! isset( $this->input_attrs['class'] ) ) {
			$this->input_attrs['class'] = '';
		}

		$this->input_attrs['class'] .= ' udb-customize-field udb-customize-range-field';
		?>

		<header class="udb-customize-control-header">
			<?php if ( ! empty( $this->label ) ) : ?>
				<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title udb-customize-control-label udb-customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description udb-customize-control-description"><?php echo $this->description; ?></span>
			<?php endif; ?>
			<span class="dashicons dashicons-image-rotate udb-customize-control-reset" title="<?php _e( 'Reset Value', 'ultimate-dashboard' ); ?>" data-reset-value="<?php echo esc_attr( $this->value() ); ?>"></span>
		</header>
		<div class="udb-customize-control-content">
			<input
				type="text"
				id="<?php echo esc_attr( $input_id ); ?>"
				class="<?php echo esc_attr( $this->input_attrs['class'] ); ?>"
				<?php if ( ! isset( $this->input_attrs['value'] ) ) : ?>
					value="<?php echo esc_attr( $this->value() ); ?>"
				<?php endif; ?>
				<?php if ( $this->enable_alpha ) : ?>
					data-alpha="true" <?php /* for old version enqueued by Kirki */ ?>
					data-alpha-enabled="true"
				<?php endif; ?>
				<?php $this->link(); ?>
			>
		</div>

		<?php
	}

	/**
	 * Render a JS template for the content of the color picker control.
	 *
	 * @since 4.1.0
	 */
	public function content_template() {
	}
}
