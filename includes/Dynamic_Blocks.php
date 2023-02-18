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

		// Fonts Loader for blocks.
		new Blocks\FontsLoader();
		// Register Meta to store selected fonts from blocks.
		new Blocks\PostMeta();
		// Generate style file for each page that will be uploaded to upload/dp-style dir.
		new Blocks\StyleLoader();
	}

	/**
	 * Register all dynamic blocks
	 */
	public function register_blocks() {
		// Dynamic Listings block.
		DynamicBlocks\Listings::register();
	}

}
