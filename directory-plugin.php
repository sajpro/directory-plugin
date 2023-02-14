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
				'style'           => $attributes_array['style'],
				'editor_script'   => $attributes_array['editorScript'],
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

		$number = 4;

		$api_url = add_query_arg(
			[
				'number' => $number,
			],
			get_rest_url( null, 'directory/v1/listings' )
		);
		// pretty_log('api_url',$api_url);

		$remote_request = wp_remote_get(
			$api_url,
			[
				'timeout' => apply_filters( 'dp_remote_get_timeout', 300 ),
			]
		);

		$response_code = wp_remote_retrieve_response_code( $remote_request );
		$response_body = (array) json_decode( wp_remote_retrieve_body( $remote_request ) );
		$pages         = $response_body['pages'];
		$prev          = $response_body['prev'];
		$next          = $response_body['next'];

		$classnames         = [];
		$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $classnames ) ] );

		ob_start(); ?>
			<div <?php echo esc_attr( $wrapper_attributes ); ?>>
				<?php if ( ( $response_code == 200 ) && ( $response_body['success'] == true ) ) : ?>
					<div id="listings-wrap">
						<div class="loader-wrap hidden">
							<div class="loader">
								<div class="svg-loader">
									<svg class="svg-container" height="100" width="100" viewBox="0 0 100 100">
										<circle class="loader-svg bg" cx="50" cy="50" r="45"></circle>
										<circle class="loader-svg animate" cx="50" cy="50" r="45"></circle>
									</svg>
								</div>
							</div>
						</div>
						<div class="wrapper <?php echo esc_attr( $align ); ?>">
							<?php
							if ( count( $response_body['listings'] ) > 0 ) {
								foreach ( $response_body['listings'] as $listing ) {
									$image_url = explode(',',$listing->preview_image)[1];
									?>
										<div class="cell">
											<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $listing->id ); ?>">
											<h2>ID: <?php echo esc_html( $listing->id ); ?></h2>
											<h5>Title: <?php echo esc_html( $listing->title ); ?></h5>
											<span>Content: <?php echo esc_html( $listing->content ); ?></span>
											<span>Status: <?php echo esc_html( $listing->listing_status ); ?></span>
											<span>Author: <?php echo esc_html( $listing->author ); ?></span>
											<span>Created at: <?php echo esc_html( $listing->created_at ); ?></span>
										</div>
									<?php
								}
							}
							?>
						</div>
					</div>
					<div class="listings-pagination">
						<input type="hidden" name="number" id="number" value="<?php echo esc_attr( $number ); ?>">
						<button class="prev-btn <?php echo esc_attr( $prev < 2 ? 'hidden' : '' ); ?>" value="<?php echo esc_attr( $prev ); ?>">Prev</button>
						<?php
						for ( $i = 0; $i < ( $number ); $i++ ) {
							$current = $i + 1;
							echo '<button class="page-number ' . esc_attr( $current == ( $next - 1 ) ? 'active' : '' ) . '" value="' . esc_html( $current ) . '">' . esc_html( $current ) . '</button>';
						}
						?>
						<button class="next-btn <?php echo esc_attr( $next > $pages ? 'hidden' : '' ); ?>" value="<?php echo esc_attr( $next ); ?>">Next</button>
					</div>
				<?php endif; ?>

				<button class="submit-toggle">Submit Listing</button>

				<div class="dp-modal">
					<div class="listing-form <?php echo esc_attr( ! is_user_logged_in() ? 'login-first' : '' ); ?>">
						<span class="close-modal">&times;</span>
						<?php if ( is_user_logged_in() ) : ?>
							<form method="post" action="" enctype="multipart/form-data">
								<label for="title"><?php esc_html_e( 'Title:', 'directory-plugin' ); ?></label>
								<input type="text" id="title" name="title" placeholder="Title...">

								<label for="content"><?php esc_html_e( 'Content:', 'directory-plugin' ); ?></label>
								<textarea id="content" name="content" placeholder="Content..." style="height:200px"></textarea>

								<label for="status"><?php esc_html_e( 'Status:', 'directory-plugin' ); ?></label>
								<select id="status" name="status">
									<option value="active"><?php esc_html_e( 'Active', 'directory-plugin' ); ?></option>
									<option value="inactive"><?php esc_html_e( 'Inactive', 'directory-plugin' ); ?></option>
								</select>

								<label for="image"><?php esc_html_e( 'Image:', 'directory-plugin' ); ?></label>
								<br>
								<input type="file" name="image">
								<br>
								<br>
								<?php wp_nonce_field( 'dp_listing_image_upload', 'dp_listing_image_upload_nonce' ); ?>
								<input type="submit" value="Submit">
							</form>
						<?php else : ?>
							<p class="no-access"><?php esc_html_e( 'You need to login first to submit a listing.', 'directory-plugin' ); ?></p>
						<?php endif; ?>
					</div>
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
