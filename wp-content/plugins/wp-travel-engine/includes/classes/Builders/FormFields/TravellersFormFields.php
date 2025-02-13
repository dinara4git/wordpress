<?php
/**
 * Billing Form Fields.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Builders\FormFields;

/**
 * Form field class to render billing form fields.
 *
 * @since 6.3.0
 */
class TravellersFormFields extends TravellerFormFields {

	protected int $traveller_number = 0;

	/**
	 * @var int
	 */
	protected int $number_of_travellers;

	/**
	 * @var int
	 */
	protected $number_of_lead_travellers;


	public function __construct( $args = array() ) {
		$args = wp_parse_args( $args, [
			'number_of_travellers'      => 1,
			'number_of_lead_travellers' => 1,
		] );

		$this->number_of_travellers      = $args[ 'number_of_travellers' ];
		$this->number_of_lead_travellers = $args[ 'number_of_lead_travellers' ];

		$this->fields = DefaultFormFields::traveller_form_fields();
		parent::__construct();
	}

	/**
	 * Render the form fields.
	 *
	 * @return void
	 */
	public function render() {
		$this->lead_traveller_form_fields();
		$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
		$travellers_details_type = $wptravelengine_settings[ 'travellers_details_type' ] ?? 'all';
		if ( $travellers_details_type === 'only_lead' ) {
			return;
		}
		$this->fellow_traveller_form_fields();
	}

	/**
	 * Render the traveler fields.
	 *
	 * @param array $fields Form fields.
	 */
	protected function render_traveler_fields( array $fields ) {
		$instance = new parent();
		$fields   = $this->map_fields( $fields );
		$instance->init( $fields )->render();
	}

	/**
	 * Render the lead traveller form fields.
	 */
	public function lead_traveller_form_fields() {
		for ( $i = 0; $i < $this->number_of_lead_travellers; $i ++ ) :
			?>
			<div class="wpte-checkout__form-section">
				<h5 class="wpte-checkout__form-title"><?php echo sprintf( __( 'Lead Traveller %d', 'wp-travel-engine' ), $i + 1 ); ?></h5>
				<?php
				$this->render_traveler_fields( $this->fields );
				?>
			</div>
		<?php
		endfor;
	}

	/**
	 * @return void
	 */
	public function fellow_traveller_form_fields() {
		for ( $i = 0; $i < ( $this->number_of_travellers - $this->number_of_lead_travellers ); $i ++ ) :
			?>
			<div class="wpte-checkout__form-section">
				<h5 class="wpte-checkout__form-title">
					<?php
					/* translators: %d: Traveller number */
					echo sprintf( __( 'Traveler %d', 'wp-travel-engine' ), $this->traveller_number + 1 );
					?>
				</h5>
				<?php $this->render_traveler_fields( $this->fields ); ?>
			</div>
		<?php
		endfor;
	}

	protected function map_fields( $fields ) {
		$form_data = WTE()->session->get( 'travellers_form_data' );
		if ( ! $form_data ) {
			$form_data = [];
		}

		$fields = array_map( function ( $field ) use ( $form_data ) {
			$name = preg_match( "#\[([^\[]+)]$#", $field[ 'name' ], $matches ) ? $matches[ 1 ] : $field[ 'name' ];
			if ( $name ) {
				$field[ 'class' ]         = 'wpte-checkout__input';
				$field[ 'wrapper_class' ] = 'wpte-checkout__form-col';
				$field[ 'name' ]          = sprintf( 'travellers[%d][%s]', $this->traveller_number, $name );;
				$field[ 'id' ] = sprintf( 'travellers_%d_%s', $this->traveller_number, $name );;
			}
			$field[ 'field_label' ] = isset( $field[ 'placeholder' ] ) && $field[ 'placeholder' ] !== '' ? $field[ 'placeholder' ] : $field[ 'field_label' ];
			$field[ 'default' ]     = $form_data[ $this->traveller_number ][ $name ] ?? $field[ 'default' ] ?? '';

			return $field;
		}, $fields );

		$this->traveller_number ++;

		return $fields;
	}

}
