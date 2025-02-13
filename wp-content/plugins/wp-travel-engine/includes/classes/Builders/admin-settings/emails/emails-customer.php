<?php
/**
 * Customer Email Settings.
 *
 * @since 6.2.0
 */

$email_tags = apply_filters( 'wptravelengine_customer_email_template_tags', array(
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
	'{bank_details}'           	  => __( 'Banks Accounts Details. This tag will be replaced with the bank details and sent to the customer receipt email when Bank Transfer method has chosen by the customer.', 'wp-travel-engine' ),
	'{check_payment_instruction}' => __( 'Instructions to make check payment.', 'wp-travel-engine' ),
	'{booking_details}'           => __( 'The booking details: Booked trips, Extra Services, Traveller details etc', 'wp-travel-engine' ),
	'{traveler_data}'             => __( 'The traveller details: Traveller details and Emergency Contact Details', 'wp-travel-engine' ),
	'{payment_method}'            => __( 'Payment Method used to checkout.', 'wp-travel-engine' ),
) );


return apply_filters(
	'emails-customer',
	array(
		'title'  => __( 'Customer', 'wp-travel-engine' ),
		'order'  => 10,
		'id'     => 'emails_customer',
		'fields' => apply_filters(
			'emails-admin-fields',
			array(
				array(
					'type'       => 'warning',
					'field_type' => 'ALERT',
					'content'    => __( '<strong>NOTE: </strong>Looking to personalize your booking and payment notifications? The Email Customizer add-on enables the creation of personalized email templates for booking and payment notifications, offering customization and flexibility. <a href="https://wptravelengine.com/plugins/email-customizer/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank">Get Email Customizer extension now.</a> ', 'wp-travel-engine' ),
				),
				array(
					'type'       => 'warning',
					'field_type' => 'ALERT',
					'content'    => __( '<strong>NOTE: </strong>Looking to optimize your website\'s booking process with personalized trip-specific emails? With Per Trip Email, customize multiple booking emails according to each trip, enhancing communication with your customers.  <a href="https://wptravelengine.com/plugins/per-trip-emails/?utm_source=free_plugin&utm_medium=pro_addon&utm_campaign=upgrade_to_pro" target="_blank"> Get Per Trip Email extension now.</a> ', 'wp-travel-engine' ),
				),
				array(
					'label'       => __( 'Customer Notification', 'wp-travel-engine' ),
					'description' => __( 'Turn this off if you do not want to send email notification to customer.', 'wp-travel-engine' ),
					'field_type'  => 'SWITCH',
					'name'        => 'customer_booking_notification.enable',
					'divider'     => true,
				),
				array(
					'field_type' => 'GROUP',
					'condition'  => 'customer_booking_notification.enable === true',
					'fields'     => array(
						array(
							'label'       => __( 'From Name', 'wp-travel-engine' ),
							'description' => __( 'Enter the name the purchase receipts are sent from. This should probably be your site or shop name.', 'wp-travel-engine' ),
							'field_type'  => 'TEXT',
							'divider'     => true,
							'name'        => 'customer_receipt_details.admin_name',
						),
						array(
							'label'       => __( 'From Email', 'wp-travel-engine' ),
							'description' => __( 'Enter the mail address from which the purchase receipts will be sent. This will act as as the from and reply-to address.', 'wp-travel-engine' ),
							'field_type'  => 'TEXT',
							'divider'     => true,
							'name'        => 'customer_receipt_details.admin_email_address',
						),
						array(
							'label'       => __( 'Booking Email Subject', 'wp-travel-engine' ),
							'description' => __( 'Enter the subject line for the booking notification email. Available Tags: {booking_id}, {payment_id}, {sitename}, {name}, {fullname}', 'wp-travel-engine' ),
							'field_type'  => 'TEXT',
							'default'     => 'Your Booking Order has been placed ({booking_id}))',
							'divider'     => true,
							'name'        => 'customer_booking_notification.subject',
						),
						array(
							'label'       => __( 'Booking Notification', 'wp-travel-engine' ),
							'description' => __( 'This template will be used when a booking request has been made by the customer. <a href="#" data-preview-email="?_action=email-template-preview&template_type=order&pid=0&to=customer">Preview Template</a>', 'wp-travel-engine' ),
							'field_type'  => 'EDITOR',
							'divider'     => true,
							'name'        => 'customer_booking_notification.template',
						),
						array(
							'label'       => __( 'Purchase Email Subject', 'wp-travel-engine' ),
							'description' => __( 'Enter the subject line for the purchase receipt email. Available Tags: {booking_id}, {payment_id}, {sitename}, {name}, {fullname}', 'wp-travel-engine' ),
							'field_type'  => 'TEXT',
							'default'     => 'Your payment has been confirmed for {booking_id}',
							'divider'     => true,
							'name'        => 'customer_purchase_notification.subject',
						),
						array(
							'label'       => __( 'Purchase Receipt', 'wp-travel-engine' ),
							'description' => __( 'This email template will be used when ever a payment received. <a href="#" data-preview-email="?_action=email-template-preview&template_type=order_confirmation&pid=0&to=customer">Preview Template</a>', 'wp-travel-engine' ),
							'field_type'  => 'EDITOR',
							'divider'     => true,
							'name'        => 'customer_purchase_notification.template',
						),
					),
				),
				array(
					'description' => __( 'Enter the text that is sent as purchase receipt email to users after completion of a successful purchase. HTML is accepted.', 'wp-travel-engine'),
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
				array(
					'field_type' => 'TITLE',
					'title'      => __( 'Trip Code', 'wp-travel-engine' ),
				),
				array(
					'field_type' => 'TEMPLATE_TAGS',
					'value'      => array( '{trip_code}' => __( 'Trip Code', 'wp-travel-engine' ) ),
					'name'       => 'emails.trip_code',
					'divider'    => true,
				),
			),
		),
	)
);
