<?php
/**
 * Fee Item.
 *
 * @since 6.3.0
 */

namespace WPTravelEngine\Abstracts;

use WPTravelEngine\Core\Cart\Cart;
use WPTravelEngine\Core\Cart\Item;
use WPTravelEngine\Interfaces\CartAdjustment as CartAdjustmentInterface;

abstract class CartAdjustment implements CartAdjustmentInterface {

	/**
	 * The order of the item.
	 *
	 * @var int
	 */
	public int $order;

	/**
	 * A unique identifier for the item.
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * @var string
	 */
	public string $label;

	/**
	 * Cart instance.
	 *
	 * @var Cart
	 */
	public Cart $cart;

	/**
	 * @var string
	 */
	public string $description;

	/**
	 * @var float
	 */
	public float $percentage;

	/**
	 * @var string
	 */
	public string $adjustment_type;

	/**
	 * Cart item type|name to apply this fee.
	 *
	 * @var array
	 */
	public array $applies_to;

	/**
	 * @var bool
	 */
	public bool $apply_to_actual_subtotal;

	/**
	 * Constructor.
	 *
	 * @param Cart $cart The cart instance.
	 * @param array $args The arguments for the item.
	 */
	public function __construct( Cart $cart, array $args = array() ) {
		$this->cart = $cart;

		$args = wp_parse_args(
			$args,
			array(
				'name'                     => '',
				'label'                    => '',
				'description'              => '',
				'percentage'               => 0,
				'adjustment_type'          => 'percentage',
				'applies_to'               => array(),
				'order'                    => - 1,
				'apply_to_actual_subtotal' => false,
			)
		);

		$this->name                     = $args[ 'name' ];
		$this->label                    = $args[ 'label' ];
		$this->description              = $args[ 'description' ] ?? '';
		$this->percentage               = $args[ 'percentage' ];
		$this->adjustment_type          = $args[ 'adjustment_type' ];
		$this->applies_to               = $args[ 'applies_to' ];
		$this->order                    = $args[ 'order' ];
		$this->apply_to_actual_subtotal = $args[ 'apply_to_actual_subtotal' ];
	}

	/**
	 * Get the adjustment percentage.
	 *
	 * @return float
	 */
	public function get_percentage(): float {
		return (float) $this->percentage;
	}

	/**
	 * @param float $total
	 * @param Item $cart_item
	 *
	 * @return float
	 */
	public function apply( float $total, Item $cart_item ): float {
		if ( 'percentage' === $this->adjustment_type ) {
			return $total * $this->percentage / 100;
		}

		return (float) $this->percentage / count( $cart_item->get_additional_line_items() );
	}

}
