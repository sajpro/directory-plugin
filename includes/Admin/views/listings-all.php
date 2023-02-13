<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'All Listings', 'directory-plugin' ); ?></h1>

	<a class="page-title-action" href="<?php echo esc_url( admin_url( 'admin.php?page=directory-listings&action=create' ) ); ?>"><?php esc_html_e( 'Add New', 'directory-plugin' ); ?></a>
	<hr class="wp-header-end">
	
	<?php if ( isset( $_GET['deleted'] ) ) : ?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( '1 Listing deleted successfully.', 'directory-plugin' ); ?></p>
		</div>
	<?php endif; ?>

	<?php
	if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		|| ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
		$delete_ids = esc_sql( $_POST['bulk-delete'] );

		// loop over the array of record IDs and delete them.
		foreach ( $delete_ids as $id ) {
			directory_plugin_delete_listing( $id );
		}
		// show admin notice.
		$number_of_listings = count( $delete_ids );

		$msg = sprintf(
			_n(
				'%d Listing deleted successfully.',
				'%d Listings deleted successfully.',
				$number_of_listings
			),
			$number_of_listings
		);
		echo '<div class="notice notice-success is-dismissible"><p>' . $msg . '</p></div>';
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
