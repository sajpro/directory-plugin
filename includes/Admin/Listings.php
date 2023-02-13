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
	 * Form handler
	 *
	 * @return int
	 */
	public function form_handler() {
		if ( ! isset( $_POST['submit_listings'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'directory-listings' ) ) {
			wp_die( 'Are you cheating?' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating?' );
		}

		$id             = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		$title          = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
		$content        = isset( $_POST['content'] ) ? sanitize_textarea_field( $_POST['content'] ) : '';
		$author         = isset( $_POST['author'] ) ? sanitize_text_field( $_POST['author'] ) : '';
		$listing_status = isset( $_POST['listing_status'] ) ? sanitize_text_field( $_POST['listing_status'] ) : 'active';
		$preview_image  = isset( $_POST['preview_image'] ) ? sanitize_text_field( $_POST['preview_image'] ) : '';

		$args = [
			'title'          => $title,
			'content'        => $content,
			'author'         => $author,
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

	/**
	 * Listing Delete handler
	 */
	public function delete_listing() {
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'directory-listings-delete' ) ) {
			wp_die( 'Are you cheating?' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Are you cheating?' );
		}

		$id = isset( $_REQUEST['listing'] ) ? intval( $_REQUEST['listing'] ) : 0;

		if ( directory_plugin_delete_listing( $id ) ) {
			$redirected_to = admin_url( 'admin.php?page=directory-listings&deleted=true' );
		} else {
			$redirected_to = admin_url( 'admin.php?page=directory-listings&deleted=false' );
		}

		wp_redirect( $redirected_to );

		exit;
	}
}
