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
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ], 5 );
		}
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
