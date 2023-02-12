<?php
/**
 * Listings
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\Admin;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . '/wp-admin/includes/class-wp-list-table.php';
}

/**
 * Listings class handler
 */
class Listing_Table extends \WP_List_Table {

	function __construct() {
		parent::__construct(
			[
				'singular' => 'contact',
				'plural'   => 'contacts',
				'ajax'     => false,
			]
		);
	}

	public function get_columns() {
		return [
			'cb'             => '<input type="checkbox">',
			'title'          => __( 'Title', 'directory-plugin' ),
			'content'        => __( 'content', 'directory-plugin' ),
			'created_by'     => __( 'Author', 'directory-plugin' ),
			'listing_status' => __( 'Status', 'directory-plugin' ),
			'preview_image'  => __( 'Image', 'directory-plugin' ),
			'created_at'     => __( 'Submission Date', 'directory-plugin' ),
		];
	}

	public function get_sortable_columns() {
		return [
			'title'      => [ 'title', true ],
			'created_by' => [ 'created_by', true ],
			'created_at' => [ 'created_at', true ],
		];
	}

	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'value':
				break;

			default:
				return isset( $item->$column_name ) ? $item->$column_name : '';
		}
	}

	public function column_title( $item ) {
		$actions = [];

		$actions['edit']   = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=directory-listings&action=edit&listing=' . $item->id ), __( 'Edit', 'directory-plugin' ) );
		$actions['delete'] = sprintf( '<a href="%s" onclick="return confirm(\'Are you sure? \');">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=directory-listings-delete&listing=' . $item->id ), 'directory-listings-delete' ), __( 'Delete', 'directory-plugin' ) );

		return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=directory-listings&action=edit&listing=' . $item->id ), $item->title, $this->row_actions( $actions ) );
	}

	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="listing_id[]" value="%d" />', $item->id );
	}

	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = [];
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $columns, $hidden, $sortable ];

		$per_page = 20;

		$total_items = directory_plugin_listings_total_count();

		$this->items = directory_plugin_listing_get( $args );

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
			]
		);
	}
}
