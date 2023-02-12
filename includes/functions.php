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
		'created_by'     => '',
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
function directory_plugin_listing_get( $args = [] ) {
	global $wpdb;

	$defaults = [
		'number'  => 20,
		'offset'  => 0,
		'orderby' => 'id',
		'order'   => 'asc',
	];

	$args = wp_parse_args( $args, $defaults );

	$sql = $wpdb->prepare(
		"SELECT * FROM {$wpdb->prefix}directory_listings 
		ORDER BY {$args['orderby']} {$args['order']}
		LIMIT %d, %d",
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
function directory_plugin_listings_total_count() {
	global $wpdb;
	return (int) $wpdb->get_var( "SELECT COUNT(id) FROM {$wpdb->prefix}directory_listings" );
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
