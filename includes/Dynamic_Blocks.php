<?php
/**
 * Dynamic Blocks stuff
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP;

/**
 * The Dynamic_Blocks Class handler
 */
class Dynamic_Blocks {

	function __construct() {
		// Register blocks.
		add_action( 'init', [ $this, 'register_blocks' ] );
	}

	/**
	 * Register all dynamic blocks
	 */
	public function register_blocks() {
		// Listings block.
		DynamicBlocks\Listings::register();
	}

}
