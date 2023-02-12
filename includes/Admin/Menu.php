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
		$parent_slug = 'directory-plugin';
		$capability  = 'manage_options';

		$hook = add_menu_page( __( 'Directory Plugin', 'directory-plugin' ), __( 'Directory Plugin', 'directory-plugin' ), $capability, $parent_slug, [ $this, 'plugin_page' ], 'dashicons-welcome-learn-more' );
		add_submenu_page( $parent_slug, __( 'All Listings', 'directory-plugin' ), __( 'All Listings', 'directory-plugin' ), $capability, $parent_slug, [ $this, 'plugin_page' ] );
		add_submenu_page( $parent_slug, __( 'Settings', 'directory-plugin' ), __( 'Settings', 'directory-plugin' ), $capability, 'directory-settings', [ $this, 'settings_page' ] );
	}

	/**
	 * Plugin Page
	 */
	public function plugin_page() {
		echo 'hello directory plugin page';
	}

	/**
	 * Settinhs Page
	 */
	public function settings_page() {
		echo 'hello directory settings page';
	}
}
