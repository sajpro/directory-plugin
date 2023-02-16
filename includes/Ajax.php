<?php
/**
 * Ajax handler
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP;

/**
 * The Ajax Handler Class
 */
class Ajax {
	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'wp_ajax_upload_listing_image', [ $this, 'upload_listing_image' ] );
		add_action( 'wp_ajax_nopriv_upload_listing_image', [ $this, 'upload_listing_image' ] );
	}

	/**
	 * Upload  Listing imaeg
	 *
	 * @return array
	 */
	function upload_listing_image() {
		if ( isset( $_FILES['file']['name'] ) ) {
			$attachment_id = media_handle_upload( 'file', 0 );
			$image_url     = wp_get_attachment_url( $attachment_id );

			if ( is_wp_error( $attachment_id ) ) {
				$result['success'] = false;
				$result['message'] = __( 'Error uploading file.', 'directory-plugin' );
			} else {
				$result['attachment_id']  = $attachment_id;
				$result['attachment_url'] = $attachment_url;
				$result['success']        = true;
				$result['message']        = __( 'File uploading successfull.', 'directory-plugin' );
			}
			wp_send_json( $result );
		}
	}
}
