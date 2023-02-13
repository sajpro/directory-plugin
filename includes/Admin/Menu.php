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
	}

	/**
	 * Admin Menu
	 */
	public function admin_menu() {
		global $direcotry_plugin_page;

		$parent_slug = 'directory-listings';
		$capability  = 'manage_options';

		$direcotry_plugin_page = add_menu_page( esc_html__( 'Directory Plugin', 'directory-plugin' ), esc_html__( 'Directory Plugin', 'directory-plugin' ), $capability, $parent_slug, [ $this, 'all_listings_page' ], 'dashicons-welcome-learn-more' );
		add_submenu_page( $parent_slug, esc_html__( 'All Listings', 'directory-plugin' ), esc_html__( 'All Listings', 'directory-plugin' ), $capability, $parent_slug, [ $this, 'all_listings_page' ] );
		add_submenu_page( $parent_slug, esc_html__( 'Add Listing', 'directory-plugin' ), esc_html__( 'Add Listing', 'directory-plugin' ), $capability, $parent_slug . '-create', [ $this, 'create_listings' ] );

		add_action( "load-$direcotry_plugin_page", [ $this, 'direcotry_plugin_screen_options' ] );
	}

	/**
	 * Plugin Page
	 */
	public function all_listings_page() {
		Listings::plugin_page();
	}

	/**
	 * Create Page
	 */
	public function create_listings() {
		echo 'hello directory create page';
	}

	// add screen options
	public function direcotry_plugin_screen_options() {
		global $direcotry_plugin_page;

		$screen = get_current_screen();

		// get out of here if we are not on our settings page
		if ( ! is_object( $screen ) || $screen->id != $direcotry_plugin_page ) {
			return;
		}

		$args = [
			'label'   => __( 'Listings per page', 'directory-plugin' ),
			'default' => 2,
			'option'  => 'listings_per_page',
		];
		add_screen_option( 'per_page', $args );

		$table = new Listing_Table();
	}

}
