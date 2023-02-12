<?php
/**
 * directory plugin's custom functions.
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
		return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'tbr-core' ) );
	}

	return $wpdb->insert_id;
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
