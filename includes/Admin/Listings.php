<?php
/**
 * Listings
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\Admin;

/**
 * Listings handler class
 */
class Listings {

	/**
	 * Plugin page handler
	 *
	 * @return void
	 */
	public static function plugin_page() {
		$action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
		$id     = isset( $_GET['listing'] ) ? intval( $_GET['listing'] ) : 0;

		switch ( $action ) {
			case 'create':
				$template = __DIR__ . '/views/listings-create.php';
				break;

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
	 * Form handler
	 *
	 * @return int
	 */
	public function form_handler() {
		if ( ! isset( $_POST['submit_listings'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'edit-listings' ) ) {
			wp_die( 'Are you cheating?' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating?' );
		}

		$id             = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$title          = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
		$content        = isset( $_POST['content'] ) ? sanitize_textarea_field( $_POST['content'] ) : '';
		$created_by     = isset( $_POST['created_by'] ) ? sanitize_text_field( $_POST['created_by'] ) : '';
		$listing_status = isset( $_POST['listing_status'] ) ? sanitize_text_field( $_POST['listing_status'] ) : '';
		$preview_image  = isset( $_POST['preview_image'] ) ? sanitize_text_field( $_POST['preview_image'] ) : '';

		$args = [
			'title'          => $title,
			'content'        => $content,
			'created_by'     => $created_by,
			'listing_status' => $listing_status,
			'preview_image'  => $preview_image,
		];

		if ( $id ) {
			$args['id'] = $id;
		}

		$insert_id = directory_plugin_listing_insert( $args );

		if ( is_wp_error( $insert_id ) ) {
			wp_die( $insert_id->get_error_message() );
		}

		if ( $id ) {
			$redirected_to = admin_url( 'admin.php?page=directory-listings&action=edit&updated=true&listing=' . $id );
		} else {
			$redirected_to = admin_url( 'admin.php?page=directory-listings&created=true' );
		}

		wp_redirect( $redirected_to );

		exit;
	}
}
