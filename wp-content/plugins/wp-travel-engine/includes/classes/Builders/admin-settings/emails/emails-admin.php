<?php
/**
 * Admin Email Settings.
 *
 * @since 6.2.0
 */

$email_tags = apply_filters( 'wptravelengine_admin_email_template_tags', array(
	'{trip_url}'                  => __( 'The trip URL for each booked trip', 'wp-travel-engine' ),
	'{name}'                      => __( 'The buyer\'s first name', 'wp-travel-engine' ),
	'{fullname}'                  => __( 'The buyer\'s full name, first and last', 'wp-travel-engine' ),
	'{user_email}'                => __( 'The buyer\'s email address', 'wp-travel-engine' ),
	'{billing_address}'           => __( 'The buyer\'s billing address', 'wp-travel-engine' ),
	'{city}'                      => __( 'The buyer\'s city', 'wp-travel-engine' ),
	'{country}'                   => __( 'The buyer\'s country', 'wp-travel-engine' ),
	'{tdate}'                     => __( 'The starting date of the trip', 'wp-travel-engine' ),
	'{date}'                      => __( 'The trip booking date', 'wp-travel-engine' ),
	'{traveler}'                  => __( 'The total number of traveller(s)', 'wp-travel-engine' ),
	'{tprice}'                    => __( 'The trip price', 'wp-travel-engine' ),
	'{price}'                     => __( 'The total payment made of the booking', 'wp-travel-engine' ),
	'{total_cost}'                => __( 'The total price of the booking', 'wp-travel-engine' ),
	'{due}'                       => __( 'The due balance', 'wp-travel-engine' ),
	'{sitename}'                  => __( 'Your site name', 'wp-travel-engine' ),
	'{booking_url}'               => __( 'The trip booking link', 'wp-travel-engine' ),
	'{ip_address}'                => __( 'The buyer\'s IP Address', 'wp-travel-engine' ),
	'{booking_id}'                => __( 'The booking order ID', 'wp-travel-engine' ),
	'{booking_details}'           => __( 'The booking details: Booked trips, Extra Services, Traveller details etc', 'wp-travel-engine' ),
	'{traveler_data}'             => __( 'The traveller details: Traveller details and Emergency Contact Details', 'wp-travel-engine' ),
	'{payment_method}'            => __( 'Payment Method used to checkout.', 'wp-travel-engine' ),
	'{billing_details}'			  => __( 'The billing details filled in new checkout template.', 'wp-travel-engine' ),
	'{traveller_details}'		  => __( 'The traveler\'s details filled in new checkout template.', 'wp-travel-engine' ),
	'{emergency_details}'		  => __( 'The emergency contact details filled in new checkout template.', 'wp-travel-engine' ),
	'{additional_note}'		 	  => __( 'The additional note filled in new checkout template.', 'wp-travel-engine' ),
) );

return apply_filters(
	'emails-admin',
	array(
		'title'  => __( 'Admin', 'wp-travel-engine' ),
		'order'  => 5,
		'id'     => 'emails_admin',
		'icon'   => 'email',
		'fields' => apply_filters(
			'emails-admin-fields',
			array(
				array(
					'label'       => __( 'Sale Notification Emails', 'wp-travel-engine' ),
					'description' => __( 'Enter the email address(es) that should receive a notification anytime a sale is made, separated by comma (,) and no spaces.', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'name'        => 'admin_email.email_addresses',
					'divider'     => true,
					'multiple'    => true,
				),
				array(
					'label'       => __( 'Admin Notification', 'wp-travel-engine' ),
					'description' => __( 'Turn this off if you do not want to receive sales notification emails.', 'wp-travel-engine' ),
					'field_type'  => 'SWITCH',
					'name'        => 'admin_email.enable',
					'divider'     => true,
				),
				array(
					'label'       => __( 'Booking Notification', 'wp-travel-engine' ),
					'description' => __( 'Turn this off if you only want to receive payment notification emails and not booking notifications.', 'wp-travel-engine' ),
					'field_type'  => 'SWITCH',
					'name'        => 'admin_booking_notification.enable',
					'divider'     => true,
				),
				array(
					'label'       => __( 'Booking Notification Subject', 'wp-travel-engine' ),
					'description' => __( 'Enter the booking subject for the booking notification email. Available Tags: {booking_id}, {payment_id}, {sitename}, {name}, {fullname}', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'default'     => 'New Booking Order has been received ({booking_id})',
					'name'        => 'admin_booking_notification.subject',
					'divider'     => true,
				),
				array(
					'label'       => __( 'Booking Notification', 'wp-travel-engine' ),
					'description' => __( 'This template will be used when a booking request has been received. <a href="#" data-preview-email="?_action=email-template-preview&template_type=order&pid=0&to=admin">Preview Template</a>', 'wp-travel-engine' ),
					'field_type'  => 'EDITOR',
					'name'        => 'admin_booking_notification.template',
					'divider'     => true,
				),
				array(
					'label'       => __( 'Payment Notification Subject', 'wp-travel-engine' ),
					'description' => __( 'Enter the booking subject for the purchase receipt email. Available Tags: {booking_id}, {payment_id}, {sitename}, {name}, {fullname}', 'wp-travel-engine' ),
					'field_type'  => 'TEXT',
					'default'     => 'Payment has been received for {booking_id}',
					'name'        => 'admin_payment_notification.subject',
					'divider'     => true,
				),
				array(
					'label'       => __( 'Payment Notification', 'wp-travel-engine' ),
					'description' => __( 'This email template will be used when ever a payment received. <a href="#" data-preview-email="?_action=email-template-preview&template_type=order_confirmation&pid=0&to=admin">Preview Template</a>', 'wp-travel-engine' ),
					'field_type'  => 'EDITOR',
					'name'        => 'admin_payment_notification.template',
					'divider'     => true,
				),
				array(
					'description' => __( 'Enter the text that is sent as sale notification email after completion of a purchase. HTML is accepted.', 'wp-travel-engine'),
					'field_type'  => 'FIELD_HEADER',
				),
				array(
					'field_type' => 'TITLE',
					'title'      => __( 'Available Template Tags-', 'wp-travel-engine' ),
				),
				array(
					'field_type' => 'TEMPLATE_TAGS',
					'value'      => $email_tags,
					'name'       => 'emails.email_tags',
					'divider'    => true,
				),
			)
		),
	),
);
