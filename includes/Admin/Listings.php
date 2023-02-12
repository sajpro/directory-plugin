<?php
/**
 * Listings
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\Admin;

/**
 * Listings handler class
 */
class Listings {

	/**
	 * Plugin page handler
	 *
	 * @return void
	 */
	public static function plugin_page() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
		$id     = isset( $_GET['listing'] ) ? intval( $_GET['listing'] ) : 0;

		switch ( $action ) {
			case 'create':
				$template = __DIR__ . '/views/listings-create.php';
				break;

			case 'edit':
				$listing  = directory_plugin_get_single_listing( $id );
				$template = __DIR__ . '/views/listings-edit.php';
				break;

			default:
				$template = __DIR__ . '/views/listings-all.php';
				break;
		}

		if ( file_exists( $template ) ) {
			include $template;
		}
	}

}
