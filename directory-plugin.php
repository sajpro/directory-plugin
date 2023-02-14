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

		if ( ! defined( 'DIRECTORY_PLUGIN_BLOCK_ASSETS' ) ) {
			define( 'DIRECTORY_PLUGIN_BLOCK_ASSETS', DIRECTORY_PLUGIN_URL . '/build' );
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

		// Register blocks.
		add_action( 'init', [ $this, 'register_blocks' ] );

		// Register block category hook.
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
	 * Register blocks
	 */
	public function register_blocks() {
		// $blocks_dir = DIRECTORY_PLUGIN_PATH . '/blocks/';
		// foreach ( scandir( $blocks_dir ) as $result ) {
		// $block_location = $blocks_dir . $result;
		// pretty_log('xxxxx',$block_location);
		// if ( ! is_dir( $block_location ) || '.' === $result || '..' === $result ) {
		// continue;
		// }
		// register_block_type( $block_location );
		// }

		$blocks_dir       = DIRECTORY_PLUGIN_PATH . '/blocks/listings/block.json';
		$blocks_json      = file_get_contents( $blocks_dir );
		$attributes_array = json_decode( $blocks_json, true );

		register_block_type(
			'directory-plugin/listings',
			[
				'editor_script'   => 'dp-editor-script',
				'render_callback' => [ $this, 'listing_dynamic_render_callback' ],
				'attributes'      => $attributes_array['attributes'],
			]
		);
	}

	/**
	 * Listing blocks dynaic content
	 */
	public function listing_dynamic_render_callback( $attributes, $content ) {
		$title = '';
		$align = '';

		if ( isset( $attributes['title'] ) && ! empty( $attributes['title'] ) ) {
			$title = $attributes['title'];
		}
		if ( isset( $attributes['align'] ) && ! empty( $attributes['align'] ) ) {
			$align = 'align' . $attributes['align'];
		}

		$classnames = [];

		$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $classnames ) ] );

		ob_start(); ?>
			<div <?php echo esc_attr( $wrapper_attributes ); ?>>
				<div class="listing-form">
					<form action="">
						<label for="fname">First Name</label>
						<input type="text" id="fname" name="firstname" placeholder="Your name..">

						<label for="lname">Last Name</label>
						<input type="text" id="lname" name="lastname" placeholder="Your last name..">

						<label for="country">Country</label>
						<select id="country" name="country">
						<option value="australia">Australia</option>
						<option value="canada">Canada</option>
						<option value="usa">USA</option>
						</select>

						<label for="subject">Subject</label>
						<textarea id="subject" name="subject" placeholder="Write something.." style="height:200px"></textarea>

						<input type="submit" value="Submit">
					</form>
				</div>
				<div class="wrapper <?php echo esc_attr( $align ); ?>">
					<div class="cell cell-1"><?php echo esc_html( $title ); ?></div>
					<div class="cell cell-2"><?php echo esc_html( $title ); ?></div>
					<div class="cell cell-3"><?php echo esc_html( $title ); ?></div>
					<div class="cell cell-4"><?php echo esc_html( $title ); ?></div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Block category register
	 */
	public function add_block_category( $block_categories ) {
		$block_categories[] = [
			'slug'  => 'directory-plugin',
			'title' => esc_html__( 'Directory Plugin', 'directory-plugin' ),
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
