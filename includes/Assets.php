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
			$this->blocks_file = include_once DIRECTORY_PLUGIN_PATH . '/assets/blocks/index.asset.php';
			add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_assets' ] );
		} else {
			add_action( 'wp_enqueue_scripts', [ $this, 'public_assets' ] );
		}
		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_assets' ] );
	}

	/**
	 * Register block editor only styles/scripts
	 */
	public function enqueue_block_editor_assets() {
		wp_register_style( 'dp-editor-style', DIRECTORY_PLUGIN_ASSETS . '/blocks/blocks-editor.css', [], md5_file( DIRECTORY_PLUGIN_ASSETS . '/blocks/blocks-editor.css' ), 'all' );
		wp_register_script( 'dp-editor-script', DIRECTORY_PLUGIN_ASSETS . '/blocks/index.js', $this->blocks_file['dependencies'], $this->blocks_file['version'], true );
	}

	/**
	 * Register blocks styles/scripts for both frontend & backend
	 */
	public function enqueue_block_assets() {
		wp_register_style( 'dp-block-style', DIRECTORY_PLUGIN_ASSETS . '/blocks/blocks-style.css', [], md5_file( DIRECTORY_PLUGIN_ASSETS . '/blocks/blocks-style.css' ), 'all' );
	}

	/**
	 * Frontend assets
	 */
	public function public_assets() {
		wp_enqueue_script( 'dp-public', DIRECTORY_PLUGIN_ASSETS . '/public/js/scripts.js', [ 'jquery' ], md5_file( DIRECTORY_PLUGIN_ASSETS . '/public/js/scripts.js' ), true );

		wp_localize_script( 'dp-public', 'DpListings', self::public_localize_js_object() );
	}

	/**
	 * Enqueue Admin Panel Assets
	 */
	public function admin_assets() {
		// CSS.
		wp_enqueue_style( 'dp-admin', DIRECTORY_PLUGIN_ASSETS . '/admin/css/styles.css', [], md5_file( DIRECTORY_PLUGIN_ASSETS . '/admin/css/styles.css' ), 'all' );

		// JS.
		wp_enqueue_media();
		wp_enqueue_script( 'dp-admin', DIRECTORY_PLUGIN_ASSETS . '/admin/js/scripts.js', [ 'jquery' ], md5_file( DIRECTORY_PLUGIN_ASSETS . '/admin/js/scripts.js' ), true );
	}

	/**
	 * JS Object for localization
	 */
	public static function public_localize_js_object() {
		return [
			'ajax_url'      => admin_url( 'admin-ajax.php' ),
			'rest_nonce'    => wp_create_nonce( 'wp_rest' ),
			'upload_nonce'  => wp_create_nonce( 'upload_nonce' ),
			'rest_url'      => get_rest_url( null, 'directory/v1/listings' ),
			'base_url'      => wp_upload_dir()['baseurl'] .'/'
		];
	}
}
