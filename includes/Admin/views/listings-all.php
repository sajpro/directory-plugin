<div class="wrap">
	<h1 class="wp-heading-inline"><?php _e( 'All Listings', 'directory-plugin' ); ?></h1>

	<a class="page-title-action" href="<?php echo esc_url( admin_url( 'admin.php?page=directory-listings&action=create' ) ); ?>"><?php _e( 'Add New', 'directory-plugin' ); ?></a>

	<form action="" method="post"> 

		<?php
			$table = new Sajib\DP\Admin\Listing_Table();
			$table->prepare_items();
			$table->display();
		?>
	</form>
</div>
