<?php
/**
 * Checkout page class.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Pages;

use WPTravelEngine\Abstracts\BasePage;
use WPTravelEngine\Builders\FormFields\DefaultFormFields;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Models\Post\Trip;
use WPTravelEngine\Interfaces\CartItem;
use WPTravelEngine\Core\Models\Settings\Options;
use WPTravelEngine\Core\Models\Post\Booking;
use WPTravelEngine\Traits\Singleton;


/**
 * Checkout page class.
 */
class Checkout extends BasePage {

	use Singleton;

	public Cart $cart;

	protected array $global_settings;

	public function __construct( Cart $cart ) {
		$this->cart            = $cart;
		$this->global_settings = wptravelengine_settings()->get();
	}

	/**
	 * Get billing form fields.
	 *
	 * @return array
	 */
	public function get_billing_form_fields(): array {
		return DefaultFormFields::billing_form_fields();
	}

	/**
	 * Get traveller form fields.
	 *
	 * @return array
	 */
	public function get_travellers_form_fields(): array {
		return DefaultFormFields::traveller_information();
	}

	/**
	 * Get emergency contact form fields.
	 *
	 * @return array
	 */
	public function get_emergency_contact_fields(): array {
		return DefaultFormFields::emergency_form_fields();
	}

	/**
	 * Get payment options.
	 *
	 * @return array
	 */
	public function get_payment_options() {
		return $this->get_active_payment_methods();
	}

	/**
	 * Retrieves active and sorted payment methods.
	 *
	 * This method fetches payment methods from global settings and sorts them based on the cart's checkout process.
	 * It also prepares icons for each payment method.
	 *
	 * @return array Sorted list of active payment methods with additional details.
	 */
	public function get_active_payment_methods() {
		// Retrieve default gateway and active payment methods.
		$default_gateway        = $this->global_settings[ 'default_gateway' ] ?? 'booking_only';
		$active_payment_methods = wp_travel_engine_get_active_payment_gateways();

		// Retrieve and filter enabled payment gateways.
		$payment_gateways = Options::get( 'wptravelengine_payment_gateways', array_map( function ( $gateway ) {
			return [
				'id' => $gateway['gateway_id'] ?? '',
				'name' => $gateway['label'] ?? '',
				'enable' => true
			];
		}, $active_payment_methods ) );


		$enabled_gateways = array_filter( $payment_gateways, fn ( $gateway ) => $gateway[ 'enable' ] ?? false );

		// Prepare mapping of gateway IDs to their details.
		$gateway_details = [];
		foreach ( $enabled_gateways as $gateway ) {
			$gateway_details[ $gateway[ 'id' ] ] = $gateway;
		}

		// Sort and prepare gateway details.
		$sorted_gateways = [];

        foreach ( $gateway_details as $id => $details ) {
            if ( ! isset( $active_payment_methods[$id] ) ) {
                continue;
            }

            $method = $active_payment_methods[$id];

			// Get display icon.
            $display_icon = $method['display_icon'] ?? '';
            $icon_dir = apply_filters( 'wptravelengine_payment_gateway_icon_dir', WP_TRAVEL_ENGINE_FILE_URL . 'assets/images/paymentgateways/frontend/', compact( 'id' ) );
            $icon_url = $display_icon ?: $icon_dir . $id . '.png';

            $sorted_gateways[$id] = array_merge( $method, [
                'icon_url'        => $this->prepare_icon_markup($icon_url),
                'default_gateway' => $default_gateway === $id,
                'description'     => in_array( $id, ['check_payments', 'direct_bank_transfer']) ? ($method['description'] ?? '' ) : '',
            ]);
        }

		return $sorted_gateways;
	}

	/**
	 * Get payment type.
	 *
	 * @return string
	 */
	public function get_payment_type() {
		return $this->cart->get_payment_type();
	}

	/**
	 * Check if full payment is enabled.
	 *
	 * @return bool
	 */
	public function is_full_payment_enabled(): bool {
		$down_payment_settings = $this->get_down_payment_settings();
		return ( $down_payment_settings[ 'global_full_payment' ] ?? true );
	}

	/**
	 * Get down payment settings.
	 *
	 * @return array
	 */
	public function get_down_payment_settings() {
		$cart_items = wptravelengine_cart()->getItems( true );
		$cart_item  = reset( $cart_items );
		if ( ! empty( $cart_item ) ) {
			$down_payment_settings = $cart_item->down_payment_settings();
		}

		return $down_payment_settings ?? array();
	}

