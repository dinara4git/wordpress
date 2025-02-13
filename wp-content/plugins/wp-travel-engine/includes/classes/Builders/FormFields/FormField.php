<?php

namespace WPTravelEngine\Builders\FormFields;

if ( ! class_exists( '\WP_Travel_Engine_Form_Field' ) ) {
	require_once WP_TRAVEL_ENGINE_ABSPATH . 'includes/lib/wte-form-framework/class-wte-form-field.php';
}

/**
 * Form Field.
 *
 * @since 6.3.0
 */
class FormField extends \WP_Travel_Engine_Form_Field {

	/**
	 * Whether to use the legacy template or not.
	 *
	 * @var bool
	 */
	protected bool $use_legacy_template = true;

	/**
	 * Constructor to initialize the form field type.
	 */
	public function __construct( $use_legacy_template = true ) {
		$this->use_legacy_template = $use_legacy_template;
		if ( ! $use_legacy_template ) {
			add_filter( 'wp_travel_engine_form_field_types', [ $this, 'modify_form_fields' ] );
			add_filter( 'wptravelengine_form_field_options', [ $this, 'modify_form_field_options' ] );
		}
	}

	/**
	 * Modify the form field types.
	 *
	 * @param array $field_types The original field types array.
	 *
	 * @return array The modified field types array.
	 */
	public function modify_form_fields( $field_types ): array {
		if ( ! empty( $field_types[ 'textarea' ][ 'field_class' ] ) ) {
			$field_types[ 'textarea' ][ 'field_class' ] = TextAreaField::class;
		}
		if ( ! empty( $field_types[ 'checkbox' ][ 'field_class' ] ) ) {
			$field_types[ 'checkbox' ][ 'field_class' ] = Checkbox::class;
		}

		return $field_types;
	}

	/**
	 * Modify the form field options for select field.
	 *
	 * @param array $options The original options array.
	 *
	 * @return array The modified options array.
	 */
	public function modify_form_field_options( $options ): array {
		if ( ! empty( $options ) && ! array_key_exists( '', $options ) ) {
			$options = array_merge( array( '' => __( 'None', 'wp-travel-engine' ) ), $options );
		}
		return $options;
	}

	/**
	 * Generates the HTML template for the Material UI form field.
	 *
	 * @param array $field Field properties.
	 * @param string $content Inner content for the field.
	 *
	 * @return string Generated HTML content.
	 */
	public function template( $field, $content ) {
		if ( $this->use_legacy_template ) {
			return parent::template( $field, $content );
		}

		// Start output buffering.
		ob_start();

		$is_required = wptravelengine_toggled( $field[ 'validations' ][ 'required' ] ?? false );
		$is_checkbox = in_array( $field['type'], array( 'checkbox', 'radio', 'file' ) );
		?>
		<?php if ( '' != ( $field[ 'wrapper_parent_class' ] ?? '' ) ):?>
			<div class="<?php echo esc_attr( $field[ 'wrapper_parent_class' ] ); ?>">
		<?php endif; ?>

			<div class="<?php echo esc_attr( $field[ 'wrapper_class' ] ?? '' ) . ( $field['type'] == 'file' ? ' full' : ''	); ?>">
				<div class="wpte-checkout__form-control <?php echo ! $is_checkbox ? 'wpte-material-ui-input-control' : ''; ?>">

					<?php if ( $is_checkbox ): ?>
						<div class="<?php echo "wpte-checkout__{$field['type']}-control"; ?>">
					<?php endif; ?>

							<?php if ( ( $field[ 'name' ] != 'wp_travel_engine_booking_setting[terms_conditions]' ) ): ?>
								<label for="<?php echo esc_attr( $field[ 'id' ] ); ?>"><?php echo esc_html( $field[ 'field_label' ] ) . ( $is_required ? '<span class="wpte-checkout__field-required">*</span>' : '' ); ?></label>
							<?php endif; ?>

							<?php if ( $field['type'] == 'file' ): ?>
								<div class="wpte-checkout__file-description">
									<?php echo esc_html__( 'Max. file size 5MB Supports: JPG, PNG, WebP images', 'wp-travel-engine' ); ?>
								</div>
							<?php endif; ?>

							<?php echo $content; // Render field content dynamically. ?>

							<?php if ( ! $is_checkbox && ( $field[ 'name' ] != 'wp_travel_engine_booking_setting[terms_conditions]' ) ): ?>
								<fieldset>
									<legend><span><?php echo esc_html( $field[ 'field_label' ] ) . ( $is_required ? '<span class="wpte-checkout__field-required">*</span>' : '' ); ?></span></legend>
								</fieldset>
							<?php endif; ?>

					<?php if ( $is_checkbox ): ?>
						</div>
					<?php endif; ?>

				</div>
			</div>

		<?php if ( '' != ( $field[ 'wrapper_parent_class' ] ?? '' ) ):?>
			</div>
		<?php endif;

		// Capture and return the generated content.
		return ob_get_clean();
	}
}
