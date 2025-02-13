<?php

namespace WPTravelEngine\Builders\FormFields;

/**
 * Default Form Fields.
 *
 * @since 6.3.0
 */

class DefaultFormFields extends \WTE_Default_Form_Fields {

	/**
	 * Traveller Information form fields.
	 *
	 * @return array
	 */
	public static function traveller_form_fields(): array {

		return apply_filters( 'wp_travel_engine_traveller_info_fields_display', array(
			'traveller_first_name' => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-checkout__form-col',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'First Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][fname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'priority'      => 20,
				'default_field' => true,
			),

			'traveller_last_name' => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-checkout__form-col',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][lname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'priority'      => 30,
				'default_field' => true,
			),

			'traveller_email' => array(
				'type'          => 'email',
				'wrapper_class' => 'wpte-checkout__form-col',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'Email', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][email]',
				'validations'   => array(
					'required' => true,
				),
				'priority'      => 50,
				'default_field' => true,
			),
			'traveller_phone' => array(
				'type'          => 'tel',
				'wrapper_class' => 'wpte-checkout__form-col',
				'field_label'   => __( 'Phone', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][phone]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'priority'      => 100,
				'default_field' => true,
				'class'         => 'wpte-checkout__input',
			),

			'traveller_country' => array(
				'type'          => 'country_dropdown',
				'field_label'   => __( 'Country', 'wp-travel-engine' ),
				'wrapper_class' => 'wpte-checkout__form-col',
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][travelers][country]',
				'validations'   => array(
					'required' => true,
				),
				'priority'      => 80,
				'default_field' => true,
				'class'         => 'wpte-checkout__input',
			),

		) );
	}

	/**
	 * @return array[]
	 */
	public static function emergency_form_fields(): array {
		return static::emergency_contact();
	}

	/**
	 * Emergency Information form fields.
	 *
	 * @return array[]
	 */
	public static function emergency_contact(): array {
		return apply_filters( 'wp_travel_engine_emergency_contact_fields_display', array(
			'traveller_emergency_first_name' => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-checkout__form-col',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'First Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][fname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][fname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'priority'      => 140,
				'default_field' => true,
			),

			'traveller_emergency_last_name' => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-checkout__form-col',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'Last Name', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][lname]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][lname]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'priority'      => 150,
				'default_field' => true,
			),

			'traveller_emergency_phone' => array(
				'type'          => 'tel',
				'wrapper_class' => 'wpte-checkout__form-col',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'Phone', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][phone]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][phone]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'priority'      => 160,
				'default_field' => true,
			),

			'traveller_emergency_country' => array(
				'type'          => 'country_dropdown',
				'field_label'   => __( 'Country', 'wp-travel-engine' ),
				'wrapper_class' => 'wpte-checkout__form-col',
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][country]',
				'class'         => 'wpte-checkout__input',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][country]',
				'validations'   => array(
					'required' => true,
				),
				'priority'      => 80,
				'default_field' => true,
			),

			'traveller_emergency_relation' => array(
				'type'          => 'text',
				'wrapper_class' => 'wpte-checkout__form-col',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'Relationship', 'wp-travel-engine' ),
				'name'          => 'wp_travel_engine_placeorder_setting[place_order][relation][relation]',
				'id'            => 'wp_travel_engine_placeorder_setting[place_order][relation][relation]',
				'validations'   => array(
					'required'  => true,
					'maxlength' => '50',
					'type'      => 'alphanum',
				),
				'priority'      => 170,
				'default_field' => true,
			),
		) );
	}

	/**
	 * Additional Note
	 */
	public static function additional_note() {
		return array(
			'traveller_additional_note' => array(
				'type'          => 'textarea',
				'wrapper_class' => 'wpte-checkout__box-content',
				'class'         => 'wpte-checkout__input',
				'field_label'   => __( 'Add any specific requests or extra details here...', 'wp-travel-engine' ),
				'name'          => 'wptravelengine_additional_note',
				'id'            => 'wptravelengine_additional_note',
				'validations'   => array(
					'required' => false,
				),
				'priority'      => 20,
				'default_field' => true,
			),

		);
	}

	/**
	 * Privacy form fields.
	 */
	public static function privacy_form_fields() {
		$options = get_option( 'wp_travel_engine_settings', array() );

		$privacy_policy_form_field = array();
		$default_label             = __( 'Check the box to confirm you\'ve read and agree to our <a href="%1$s" id="terms-and-conditions" target="_blank"> Terms and Conditions</a> and <a href="%2$s" id="privacy-policy" target="_blank">Privacy Policy</a>.', 'wp-travel-engine' );
		$checkbox_options = array(
			'0' => sprintf(
				!empty( $options['privacy_policy_msg'] ) ?
				$options['privacy_policy_msg'] . ' <a href="%1$s" id="terms-and-conditions" target="_blank">' . __( 'Terms and Conditions', 'wp-travel-engine' ) . '</a>'. __( ' and', 'wp-travel-engine' ) . '  <a href="%2$s" id="privacy-policy" target="_blank">' . __( 'Privacy Policy', 'wp-travel-engine' ) . '</a>.' :
				$default_label,
				esc_url( get_permalink($options['pages']['wp_travel_engine_terms_and_conditions'] ?? '' ) ),
				esc_url( get_privacy_policy_url() )
			),
		);
		if ( function_exists( 'get_privacy_policy_url' ) ) {
			$privacy_policy_form_field[ 'privacy_policy_info' ] = array(
				'type'              => 'checkbox',
				'options'           => $checkbox_options,
				'name'              => 'wp_travel_engine_booking_setting[terms_conditions]',
				'wrapper_class'     => 'wpte-checkout__form-control',
				'id'                => 'wp_travel_engine_booking_setting[terms_conditions]',
				'default'           => '',
				'validations'       => array(
					'required' => true,
				),
				'option_attributes' => array(
					'required'                      => true,
					'data-msg'                      => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
					'data-parsley-required-message' => __( 'Please make sure to check the privacy policy checkbox', 'wp-travel-engine' ),
				),
				'priority'          => 70,
			);

		}

		return apply_filters( 'wte_booking_privacy_fields', $privacy_policy_form_field );
	}
}
