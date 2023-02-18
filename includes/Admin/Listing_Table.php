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

	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct(
			[
				'singular' => 'listing',
				'plural'   => 'listings',
				'ajax'     => false,
			]
		);
	}

	/**
	 * Get a list of columns
	 */
	public function get_columns() {
		return [
			'cb'             => '<input type="checkbox">',
			'title'          => esc_html__( 'Title', 'directory-plugin' ),
			'content'        => esc_html__( 'Content', 'directory-plugin' ),
			'author'         => esc_html__( 'Author', 'directory-plugin' ),
			'listing_status' => esc_html__( 'Status', 'directory-plugin' ),
			'preview_image'  => esc_html__( 'Image', 'directory-plugin' ),
			'created_at'     => esc_html__( 'Submission Date', 'directory-plugin' ),
		];
	}

	/**
	 * Get a list of sortable columns.
	 */
	public function get_sortable_columns() {
		return [
			'title'      => [ 'title', true ],
			'created_at' => [ 'created_at', true ],
		];
	}

	/**
	 * Default columns to display its value.
	 */
	protected function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'preview_image':
				$image_url = wp_get_attachment_url( $item->$column_name );
				return isset( $item->$column_name ) ? '<img width="100" height="auto" src="' . esc_url( $image_url ) . '"/>' : '';
				break;

			case 'created_at':
				return isset( $item->$column_name ) ? esc_html( mysql2date( 'F j, Y \a\t g:i A', $item->$column_name ) ) : '';
				break;

			case 'author':
				$user = get_user_by( 'id', $item->$column_name );
				$name = $user->display_name;
				return isset( $item->$column_name ) ? esc_html( $name ) : '';
				break;

			default:
				return isset( $item->$column_name ) ? esc_html( $item->$column_name ) : '';
		}
	}

	/**
	 * Title column of the table
	 */
	public function column_title( $item ) {
		$actions = [];

		$actions['edit'] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=directory-listings&action=edit&listing=' . intval( $item->id ) ) ), esc_html__( 'Edit', 'directory-plugin' ) );

		$actions['delete'] = sprintf( '<a href="%s" onclick="return confirm(\'Are you sure? \');">%s</a>', esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=directory-listings-delete&listing=' . intval( $item->id ) ), 'directory-listings-delete' ) ), esc_html__( 'Delete', 'directory-plugin' ) );

		return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', esc_url( admin_url( 'admin.php?page=directory-listings&action=edit&listing=' . intval( $item->id ) ) ), $item->title, $this->row_actions( $actions ) );
	}

	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="bulk-delete[]" value="%d" />', intval( $item->id ) );
	}

	/**
	 * Get an associative array ( option_name => option_title ) with the list
	 * of bulk actions available on this table.
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => esc_html__( 'Delete', 'directory-plugin' ),
		];

		return $actions;
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination
	 */
	public function extra_tablenav( $which ) {
		if ( $which == 'top' ) {
			$active = ( ! empty( $_REQUEST['author'] ) ? wp_unslash( $_REQUEST['author'] ) : '' );
			$users  = get_users();
			?>
			<div class="alignleft actions bulkactions">
				<select name="author" id="filter-by-author">
					<option value=""><?php esc_html_e( 'All Authors', 'directory-plugin' ); ?></option>
					<?php
					foreach ( $users as $user ) {
						$name = $user->display_name ? $user->display_name : $user->user_login;
						?>
							<option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $active, $user->ID ); ?>><?php echo esc_html( $name ); ?></option>
						<?php
					}
					?>
				</select>
				<input type="submit" class="button" value="<?php esc_html_e( 'Filter', 'directory-plugin' ); ?>">
			</div>
			<?php
		}
	}

	/**
	 * Define which columns are hidden
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		$screen = get_current_screen();
		return get_user_option( 'manage' . $screen->id . 'columnshidden' );
	}

	/**
	 * Message to be displayed when there are no listings
	 */
	public function no_items() {
		esc_html_e( 'No listings found.', 'directory-plugin' );
	}

	/**
	 * Get an associative array ( id => link ) with the list
	 * of views available on this table.
	 */
	protected function get_views() {
		$views   = [];
		$current = ( ! empty( $_REQUEST['listing_status'] ) ? wp_unslash( $_REQUEST['listing_status'] ) : 'all' );

		// All link
		$all_count    = directory_plugin_listings_total_count();
		$class        = ( $current == 'all' ? ' class="current"' : '' );
		$all_url      = remove_query_arg( 'listing_status' );
		$views['all'] = "<a href='{$all_url }' {$class} >All <span class='count'>({$all_count})</span></a>";

		// Active link
		$active_count    = directory_plugin_listings_total_count( [ 'status' => 'active' ] );
		$active_url      = add_query_arg( 'listing_status', 'active' );
		$class           = ( $current == 'active' ? ' class="current"' : '' );
		$views['active'] = "<a href='{$active_url}' {$class} >Active <span class='count'>({$active_count})</span></a>";

		// Inactive link
		$inactive_count    = directory_plugin_listings_total_count( [ 'status' => 'inactive' ] );
		$inactive_url      = add_query_arg( 'listing_status', 'inactive' );
		$class             = ( $current == 'inactive' ? ' class="current"' : '' );
		$views['inactive'] = "<a href='{$inactive_url}' {$class} >Inactive <span class='count'>({$inactive_count})</span></a>";

		return $views;
	}

	/**
	 * Prepares the list of items for displaying
	 */
	public function prepare_items() {
		$filter = [];
		if ( isset( $_POST['s'] ) ) {
			$filter['search'] = wp_unslash( $_POST['s'] );
		}
		if ( isset( $_POST['author'] ) ) {
			$filter['author'] = sanitize_text_field( wp_unslash( $_POST['author'] ) );
		}

		if ( isset( $_GET['listing_status'] ) ) {
			$filter['status'] = sanitize_text_field( wp_unslash( $_GET['listing_status'] ) );
		}

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns() ? $this->get_hidden_columns() : [];
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [ $columns, $hidden, $sortable ];

		$per_page     = $this->get_items_per_page( 'listings_per_page', 20 );
		$current_page = $this->get_pagenum();
		$offset       = ( $current_page - 1 ) * $per_page;

		$args = [
			'number' => $per_page,
			'offset' => $offset,
		];

		$total_items = directory_plugin_listings_total_count();

		if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
			$args['orderby'] = wp_unslash( $_REQUEST['orderby'] );
			$args['order']   = wp_unslash( $_REQUEST['order'] );
		}

		$this->items = directory_plugin_listing_get( $args, $filter );

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
			]
		);
	}
}
