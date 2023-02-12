<?php
/**
 * Admin
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP;

/**
 * The Admin Handler Class
 */
class Admin {
	/**
	 * Constructor
	 */
	function __construct() {
		$listings = new Admin\Listings();
		$this->dispatch_action( $listings );

		new Admin\Menu();
	}

	/**
	 * Form Action handler
	 */
	public function dispatch_action( $listings ) {
		add_action( 'admin_init', [ $listings, 'form_handler' ] );
	}
}
