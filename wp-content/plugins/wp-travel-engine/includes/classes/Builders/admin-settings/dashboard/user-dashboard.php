<?php
/**
 * User Dashboard Tab Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'user_dashboard_settings',
	array(
		'title'  => __( 'User Dashboard', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'dashboard-user-settings',
		'fields' => array(
			array(
				'divider'     => true,
				'label'       => __( 'Automatically Generate User Account', 'wp-travel-engine' ),
				'description' => __( 'It automatically creates user account (username and password) when booking a trip and sends the details to the customer', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'generate_user_account',
			),
			array(
				'condition'  => 'generate_user_account === false',
				'field_type' => 'GROUP',
				'fields'     => array(
					array(
						'divider'     => true,
						'label'       => __( 'Require Registration for Booking', 'wp-travel-engine' ),
						'description' => __( 'Customers must sign in or create an account to complete trip bookings.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'enable_booking_registration',
					),
					array(
						'divider'     => true,
						'label'       => __( 'Customer Registration', 'wp-travel-engine' ),
						'description' => __( 'Disable to prevent customers from creating new accounts on my account page.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'enable_account_registration',
					),
					array(
						'divider'     => true,
						'label'       => __( 'Generate Username From Customer Email', 'wp-travel-engine' ),
						'description' => __( 'The customer\'s email will be used to generate their username.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'generate.user_name',
					),
					array(
						'label'       => __( 'Generate Secure Password', 'wp-travel-engine' ),
						'description' => __( 'Customers will receive a strong, randomly generated password upon signup.', 'wp-travel-engine' ),
						'field_type'  => 'SWITCH',
						'name'        => 'generate.secure_password',
					),
				),
			),
		),
	),
);
