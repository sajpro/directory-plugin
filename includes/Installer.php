<?php
/**
 * Installer class.
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP;

/**
 * Installer class
 */
class Installer {

	/**
	 * Run Installer
	 */
	public static function run() {
		self::activate_version();
	}

	/**
	 * Saving activation time & version
	 */
	public static function activate_version() {
		$installed = get_option( 'dp_installed' );

		if ( ! $installed ) {
			update_option( 'dp_installed', time() );
		}

		update_option( 'dp_version', DIRECTORY_PLUGIN_VERSION );
	}
}
