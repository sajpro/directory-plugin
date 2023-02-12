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
		self::create_tables();
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

	/**
	 * Creating table during plugin activation
	 */
	public static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}directory_listings` (
	        id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		    title varchar(100) NOT NULL,
		    content longtext DEFAULT '' NULL,
		    listing_status varchar(30) DEFAULT '' NULL,
		    preview_image varchar(30) DEFAULT '' NULL,
		    created_by bigint(20) UNSIGNED NOT NULL,
		    created_at datetime NOT NULL,
		    PRIMARY KEY  (id)
		) $charset_collate;";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}
}
