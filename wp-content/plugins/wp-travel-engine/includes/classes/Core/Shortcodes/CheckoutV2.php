<?php
/**
 * Checkout V2 Shortcode.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Shortcodes;

use WPTravelEngine\Assets;
use WPTravelEngine\Core\Coupons;

class CheckoutV2 extends Checkout {

	/**
	 * Default attributes for the shortcode.
	 *
	 * @return array
	 */
	protected function default_attributes(): array {
		global $wte_cart;

		$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
		$checkout_page_template  = $wptravelengine_settings[ 'checkout_page_template' ] ?? '1.0';
		$display_header_footer   = $wptravelengine_settings[ 'display_header_footer' ] ?? 'no';
		$show_travellers_info    = $wptravelengine_settings[ 'display_travellers_info' ] ?? 'yes';
		$show_emergency_contact  = $wptravelengine_settings[ 'display_emergency_contact' ] ?? '';
		$traveller_details_form  = $wptravelengine_settings[ 'traveller_emergency_details_form' ] ?? 'on_checkout';
		$display_billing_details = $wptravelengine_settings[ 'display_billing_details' ] ?? 'yes';
		$show_additional_note    = $wptravelengine_settings[ 'show_additional_note' ] ?? 'yes';
		$show_coupon_form        = $wptravelengine_settings[ 'show_discount' ] ?? 'yes';
		return array(
			'version'            => $checkout_page_template,
			'header'             => $checkout_page_template == '2.0' && $display_header_footer == 'yes' ? 'default' : 'none',
			'footer'             => $checkout_page_template == '2.0' && $display_header_footer == 'yes' ? 'default' : 'none',
			'checkout-steps'     => 'show',
			'tour-details'       => 'show',
			'tour-details-title' => 'show',
			'cart-summary'       => 'show',
			'cart-summary-title' => 'show',
			'travellers'         => $show_travellers_info == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide',
			'travellers-title'   => 'show',
			'emergency'          => $show_emergency_contact == 'yes' && $traveller_details_form == 'on_checkout' ? 'show' : 'hide',
			'emergency-title'    => 'show',
			'billing'            => $display_billing_details == 'yes' ? 'show' : 'hide',
			'billing-title'      => 'show',
			'additional_note'    => $show_additional_note == 'yes' ? 'show' : 'hide',
			'additional-note-title' => 'show',
			'payment'            => 'show',
			'payment-title'      => 'show',
			'coupon_form'        => $show_coupon_form == 'yes' && Coupons::is_coupon_available() && 'due' !== $wte_cart->get_payment_type() ? 'show' : 'hide',
			'footer_copyright'   => $wptravelengine_settings[ 'footer_copyright' ] ?? '',
		);
	}

	/**
	 * Place order form shortcode callback function.
	 *
	 * @param array $atts The shortcode attributes.
	 *
	 * @return string
	 * @since 1.0
	 */
	public function output( $atts ): string {
		global $wte_cart;

		$wptravelengine_settings = get_option( 'wp_travel_engine_settings', array() );
		$checkout_page_template  = $wptravelengine_settings[ 'checkout_page_template' ] ?? '1.0';
		// Simplified conditional check for outputting based on version
		if ($atts['version'] === '1.0' || ($atts['version'] !== '2.0' && $checkout_page_template == '1.0')) {
			return parent::output($atts);
		}

		Assets::instance()
		      ->enqueue_script( 'trip-checkout' )
		      ->enqueue_style( 'trip-checkout' )
		      ->enqueue_script( 'parsley' )
		      ->enqueue_script( 'wptravelengine-validatejs' )
		      ->dequeue_script( 'wp-travel-engine' )
		      ->dequeue_style( 'wp-travel-engine' );

		ob_start();

		$cart_items = $wte_cart->getItems();
		if ( ! empty( $cart_items ) ) {

			$template_args    = array();

			$atts = apply_filters( 'wptravelengine_checkoutv2_shortcode_attributes', $atts, $this );

			$form_sections = array(
				'travellers'      => 'content-travellers-details',
				'emergency'       => 'content-emergency-details',
				'billing'         => 'content-billing-details',
				'additional_note' => 'content-checkout-note',
				'payment'         => 'content-payments',
			);

			$template_args[ 'form_sections' ] = apply_filters( 'wptravelengine_checkoutv2_form_templates', $form_sections );
			unset( $form_sections );

			foreach ( array_keys( $template_args[ 'form_sections' ] ) as $section ) {
				if ( 'hide' === $atts[ $section ] ?? 'show' ) {
					unset( $template_args[ 'form_sections' ][ $section ] );
				}
			}

			wptravelengine_get_template(
				'template-checkout/content-checkout.php',
				wptravelengine_get_checkout_template_args(
					array(
						'attributes'     => $atts,
						'deposit_amount' => $wte_cart->get_totals()[ 'partial_total' ],
						'due_amount'     => $wte_cart->get_totals()[ 'due_total' ],
					)
				)
			);

		} else {
			echo __(
				'Sorry, you may not have selected the number of travellers for this trip. Please select number of travellers and confirm your booking. Thank you.',
				'wp-travel-engine'
			);
		}

		return ob_get_clean();
	}


}
