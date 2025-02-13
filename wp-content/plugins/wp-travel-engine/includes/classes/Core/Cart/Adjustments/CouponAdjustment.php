<?php
/**
 * Discount Adjustment class.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Core\Cart\Adjustments;

use WPTravelEngine\Abstracts\CartAdjustment;
use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Core\Coupon;
use WPTravelEngine\Core\Tax;

class CouponAdjustment extends CartAdjustment {

	protected Coupon $coupon;

	public function __construct( Cart $cart, Coupon $coupon, array $args = array() ) {
		$args         = wp_parse_args(
			$args,
			array(
				'name'            => "coupon",
				'label'           => sprintf(
					__( '%s (%s)', 'wp-travel-engine' ),
					$coupon->code(),
					$coupon->type() === 'percentage' ? $coupon->value() . '%' : wptravelengine_the_price( $coupon->value(), false, false ),
				),
				'adjustment_type' => $coupon->type(),
				'percentage'      => $coupon->value(),
			)
		);
		$this->coupon = $coupon;
		parent::__construct( $cart, $args );
	}

	/**
	 * Apply the adjustment.
	 *
	 * @param float $total
	 * @param Item $cart_item
	 *
	 * @return float
	 */
	public function apply( float $total, Item $cart_item ): float {
		if ( ! $this->coupon->is_valid_for_trip( $cart_item->trip_id ) ) {
			return 0;
		};
		if ( 'percentage' === $this->adjustment_type ) {
			return $total * $this->percentage / 100;
		}

		return (float) $this->percentage / count( $cart_item->get_additional_line_items() );
	}
}
