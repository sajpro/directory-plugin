<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'All Listings', 'directory-plugin' ); ?></h1>

	<a class="page-title-action" href="<?php echo esc_url( admin_url( 'admin.php?page=directory-listings&action=create' ) ); ?>"><?php esc_html_e( 'Add New', 'directory-plugin' ); ?></a>
	<hr class="wp-header-end">

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
