<?php

/**
 * Test Email Settings.
 *
 * @since 6.2.0
 */

return apply_filters(
	'emails-test',
	array(
		'title'  => __( 'Test Email', 'wp-travel-engine' ),
		'order'  => 20,
		'id'     => 'emails_test',
		'fields' => array(
			array(
				'field_type' => 'ALERT',
				'content'    => sprintf(
					__( '<strong>NOTE: </strong>After sending the test email and receiving a success message, please check your inbox. If your server is properly configured for email sending, you will receive the test email. However, if something seems amiss or the email does not arrive, please refer to the <a href="%s">Email FAQ page</a> for troubleshooting assistance.', 'wp-travel-engine' ),
					'https://docs.wptravelengine.com/article/email-troubleshooting/'
				),
			),
			array(
				'label'      => __( 'Send Test Email', 'wp-travel-engine' ),
				'field_type' => 'TEST_EMAIL',
				'_nonce'     => wp_create_nonce( 'wptravelengine_test_email_nonce' ),
				'default'    => wp_get_current_user()->user_email,
			),
		),
	)
);
