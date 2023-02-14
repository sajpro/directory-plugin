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

// Includes autolaoder.
require_once __DIR__ . '/vendor/autoload.php';

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
		$this->includes();
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
			define( 'DIRECTORY_PLUGIN_ASSETS', DIRECTORY_PLUGIN_URL . '/assets' );
		}
	}

	/**
	 * Includes
	 */
	public function includes() {
		register_activation_hook( __FILE__, [ $this, 'run_activation' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

		$api_endpoint = new Sajib\DP\RestApi\v1\Api_Endpoints();
		$api_endpoint->init();

		new Sajib\DP\Assets();
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function run_activation() {
		Sajib\DP\Installer::run();
	}

	/**
	 * Init pluign
	 *
	 * @return void
	 */
	public function init() {
		self::load_plugin_textdomain();

		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			add_filter( 'block_categories_all', [ $this, 'add_block_category' ], 10, 2 );
		} else {
			add_filter( 'block_categories', [ $this, 'add_block_category' ], 10, 2 );
		}

		if ( is_admin() ) {
			new Sajib\DP\Admin();
		}
	}

	/**
	 * Load Localization files.
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'directory-plugin', false, dirname( DIRECTORY_PLUGIN_BASENAME ) . '/languages' );
	}

	/**
	 * Block category register
	 */
	public function add_block_category( $block_categories ) {
		$block_categories[] = [
			'slug'  => 'directory-plugin',
			'title' => __( 'Directory Plugin', 'directory-plugin' ),
		];
		return $block_categories;
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
