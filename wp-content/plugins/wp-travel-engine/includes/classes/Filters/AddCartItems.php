<?php
/**
 * Add Cart Items Filter
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Filters;

use WPTravelEngine\Core\Cart\Items\ExtraService;
use WPTravelEngine\Core\Cart\Adjustments\TaxAdjustment;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Adjustments\CouponAdjustment;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Coupon;
use WPTravelEngine\Core\Models\Post\Trip;

class AddCartItems {

	/**
	 * Initializes hooks for template inclusion and excerpt modification.
	 */
	public function hooks() {
		add_action( 'wptravelengine_before_calculate_totals', array( $this, 'add_extra_services' ) );
		add_action( 'wptravelengine_before_calculate_totals', array( $this, 'add_tax' ) );
		add_action( 'wptravelengine_before_calculate_totals', array( $this, 'apply_coupon_discounts' ) );
	}

	/**
	 * Add tax as a fee to the cart.
	 */
	public function add_tax( Cart $cart ) {
		if ( $cart->tax()->is_taxable() && $cart->tax()->is_exclusive() ) {
			$cart->add_fee(
				new TaxAdjustment( $cart, [ 'order' => 10 ] )
			);
		}
	}

	/**
	 * Add cart items from order items.
	 *
	 * @param Cart $cart
	 *
	 * @return void
	 */
	public function add_extra_services( Cart $cart ) {
		$cart_items = $cart->getItems( true );

		foreach ( $cart_items as $cart_item ) {
			$cart_item_additional_items = $cart_item->get_additional_line_items();
			if ( isset( $cart_item_additional_items[ 'extra_service' ] ) ) {
				$cart_item_additional_items[ 'extra_service' ] = [];
			}
			$cart_item->set_additional_line_items( $cart_item_additional_items );

			if ( ! ( $trip_extras = $cart_item->subtotal_reservations[ 'extraServices' ] ?? null ) ) {
				continue;
			}

			$trip           = new Trip( $cart_item->trip_id );
			$extra_services = $trip->get_services();

			foreach ( $trip_extras as $trip_extra ) {

				foreach ( $extra_services as $service ) {
					$key = array_search( $trip_extra[ 'id' ], array_column( $service[ 'options' ], 'key' ) );
					if ( $key !== false ) {
						$label = (string) ( $service[ 'options' ][ $key ][ 'label' ] ?? ( $service[ 'title' ] ?? '' ) );
						$price = (float) ( $service[ 'options' ][ $key ][ 'price' ] ?? 0.0 );
						break;
					}
				}

				if ( ! isset( $label ) || ! isset( $price ) ) {
					continue;
				}

				$item = new ExtraService( $cart, array(
					'label'    => $label,
					'quantity' => $trip_extra[ 'quantity' ] ?? 0,
					'price'    => $price,
				) );

				$cart_item->add_additional_line_items( $item );
			}
		}
	}

	/**
	 * Apply coupon discounts to the cart.
	 *
	 * @param Cart $cart
	 */
	public function apply_coupon_discounts( Cart $cart ) {
		foreach ( $cart->get_discounts() as $coupon ) {
			$coupon = Coupon::by_code( $coupon[ 'name' ] );
			if ( $coupon instanceof Coupon ) {
				$cart->add_deductible_items(
					new CouponAdjustment(
						$cart,
						$coupon
					)
				);
			}

		}
	}
}
