<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'All Listings', 'directory-plugin' ); ?></h1>

	<a class="page-title-action" href="<?php echo esc_url( admin_url( 'admin.php?page=directory-listings-create' ) ); ?>"><?php esc_html_e( 'Add New', 'directory-plugin' ); ?></a>
	<?php
	if ( isset( $_POST['s'] ) && ! empty( $_POST['s'] ) ) {
		echo sprintf(
			'<span class="subtitle">%1$s <strong>%2$s</strong></span>',
			__( 'Search results for:', 'directory-plugin' ),
			wp_unslash( $_POST['s'] )
		);
	}
	?>
	<hr class="wp-header-end">
	
	<?php
	if ( isset( $_GET['deleted'] ) ) {
		$delete_count = isset( $_GET['delete-total'] ) ? intval( $_GET['delete-total'] ) : 1;

		$msg = sprintf(
			_n(
				'%d Listing deleted successfully.',
				'%d Listings deleted successfully.',
				$delete_count
			),
			$delete_count
		);
		echo sprintf( __( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', 'directory-plugin' ), $msg );
	}
	?>

	<form id="posts-filter" action="" method="post"> 

		<?php
			$table = new Sajib\DP\Admin\Listing_Table();
			$table->views();
			$table->prepare_items();
			$table->search_box( 'Search', 's' );
			$table->display();
		?>
	</form>
</div>
