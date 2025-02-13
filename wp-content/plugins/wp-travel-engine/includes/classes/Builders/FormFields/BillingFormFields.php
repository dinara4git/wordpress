<?php
/**
 * Billing Form Fields.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Builders\FormFields;

use WPTravelEngine\Traits\Singleton;

/**
 * Form field class to render billing form fields.
 *
 * @since 6.3.0
 */
class BillingFormFields extends FormField {

	use Singleton;

	public function __construct() {
		parent::__construct( false );

		$this->init( $this->map_fields( \WTE_Default_Form_Fields::billing_form_fields() ) );
	}

	/**
	 * @inheritDoc
	 */
	public function render(): void {
		?>
		<div class="wpte-checkout__form-section">
			<div class="wpte-checkout__form-row">
				<?php parent::render(); ?>
			</div>
		</div>
		<?php
	}

	protected function map_fields( $fields ) {
		$billing_form_data = WTE()->session->get( 'billing_form_data' );
		if ( ! $billing_form_data ) {
			$billing_form_data = [];
		}

		return array_map( function ( $field ) use ( $billing_form_data ) {
			$name = null;

			// Extract the name using regex patterns.
			if ( preg_match( "#\[([^\[]+)]$#", $field[ 'name' ], $matches ) ) {
				$name = $matches[ 1 ];
			} else if ( preg_match( "/^[^\s]+$/", $field[ 'name' ], $matches ) ) {
				$name = $matches[ 0 ];
			}

			// If a name was found, set field attributes.
			if ( $name ) {
				$field[ 'class' ]         = 'wpte-checkout__input';
				$field[ 'wrapper_class' ] = 'wpte-checkout__form-col';
				if ( $field[ 'type' ] === 'file' ) {
					$field[ 'name' ] = sprintf( '%s', $name );
					$field[ 'id' ]   = sprintf( '%s', $name );
				} else {
					$field[ 'name' ] = sprintf( 'billing[%s]', $name );
					$field[ 'id' ]   = sprintf( 'billing_%s', $name );
				}
				$field[ 'field_label' ] = isset( $field[ 'placeholder' ] ) && $field[ 'placeholder' ] !== '' ? $field[ 'placeholder' ] : $field[ 'field_label' ];
				$field[ 'default' ]     = $billing_form_data[ $name ] ?? $field[ 'default' ] ?? '';;
			}

			return $field;
		}, $fields );
	}


}
