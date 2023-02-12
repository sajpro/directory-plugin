<?php
/**
 * Plugin Name:       Directory Plugin
 * Plugin URI:        http://sajib.me/plugins/directory-plugin/
 * Description:       A simple and well structured directory plugin where a logged in user will submit listings through a frontend submission form.
 * Version:           1.0.0
 * Author:            Sajib Talukder
 * Author URI:        http://sajib.me/
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       directory-plugin
 * Domain Path:       /languages
 *
 * @package DirectoryPlugin
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


/**
 * The main plugin class
 */
final class Directory_Plugin {
	/**
	 * Plugin version
	 *
	 * @var string
	 */
	private static $version = '1.0.0';

	/**
	 * Single Instance
	 *
	 * @var null The single instance of the class
	 */
	private static $_instance = null;

	/**
	 * Singular class instance safeguard.
	 * Ensures only one instance of a class can be instantiated.
	 * Follows a singleton design pattern.
	 *
	 * @since 1.0
	 * @return Directory_Plugin - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'directory-plugin' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'directory-plugin' ), '1.0.0' );
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->setup_constants();
	}

	/**
	 * Constants
	 */
	public function setup_constants() {
		if ( ! defined( 'DIRECTORY_PLUGIN_VERSION' ) ) {
			define( 'DIRECTORY_PLUGIN_VERSION', self::$version );
		}

		if ( ! defined( 'DIRECTORY_PLUGIN_FILE' ) ) {
			define( 'DIRECTORY_PLUGIN_FILE', __FILE__ );
		}

		if ( ! defined( 'DIRECTORY_PLUGIN_PATH' ) ) {
			define( 'DIRECTORY_PLUGIN_PATH', __DIR__ );
		}

		if ( ! defined( 'DIRECTORY_PLUGIN_BASENAME' ) ) {
			define( 'DIRECTORY_PLUGIN_BASENAME', plugin_basename( DIRECTORY_PLUGIN_FILE ) );
		}

		if ( ! defined( 'DIRECTORY_PLUGIN_AJAX_URL' ) ) {
			define( 'DIRECTORY_PLUGIN_AJAX_URL', admin_url( 'admin-ajax.php' ) );
		}

		if ( ! defined( 'DIRECTORY_PLUGIN_URL' ) ) {
			define( 'DIRECTORY_PLUGIN_URL', plugins_url( '', DIRECTORY_PLUGIN_FILE ) );
		}

		if ( ! defined( 'DIRECTORY_PLUGIN_ASSETS' ) ) {
			define( 'DIRECTORY_PLUGIN_ASSETS', DIRECTORY_PLUGIN_FILE . '/assets' );
		}
	}

} // End class

/**
 * Returns the main instance of Directory_Plugin.
 *
 * @return Directory_Plugin
 */
function DP() {
	return Directory_Plugin::instance();
}

// Initialize the class instance only once.
DP();
