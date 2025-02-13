<?php
/**
 * Emergency Form Fields.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Builders\FormFields;

/**
 * Form field class to render emergency form fields.
 *
 * @since 6.3.0
 */
class EmergencyFormFields extends FormField {
	public function __construct() {
		parent::__construct( false );

		$fields = DefaultFormFields::emergency_form_fields();

		$this->init( $fields );
	}

	public function render() {
		$this->fields = $this->map_fields( $this->fields );
		?>
		<div class="wpte-checkout__form-section">
			<div class="wpte-checkout__form-row">
				<?php parent::render(); ?>
			</div>
		</div>
		<?php
	}

	protected function map_fields( $fields ) {
		$form_data = WTE()->session->get( 'emergency_form_data' );
		if ( ! $form_data ) {
			$form_data = [];
		}

		return array_map( function ( $field ) use ( $form_data ) {
			$name = preg_match( "#\[([^\[]+)]$#", $field[ 'name' ], $matches ) ? $matches[ 1 ] : $field[ 'name' ];
			if ( $name ) {
				$field[ 'class' ]         = 'wpte-checkout__input';
				$field[ 'wrapper_class' ] = 'wpte-checkout__form-col';
				$field[ 'name' ]          = sprintf( 'emergency[%s]', $name );;
				$field[ 'id' ] = sprintf( 'emergency_%s', $name );;
			}
			$field[ 'field_label' ] = isset( $field[ 'placeholder' ] ) && $field[ 'placeholder' ] !== '' ? $field[ 'placeholder' ] : $field[ 'field_label' ];
			$field[ 'default' ]     = $form_data[ $name ] ?? $field[ 'default' ] ?? '';

			return $field;
		}, $fields );
	}
}
