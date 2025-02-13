<?php
/**
 * Thank You Page Template Filters.
 *
 * @since 6.3.3
 */

namespace WPTravelEngine\Filters;

use WPTravelEngine\Builders\FormFields\TravellerFormFields;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Core\Models\Post\Payment;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Pages\Checkout;
use WPTravelEngine\PaymentGateways\CheckPayment;
use WPTravelEngine\PaymentGateways\DirectBankTransfer;

/**
 * Thank You Page Template Filters.
 *
 * @since 6.3.3
 */
class ThankYouPageTemplate extends CheckoutPageTemplate {

	public Booking $booking;

	public Payment $payment;

	/**
	 * @var ?Cart
	 */
	public ?Cart $cart = null;

	/**
	 * Constructor.
	 *
	 * @param Booking $booking
	 * @param Payment $payment
	 */
	public function __construct( Booking $booking, Payment $payment ) {
		$this->booking = $booking;
		$this->payment = $payment;

		$this->set_cart();

		add_action( 'shutdown', function () {
			$this->cart->clear();
		} );

	}

	/**
	 * @return void
	 */
	public function hooks() {
		add_action( 'wptravelengine_thankyou_before_content', [ $this, 'page_header' ] );
		add_action( 'wptravelengine_thankyou_content', [ $this, 'page_content' ] );
		add_action( 'wptravelengine_thankyou_booking_details', [ $this, 'booking_details' ] );
		add_action( 'wptravelengine_thankyou_after_booking_details', [ $this, 'after_booking_details' ] );
		add_action( 'wptravelengine_thankyou_cart_summary', [ $this, 'cart_summary' ] );
		add_action( 'thankyou_template_parts_tour-details', [ $this, 'tour_details' ] );
		add_action( 'thankyou_template_parts_cart-summary', [ $this, 'cart_summary_partial' ] );
		add_action( 'thankyou_template_parts_cart-summary', [ $this, 'print_payment_details' ], 11 );
		add_action( 'wptravelengine_thankyou_booking_details_direct_bank_transfer', [ $this, 'print_bank_details' ] );
		add_action( 'wptravelengine_thankyou_booking_details_check_payments', [ $this, 'print_check_instruction', ] );
	}

	/**
	 * Print Check Instruction.
	 *
	 * @since 6.3.3
	 */
	public function print_check_instruction( $payment_id ) {
		$payment = wptravelengine_get_payment( $payment_id );
		if ( $payment ) {
			$check_payment = new CheckPayment();
			$check_payment->print_instruction( $payment_id );
		}
	}

	/**
	 * After Booking Details.
	 *
	 * @since 6.3.3
	 */
	public function after_booking_details() {
		do_action( "wptravelengine_thankyou_booking_details_{$this->payment->get_payment_gateway()}", $this->payment->get_id() );
	}

	/**
	 * Print Bank Details.
	 *
	 * @since 6.3.3
	 */
	public function print_bank_details( $payment_id ) {
		$payment = wptravelengine_get_payment( $payment_id );
		if ( $payment ) {
			$direct_bank_transfer = new DirectBankTransfer();

			$direct_bank_transfer->print_instruction( $payment_id );
		}
	}

	/**
	 * Print Payment Details.
	 *
	 * @since 6.3.3
	 */
	public function print_payment_details() {

		$payment_amount = $this->payment->get_payable_amount();
		$payment_status = $this->payment->get_payment_status();
		$remarks        = __( 'Your booking order has been placed. You booking will be confirmed after payment confirmation/settlement.', 'wp-travel-engine' );
		wptravelengine_get_template( 'thank-you/content-payment-details.php',
			compact(
				'payment_amount',
				'payment_status',
				'remarks'
			)
		);
	}

	/**
	 * @return Cart
	 */
	public function get_cart(): ?Cart {
		return $this->cart;
	}

	/**
	 * @return void
	 */
	protected function set_cart() {
		if ( ! $this->cart ) {
			global $wte_cart;
			$this->cart  = $wte_cart;
			$order_items = $this->booking->get_meta( 'order_trips' );
			$cart_info   = $this->booking->get_meta( 'cart_info' );
			if ( isset( $cart_info[ 'discounts' ] ) ) {
				foreach ( $cart_info[ 'discounts' ] as $discount_id => $discount ) {
					$this->cart->add_discount_values( $discount_id, $discount[ 'name' ], $discount[ 'type' ], $discount[ 'value' ], );
				}
			}

			$items = array();

			foreach ( $order_items as $order_item ) {
				$items[] = Item::from_order_item( $order_item, $this->booking, $wte_cart );
			}
			$this->cart->setItems( $items );
			$this->cart->set_payment_gateway( $this->payment->get_meta( 'payment_gateway' ) );

			$this->cart->calculate_totals();
		}

		return $this->cart;
	}

	/**
	 * Tour Details.
	 *
	 * @since 6.3.3
	 */
	public function tour_details() {
		$tour_details = Checkout::instance( $this->get_cart() )->get_tour_details();
		wptravelengine_get_template(
			'template-checkout/content-tour-details.php',
			array_merge( compact( 'tour_details' ), array(
				'content_only' => true,
			) )
		);
	}

	/**
	 * Cart Summary Partial.
	 *
	 * @since 6.3.3
	 */
	public function cart_summary_partial() {
		$this->print_cart_summary( array(
			'show_coupon_form' => false,
			'content_only'     => true,
			'show_title'       => true,
		) );
	}

	/**
	 * Page Header.
	 *
	 * @since 6.3.3
	 */
	public function page_header() {

		if ( ! $thankyou_message = wptravelengine_settings()->get( 'confirmation_msg', false ) ) {
			$thankyou_message = __( 'Thank you for booking the trip. Please check your email for confirmation.🎉', 'wp-travel-engine' );
		}
		wptravelengine_get_template( 'thank-you/content-page-header.php', compact( 'thankyou_message' ) );
	}

	/**
	 * Page Content.
	 *
	 * @since 6.3.3
	 */
	public function page_content() {
		wptravelengine_get_template( 'thank-you/content-thank-you.php' );
	}

	/**
	 * Booking Details.
	 *
	 * @since 6.3.3
	 */
	public function booking_details() {

		$order_trips        = $this->booking->get_meta( 'order_trips' );
		$additional_note    = $this->booking->get_meta( 'wptravelengine_additional_note' );
		$_traveller_details = $this->booking->get_meta( 'wptravelengine_travelers_details' );

		$order_trip = reset( $order_trips );

		$trip = new Trip( $order_trip[ 'ID' ] );

		$start_datetime  = $order_trip[ 'datetime' ];
		$trip_start_date = wptravelengine_format_trip_datetime( $start_datetime );
		$trip_end_date   = wptravelengine_format_trip_end_datetime( $start_datetime, $trip );

		$traveller_details = array();
		if ( is_array( $_traveller_details ) ) {
			foreach ( $_traveller_details as $traveller ) {
				$traveller_form_fields = new TravellerFormFields();
				$traveller_details[]   = $traveller_form_fields->with_values( $traveller );
			}
		}

		wptravelengine_get_template( 'thank-you/content-booking-details.php', compact(
			'trip_start_date',
			'trip_end_date',
			'additional_note',
			'traveller_details'
		) );
	}

	/**
	 * Cart Summary.
	 *
	 * @since 6.3.3
	 */
	public function cart_summary() {
		wptravelengine_get_template(
			'thank-you/content-cart-summary.php',
		);
	}

}
