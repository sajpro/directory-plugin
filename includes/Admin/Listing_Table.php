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
			'cb'                      => '<input type="checkbox">',
			'title'                   => __( 'Title', 'tbr-core' ),
			'content'                 => __( 'content', 'tbr-core' ),
			'created_by'              => __( 'Author', 'tbr-core' ),
			'listing_status'          => __( 'Status', 'tbr-core' ),
			'preview_image'           => __( 'Image', 'tbr-core' ),
			'created_at'              => __( 'Submission Date', 'tbr-core' ),
		];
	}

	public function get_sortable_columns() {
		return [
			'name'       => [ 'name', true ],
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

	public function column_name( $item ) {
		$actions = [];

		$actions['edit']   = sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=tbr-core&action=edit&id=' . $item->id ), __( 'Edit', 'tbr-core' ) );
		$actions['delete'] = sprintf( '<a href="%s" onclick="return confirm(\'Are you sure? \');">%s</a>', wp_nonce_url( admin_url( 'admin-post.php?action=tbr-core-adrress-delete&id=' . $item->id ), 'tbr-core-adrress-delete' ), __( 'Delete', 'tbr-core' ) );

		return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=tbr-core&action=view&id=' . $item->id ), $item->name, $this->row_actions( $actions ) );
	}

	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="address_id[]" value="%d" />', $item->id );
	}

	public function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = [];
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $columns, $hidden, $sortable ];

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;

		$total_items = tbr_core_addresses_count();

		$args = [
			'number' => $per_page,
			'offset' => $offset,
		];

		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
			$args['orderby'] = $_REQUEST['orderby'];
			$args['order']   = $_REQUEST['order'];
		}

		$this->items = tbr_core_get_addresses( $args );

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
			]
		);
	}
}
