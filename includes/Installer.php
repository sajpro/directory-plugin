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
		self::create_table_for_listings();
		self::create_table_for_empty_image();
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
	 * Creating table for listings
	 */
	public static function create_table_for_listings() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}directory_listings` (
	        id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		    title varchar(100) NOT NULL,
		    content longtext DEFAULT '' NULL,
		    listing_status varchar(30) DEFAULT '' NULL,
		    preview_image varchar(250) DEFAULT '' NULL,
		    author bigint(20) UNSIGNED NOT NULL,
		    created_at datetime NOT NULL,
		    PRIMARY KEY  (id)
		) $charset_collate;";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}

	/**
	 * Creating table for listings that has no images, since using sql join query to
	 * fetch data with where conditon it makes error. Listing preview_image column needs a default value and it can not be empty.
	 * So during activation inserting this default value with post_id = 0. (zero means false. 0 can not be a (real) post id that
	 * created from dashboard, its auto incremental)
	 */
	public static function create_table_for_empty_image() {
		global $wpdb;

		$metakey   = '_wp_attached_file';
		$metavalue = '';

		$wpdb->query(
			$wpdb->prepare(
				"INSERT INTO $wpdb->postmeta
				( post_id, meta_key, meta_value )
				VALUES ( %d, %s, %s )",
				0,
				$metakey,
				$metavalue
			)
		);
	}
}
