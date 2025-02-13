<?php
/**
 * WTE - Object.
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WTE' ) ) :
	/**
	 * Main WTE Object
	 *
	 * @since 2.2.6
	 */
	// dynamic properties
	#[AllowDynamicProperties]
	final class WTE {

		/**
		 * The single instance of the class.
		 *
		 * @var WP Travel Engine
		 * @since 2.2.6
		 */
		protected static $_instance = null;

		/**
		 * Main WTE Instance.
		 * Ensures only one instance of WTE is loaded or can be loaded.
		 *
		 * @return WTE - Main instance.
		 * @see WTE()
		 * @since 2.2.6
		 * @static
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * WTE Constructor.
		 *
		 * @since 2.2.6
		 */
		function __construct() {

			require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-session.php';
			require_once WP_TRAVEL_ENGINE_BASE_PATH . '/includes/class-wte-notices.php';

			$this->session = new WTE_Session();
			$this->notices = new WTE_Notices();
		}
	}
endif;
/**
 * Main instance of WP Travel Engine.
 *
 * Returns the main instance of WTE to prevent the need to use globals.
 *
 * @return WP Travel Engine Object
 * @since  2.2.6
 */
function WTE() {
	return WTE::instance();
}

// Start WP Travel Engine Object.
WTE();
