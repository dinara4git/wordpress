<?php
/**
 * Traveller Form Fields.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Builders\FormFields;

/**
 * Form field class to render billing form fields.
 *
 * @since 6.3.0
 */
class TravellerFormFields extends FormField {

	public function __construct() {
		parent::__construct( false );
	}


	public function render() {
		echo '<div class="wpte-checkout__form-row">';
		parent::render();
		echo '</div>';
	}

	/**
	 * @param array $form_data
	 *
	 * @return array
	 * @since 6.3.3
	 */
	public function with_values( array $form_data ): array {
		$this->fields = DefaultFormFields::traveller_form_fields();

		return array_map( function ( $field ) use ( $form_data ) {
			$name = preg_match( "#\[([^\[]+)]$#", $field[ 'name' ], $matches ) ? $matches[ 1 ] : $field[ 'name' ];
			if ( $name ) {
				$field[ 'class' ]         = 'wpte-checkout__input';
				$field[ 'wrapper_class' ] = 'wpte-checkout__form-col';
				$field[ 'name' ]          = sprintf( 'travellers[%s]', $name );;
				$field[ 'id' ] = sprintf( 'travellers_%s', $name );;
			}
			$field[ 'field_label' ] = isset( $field[ 'placeholder' ] ) && $field[ 'placeholder' ] !== '' ? $field[ 'placeholder' ] : $field[ 'field_label' ];
			$field[ 'value' ]     = $form_data[ $name ] ?? $field[ 'default' ] ?? '';

			return $field;
		}, $this->fields );
	}
}
