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
class Udb_Customize_Control extends \WP_Customize_Control {

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

		printf( '<li id="%s" class="%s">', esc_attr( $id ), esc_attr( $class ) );
		$this->render_content();
		echo '</li>';

	}

}
