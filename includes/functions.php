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

		directory_plugin_purge_cache( $id );

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

		directory_plugin_purge_cache();

		return [
			'id'         => $wpdb->insert_id,
			'created_at' => $data['created_at'],
		];
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
		'order'   => 'desc',
	];

	$args = wp_parse_args( $args, $defaults );

	$last_changed = wp_cache_get_last_changed( 'dirlistings' );
	$hash         = md5( serialize( array_diff_assoc( $args, $defaults ) ) );
	$cache_key    = "all:$hash:$last_changed";

	$extra_checks = '';
	$search       = '';

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

	$listings = $wpdb->prefix . 'directory_listings';
	$postmeta = $wpdb->postmeta;

	$sql = $wpdb->prepare(
		"SELECT $listings.*, $postmeta.meta_value as image_url FROM {$listings}
		JOIN $postmeta
		ON $listings.preview_image = $postmeta.post_id
		WHERE ($listings.title LIKE %s 
		OR $listings.content LIKE %s)
		AND $postmeta.post_id = $listings.preview_image
		AND $postmeta.meta_key = %s
		$extra_checks
		ORDER BY {$args['orderby']} {$args['order']}
		LIMIT %d, %d",
		"%$search%",
		"%$search%",
		'_wp_attached_file',
		$args['offset'],
		$args['number']
	);

	$items = wp_cache_get( $cache_key, 'dirlistings' );
	if ( false === $items ) {
		$items = $wpdb->get_results( $sql );
		wp_cache_set( $cache_key, $items, 'dirlistings' );
	}

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

	$total_count = wp_cache_get( 'count', 'dirlistings' );

	if ( false === $total_count ) {
		$total_count = (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}directory_listings $extra_checks" );
		wp_cache_set( 'count', $total_count, 'dirlistings' );
	}

	return $total_count;
}

/**
 * Get an listing by id
 *
 * @param int $id
 * @return object
 */
function directory_plugin_get_single_listing( $id ) {
	global $wpdb;

	$listing = wp_cache_get( 'listing-' . $id, 'dirlistings' );

	if ( false === $listing ) {
		$listing = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}directory_listings WHERE id = %d", $id )
		);
		wp_cache_set( 'listing-' . $id, $listing, 'dirlistings' );
	}

	return $listing;
}

/**
 * Delete an  Listing
 *
 * @return int
 */
function directory_plugin_delete_listing( $id ) {
	global $wpdb;

	directory_plugin_purge_cache( $id );

	return $wpdb->delete(
		$wpdb->prefix . 'directory_listings',
		[ 'id' => $id ],
		[ '%d' ]
	);
}

/**
 * Purge cache
 *
 * @return void
 */
function directory_plugin_purge_cache( $id = null ) {
	$group = 'dirlistings';

	if ( $id ) {
		wp_cache_delete( 'listing-' . $id, $group );
	}

	wp_cache_delete( 'count', $group );
	wp_cache_set( 'last_changed', microtime(), $group );
}

