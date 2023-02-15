<?php
/**
 * Directory plugin's custom functions.
 *
 * @package DirectoryPlugin
 */

/**
 * Insert a new daabase
 *
 * @param  array $args
 * @return int|WP_Error
 */
function directory_plugin_listing_insert( $args = [] ) {
	global $wpdb;

	$defaults = [
		'title'          => '',
		'content'        => '',
		'listing_status' => '',
		'preview_image'  => '',
		'author'         => '',
		'created_at'     => current_time( 'mysql' ),
	];

	$data = wp_parse_args( $args, $defaults );

	if ( isset( $data['id'] ) ) {
		$id = $data['id'];

		unset( $data['id'] );

		$updated = $wpdb->update(
			"{$wpdb->prefix}directory_listings",
			$data,
			[ 'id' => $id ],
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
			],
			[ '%d' ]
		);

		return $updated;
	} else {
		$inserted = $wpdb->insert(
			"{$wpdb->prefix}directory_listings",
			$data,
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s',
			]
		);

		if ( ! $inserted ) {
			return new \WP_Error( 'failed-to-insert', esc_html__( 'Failed to insert data', 'directory-plugin' ) );
		}

		return $wpdb->insert_id;
	}
}

/**
 * Get all listings
 *
 * @param  array $args [description]
 * @return array
 */
function directory_plugin_listing_get( $args = [], $filter = [] ) {
	global $wpdb;

	$defaults = [
		'number'  => 20,
		'offset'  => 0,
		'orderby' => 'id',
		'order'   => 'asc',
	];

	$args = wp_parse_args( $args, $defaults );

	$table = $wpdb->prefix . 'directory_listings';

	$extra_checks = '';

	$search = '';

	if ( is_array( $filter ) && ! empty( $filter['search'] ) ) {
		$search = $filter['search'];
	}
	if ( is_array( $filter ) && ! empty( $filter['status'] ) ) {
		$status        = $filter['status'];
		$extra_checks .= $wpdb->prepare( ' AND listing_status = %s', "$status" );
	}
	if ( is_array( $filter ) && ! empty( $filter['author'] ) ) {
		$author        = $filter['author'];
		$extra_checks .= $wpdb->prepare( ' AND author = %s', "$author" );
	}

	$sql = $wpdb->prepare(
		"SELECT $table.*, $wpdb->postmeta.meta_value as image_url FROM {$table}
		JOIN $wpdb->postmeta
		ON $table.preview_image = $wpdb->postmeta.post_id
		WHERE ($table.title LIKE %s 
		OR $table.content LIKE %s)
		AND $wpdb->postmeta.post_id = $table.preview_image
		AND $wpdb->postmeta.meta_key = %s
		$extra_checks
		ORDER BY {$args['orderby']} {$args['order']}
		LIMIT %d, %d",
		"%$search%",
		"%$search%",
		'_wp_attached_file',
		$args['offset'],
		$args['number']
	);

	$items = $wpdb->get_results( $sql );

	return $items;
}

/**
 * Get the total number of listings
 *
 * @return int
 */
function directory_plugin_listings_total_count( $filter = [] ) {
	global $wpdb;
	$extra_checks = '';
	if ( is_array( $filter ) && ! empty( $filter['status'] ) ) {
		$status        = $filter['status'];
		$extra_checks .= $wpdb->prepare( ' WHERE listing_status = %s', "$status" );
	}

	return (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}directory_listings $extra_checks" );
}

/**
 * Get an listing by id
 *
 * @param int $id
 * @return object
 */
function directory_plugin_get_single_listing( $id ) {
	global $wpdb;
	return $wpdb->get_row(
		$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}directory_listings WHERE id = %d", $id )
	);
}

/**
 * Delete an  Listing
 *
 * @return int
 */
function directory_plugin_delete_listing( $id ) {
	global $wpdb;
	return $wpdb->delete(
		$wpdb->prefix . 'directory_listings',
		[ 'id' => $id ],
		[ '%d' ]
	);
}

/**
 * Upload  Listing imaeg
 *
 * @return array
 */
function directory_plugin_upload_listing_image() {
	if ( isset( $_FILES['file']['name'] ) ) {
		// pretty_log( '$request', $_FILES['file'] );
		$attachment_id = media_handle_upload( 'file', 0 );
		if ( is_wp_error( $attachment_id ) ) {
			$result['success'] = false;
			$result['message'] = __( 'Error uploading file.', 'directory-plugin' );
		} else {
			$result['attachment_id'] = $attachment_id;
			$result['success']       = true;
			$result['message']       = __( 'File uploading successfull.', 'directory-plugin' );
		}
		wp_send_json( $result );
	}
}
add_action( 'wp_ajax_upload_listing_image', 'directory_plugin_upload_listing_image' );
add_action( 'wp_ajax_nopriv_upload_listing_image', 'directory_plugin_upload_listing_image' );
