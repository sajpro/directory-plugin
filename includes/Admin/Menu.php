<?php
/**
 * Admin Menu
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\Admin;

/**
 * The Menu Handler Class
 */
class Menu {
	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_filter( 'set-screen-option', [ $this, 'dp_set_screen_option' ], 10, 3 );
	}

	/**
	 * Admin Menu
	 */
	public function admin_menu() {
		global $direcotry_plugin_page;

		$parent_slug = 'directory-listings';
		$capability  = 'manage_options';

		$direcotry_plugin_page = add_menu_page( esc_html__( 'Directory Plugin', 'directory-plugin' ), esc_html__( 'Directory Plugin', 'directory-plugin' ), $capability, $parent_slug, [ $this, 'plugin_page' ], 'dashicons-welcome-learn-more' );
		add_submenu_page( $parent_slug, esc_html__( 'All Listings', 'directory-plugin' ), esc_html__( 'All Listings', 'directory-plugin' ), $capability, $parent_slug, [ $this, 'plugin_page' ] );
		add_submenu_page( $parent_slug, esc_html__( 'Add Listing', 'directory-plugin' ), esc_html__( 'Add Listing', 'directory-plugin' ), $capability, $parent_slug . '-create', [ $this, 'create_listings' ] );

		add_action( "load-$direcotry_plugin_page", [ $this, 'direcotry_plugin_screen_options' ] );
	}

	/**
	 * Plugin page handler
	 *
	 * @return void
	 */
	public static function plugin_page() {
		$action = isset( $_GET['action'] ) ? wp_unslash( $_GET['action'] ) : 'list';
		$id     = isset( $_GET['listing'] ) ? intval( wp_unslash( $_GET['listing'] ) ) : 0;

		switch ( $action ) {
			// case 'create':
			// $template = __DIR__ . '/views/listings-create.php';
			// break;

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

	/**
	 * Create Page
	 */
	public function create_listings() {
		include __DIR__ . '/views/listings-create.php';
	}

	/**
	 * Add screen options to Listing page
	 */
	public function direcotry_plugin_screen_options() {
		global $direcotry_plugin_page;

		$screen = get_current_screen();

		// get out of here if we are not on our settings page
		if ( ! is_object( $screen ) || $screen->id != $direcotry_plugin_page ) {
			return;
		}

		$args = [
			'label'   => esc_html__( 'Listings per page', 'directory-plugin' ),
			'default' => 20,
			'option'  => 'listings_per_page',
		];
		add_screen_option( 'per_page', $args );

		$table = new Listing_Table();
	}

	/**
	 * Save listings per page screen options value
	 */
	public function dp_set_screen_option( $status, $option, $value ) {
		if ( 'listings_per_page' == $option ) {
			return $value;
		}
	}
}
