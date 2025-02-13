<?php
/**
 * Enquiry Email Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'emails-enquiry',
	array(
		'title'  => __( 'Enquiry Emails', 'wp-travel-engine' ),
		'order'  => 15,
		'id'     => 'emails_enquiry',
		'fields' => array(
			array(
				'label'      => __( 'Notification Email(s)', 'wp-travel-engine' ),
				'help'       => __( 'Enter the email address(es) to receive notifications whenever an enquiry is made. Separate multiple addresses with a comma (,) without spaces.', 'wp-travel-engine' ),
				'field_type' => 'TEXT',
				'name'       => 'enquiry_form.email_addresses',
				'divider'    => true,
				'multiple'   => true,
			),
			array(
				'label'       => __( 'Email Subject For Enquiry', 'wp-travel-engine' ),
				'description' => __( 'Email subject for admin if a query is received. Supported Email tags - {enquirer_name}, {enquirer_email}', 'wp-travel-engine' ),
				'field_type'  => 'TEXT',
				'name'        => 'enquiry_form.email_subject',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Customer Enquiry Notification', 'wp-travel-engine' ),
				'description' => __( 'Enable this to send enquiry notification emails to the customer.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enquiry_form.notify_customer',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Custom Enquiry Form', 'wp-travel-engine' ),
				'description' => __( 'Enable this feature to utilize a custom form. Please ensure that a notification email is included within the custom form.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enquiry_form.custom_form.enable',
				'divider'     => true,
			),
			array(
				'field_type' => 'GROUP',
				'condition'  => 'enquiry_form.custom_form.enable === true',
				'fields'     => array(
					array(
						'label'       => __( 'Form Shortcode', 'wp-travel-engine' ),
						'description' => __( 'Add the custom form shortcode. We have made WP Travel Engine compatible with popular forms likes Gravity Form, Ninja Forms and WPForms.', 'wp-travel-engine' ),
						'field_type'  => 'TEXT',
						'name'        => 'enquiry_form.custom_form.shortcode',
						'divider'     => true,
					),
				),
			),
			array(
				'label'       => __( 'Show Enquiry Form', 'wp-travel-engine' ),
				'description' => __( 'Turn this off to hide the enquiry form on Trip Page.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enquiry_form.enable',
				'divider'     => true,
			),
			array(
				'label'       => __( 'Show Powered By Link', 'wp-travel-engine' ),
				'description' => __( 'Turn this off to hide Powered by Link from enquiry and booking emails.', 'wp-travel-engine' ),
				'field_type'  => 'SWITCH',
				'name'        => 'enquiry_form.powered_by_link',
				'divider'     => true,
			),
		),
	)
);