	/**
	 * Get down payment type.
	 *
	 * @return string
	 */
	public function get_down_payment_type() {
		$down_payment_settings = $this->get_down_payment_settings();

		return $down_payment_settings[ 'type' ] ?? '';
	}

	/**
	 * Get down payment value.
	 *
	 * @return int
	 */
	public function get_down_payment_value() {
		$down_payment_settings = $this->get_down_payment_settings();

		return $down_payment_settings[ 'value' ] ?? 0;
	}

	/**
	 * Get full payment amount.
	 *
	 * @return string
	 */
	public function get_full_payment_amount() {
		$value = ( $this->get_down_payment_type() ?? '' ) === 'amount' ? wptravelengine_the_price( $this->cart->get_totals()[ 'total' ], false, false ) : '100%';

		return $value;
	}

	/**
	 * Get down payment amount.
	 *
	 * @return string
	 */
	public function get_down_payment_amount() {
		$is_amount = ( $this->get_down_payment_type() ?? '' ) === 'amount';
		$value     = $this->get_down_payment_value() ?? 0;

		return $is_amount ? wptravelengine_the_price( $value, false, false ) : "{$value}%";
	}

	/**
	 * Get due payment amount.
	 *
	 * @return string
	 */
	public function get_due_payment_amount() {
		$payment_type = $this->cart->get_payment_type();
		$due_amount   = $payment_type === 'due' ? $this->get_due_amount( $this->cart ) : null;

		return $due_amount;
	}

	/**
	 * Get due amount.
	 *
	 * @param Cart $cart
	 *
	 * @return string
	 */
	public function get_due_amount( $cart ) {
		$booking = Booking::make( $cart->get_booking_ref() );

		return wte_get_formated_price( $booking->get_due_amount() );
	}


	/**
	 * Prepares the icon markup based on the URL or SVG content.
	 *
	 * This method checks if the icon URL contains SVG content and returns appropriate HTML markup.
	 *
	 * @param string $icon_url URL or SVG content for the payment method icon.
	 *
	 * @return string HTML markup for the icon.
	 */
	private function prepare_icon_markup( $icon_url ) {
		if ( empty( $icon_url ) ) {
			return '';
		}

		return strpos( $icon_url, '<svg' ) !== false ? $icon_url : '<img src="' . esc_url( $icon_url ) . '" alt="Payment Method Icon">';
	}

