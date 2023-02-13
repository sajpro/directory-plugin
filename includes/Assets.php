<?php
/**
 * Assets class.
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP;

/**
 * Scripts and Styles handler Class
 */
class Assets {
	/**
	 * Constructor
	 */
	function __construct() {
		if ( is_admin() ) {
			$this->blocks_file = include_once DIRECTORY_PLUGIN_PATH . '/build/index.asset.php';
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );

			add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ] );
		}
	}

	public function enqueue_block_editor_assets() {
		wp_register_script( 'dp-editor-script', DIRECTORY_PLUGIN_URL . '/build/index.js', $this->blocks_file['dependencies'], $this->blocks_file['version'], true );
	}

	/**
	 * Admin Assets
	 */
	public function admin_assets() {
		// CSS.
		wp_enqueue_style( 'dp-admin', DIRECTORY_PLUGIN_ASSETS . '/admin/css/styles.css', [], md5_file( DIRECTORY_PLUGIN_ASSETS . '/admin/css/styles.css' ), 'all' );

		// JS.
		wp_enqueue_media();
		wp_enqueue_script( 'dp-admin', DIRECTORY_PLUGIN_ASSETS . '/admin/js/scripts.js', [ 'jquery' ], md5_file( DIRECTORY_PLUGIN_ASSETS . '/admin/js/scripts.js' ), true );
	}
}
