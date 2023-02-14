<?php
	$users         = get_users();
	$selected_user = $listing->author;
?>
<div class="wrap">

	<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Address', 'directory-plugin' ); ?></h1>

	<hr class="wp-header-end">

	<?php if ( isset( $_GET['updated'] ) ) : ?>
		<div class="notice notice-success">
			<p><?php esc_html_e( 'Listing Updated Successfully.', 'directory-plugin' ); ?></p>
		</div>
	<?php endif; ?>

	<form id="posts-filter" action="" method="post"> 
		<table class="form-table">
			<tbody>
				<tr class="row">
					<th scope="row">
						<label for="title"><?php esc_html_e( 'Title', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="title" id="title" class="regulr-text" value="<?php echo esc_attr( $listing->title ); ?>">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="content"><?php esc_html_e( 'Content', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<textarea name="content" id="content" class="regular-text"><?php echo esc_attr( $listing->content ); ?></textarea> 
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="author"><?php esc_html_e( 'Author', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<select name="author" id="filter-by-author">
							<?php
							foreach ( $users as $user ) {
								$name = $user->display_name ? $user->display_name : $user->user_login;
								?>
									<option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $selected_user, $user->ID ); ?>><?php echo esc_html( $name ); ?></option>
								<?php
							}
							?>
						</select>
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="listing_status"><?php esc_html_e( 'Status', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<select name="listing_status" id="listing_status">
							<option value="active" <?php selected( $listing->listing_status, 'active' ); ?>>Active</option>
							<option value="inactive" <?php selected( $listing->listing_status, 'inactive' ); ?>>In Active</option>
						</select>
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="preview_image"><?php esc_html_e( 'Preview Image', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<?php
						$directory_plugin_image_data = ! empty( $listing->preview_image ) ? explode( ',', $listing->preview_image ) : [];
						?>
						<div class="img-wrap">
							<span class="img-remove">X</span>
							<img class="img-preview <?php echo ( ( is_array( $directory_plugin_image_data ) && ! empty( $directory_plugin_image_data[1] ) ) ? '' : 'hide' ); ?>" src="<?php echo esc_url( $directory_plugin_image_data[1] ); ?>" width="100" height="auto" alt="image"> 
						</div>
						<input type="hidden" class="wpx-img-field" id="preview_image" name="preview_image" value="<?php echo esc_attr( $listing->preview_image ); ?>"/> 
						<input type="button" class="button wpx-browse" data-title="<?php esc_attr_e( 'Media Gallery' ); ?>" data-select-text="<?php esc_attr_e( 'Select Image', 'directory-plugin' ); ?>" value="<?php esc_attr_e( 'Upload/Edit Image', 'directory-plugin' ); ?>"/>
					</td>
				</tr>
			</tbody>

		</table>
			<input type="hidden" name="id" value="<?php echo esc_attr( $listing->id ); ?>">
			<?php wp_nonce_field( 'directory-listings' ); ?>
			<?php submit_button( esc_html__( 'Update Listings', 'directory-plugin' ), 'primary', 'submit_listings' ); ?>
	</form>

</div>