	/**
	 * Get additional line items.
	 *
	 * @return array
	 */
	public function get_additional_line_items(): array {

		$rows       = array();
		$cart_items = $this->cart->getItems( true );
		foreach ( $cart_items as $cart_item ) {
			$additional_line_items = $cart_item->get_additional_line_items();

			$_rows = array();
			if ( count( $cart_items ) > 1 ) {
				$_rows[ 'trip_title' ] = sprintf( '<tr><td><strong>%s</strong></td><td></td></tr>', get_the_title( $cart_item->trip_id ) );
			}

			foreach ( $additional_line_items as $item ) {
				if ( $item instanceof CartItem ) {
					$item = array( $item->item_type => $item );
				}
				foreach ( $item as $_item ) {
					$_rows[ $_item->item_type ][] = sprintf(
						'<tr><td>%s</td><td><strong>%s</strong></td></tr>',
						sprintf( '%s: %s x %s', $_item->label, $_item->quantity, wptravelengine_the_price( $_item->price, false ) ),
						wptravelengine_the_price( $_item->get_subtotal(), false )
					);
				}
			}
			$rows[ 'line_items' ][] = $_rows;
		}

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $rows, $this->cart );
	}

	public function get_cart_line_items(): ?array {
		return array_merge(
			$this->get_additional_line_items(),
			$this->get_cart_subtotal(),
			$this->get_deductible_line_items(),
			$this->get_fee_rows(),
			$this->get_total_row(),
		);

	}

	public function get_fee_rows() {
		$fees  = $this->cart->get_fees();
		$items = array();
		foreach ( $fees as $fee ) {
			$description = $fee->description;
			$items[ $fee->name ] = sprintf(
				'<tr class="wpte-checkout__booking-summary-%s"><td>%s%s</td><td><strong>+ %s</strong></td></tr>',
				$fee->name,
				"{$fee->label}",
				! empty( $description ) ? "<span id='{$fee->name}-tooltip' class='wpte-checkout__tooltip' data-content='{$description}'><svg><use xlink:href='#help' /></svg></span>" : '',
				wptravelengine_the_price( $this->cart->get_totals()[ "total_{$fee->name}" ], false )
			);
		}

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $items, $this->cart );
	}

	public function get_deductible_line_items(): array {
		$deductible_items = $this->cart->get_deductible_items();

		$rows = array();
		foreach ( $deductible_items as $item ) {
			$rows[ $item->name ][] = sprintf(
				'<tr class="wpte-checkout__booking-summary-discount"><td>%s</td><td><strong>- %s</strong></td></tr>',
				$item->label,
				wptravelengine_the_price( $this->cart->get_totals()[ "total_{$item->name}" ], false )
			);
		}

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $rows, $this->cart );
	}

	public function get_cart_subtotal() {
		$rows = array();

		$rows[ 'before_subtotal' ] = '<tr class="wpte-checkout__table-separator"><td colspan="2"><hr /></td></tr>';

		$rows[ 'subtotal' ] = sprintf(
			'<tr><td><strong>%s</strong></td><td><strong>%s</strong></td></tr>',
			__( 'Subtotal:', 'wp-travel-engine' ),
			wptravelengine_the_price( $this->cart->get_subtotal(), false )
		);

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $rows, $this->cart );
	}

	public function get_tour_details() {
		$cart_items = $this->cart->getItems();


		$item_details = array();
		foreach ( $cart_items as $cart_item ) {
			/** @var array $cart_item */
			$trip            = new Trip( $cart_item[ 'trip_id' ] );
			$trip_start_date = ! empty( $cart_item[ 'trip_time' ] ) ? $cart_item[ 'trip_time' ] : $cart_item[ 'trip_date' ];
			$item            = array(
				sprintf( '<tr><td colspan="2">%s</td></tr>', sprintf( '<a href="%s" class="wpte-checkout__trip-name">%s</a>', $trip->get_permalink(), $trip->get_title() ) ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Package:', 'wp-travel-engine' ), get_the_title( $cart_item[ 'price_key' ] ) ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Trip Code:', 'wp-travel-engine' ), $trip->get_trip_code() ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Starts on:', 'wp-travel-engine' ), wptravelengine_format_trip_datetime( $trip_start_date ) ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'Ends on:', 'wp-travel-engine' ), wptravelengine_format_trip_end_datetime( $trip_start_date, $trip ) ),
				sprintf( '<tr><td>%s</td><td><strong>%s</strong></td></tr>', __( 'No. of Travellers:', 'wp-travel-engine' ), array_sum( $cart_item[ 'pax' ] ?? [] ) ),
			);

			$item_details[] = apply_filters( 'wptravelengine_checkout_page_item_' . __FUNCTION__, $item, $trip, $cart_item );
		}

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $item_details, $cart_items, $this );
	}

	/**
	 * Get total row.
	 *
	 * @return array
	 */
	public function get_total_row(): array {
		$tax  = $this->cart->tax();
		$rows = array(
			'before_total' => '<tr class="wpte-checkout__table-spacer"><td colspan="2"></td></tr>',
			'total'        => sprintf(
				'<tr class="wpte-checkout__booking-summary-total"><td><strong>%s</strong></td><td><strong>%s</strong>%s</td></tr>',
				__( 'Total:', 'wp-travel-engine' ),
				wptravelengine_the_price( $this->cart->get_totals()[ 'total' ], false ),
				( $tax->is_taxable() && $tax->is_inclusive() ) ? sprintf( __( ' (Incl. %s%% tax)', 'wp-travel-engine' ), $tax->get_tax_percentage() ) : ''
			),
			'after_total'  => '<tr class="wpte-checkout__table-spacer"><td colspan="2"></td></tr>',
		);

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $rows, $this->cart );
	}

	public function get_note_form_fields() {
		$field = DefaultFormFields::additional_note();
		$form_data = WTE()->session->get( 'additional_note' );
		if ( ! empty( $form_data ) && isset( $field[ 'traveller_additional_note' ] ) ) {
			$field[ 'traveller_additional_note' ][ 'default' ] = $form_data;
		}

		return apply_filters( 'wptravelengine_checkout_page_' . __FUNCTION__, $field );
	}

}
