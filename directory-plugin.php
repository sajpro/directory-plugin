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
 * Constants
 */
if ( ! defined( 'DIRECTORY_PLUGIN_VERSION' ) ) {
	define( 'DIRECTORY_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'DIRECTORY_PLUGIN_FILE' ) ) {
	define( 'DIRECTORY_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'DIRECTORY_PLUGIN_PATH' ) ) {
	define( 'DIRECTORY_PLUGIN_PATH', __DIR__ );
}

if ( ! defined( 'DIRECTORY_PLUGIN_URL' ) ) {
	define( 'DIRECTORY_PLUGIN_URL', plugins_url( '', DIRECTORY_PLUGIN_FILE ) );
}

if ( ! defined( 'DIRECTORY_PLUGIN_ASSETS' ) ) {
	define( 'DIRECTORY_PLUGIN_ASSETS', DIRECTORY_PLUGIN_FILE . '/assets' );
}
