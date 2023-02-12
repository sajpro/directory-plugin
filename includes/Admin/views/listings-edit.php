<div class="wrap">

	<h1 class="wp-heading-inline"><?php esc_html_e( 'Edit Address', 'directory-plugin' ); ?></h1>

	<form action="" method="post">
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
						<?php
						$directory_plugin_user = get_user_by( 'id', $listing->created_by );
						?>
						<input type="text" name="author" id="author" class="regulr-text" value="<?php echo esc_attr( $directory_plugin_user->display_name ? $directory_plugin_user->display_name : $user->user_login ); ?>">
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="status"><?php esc_html_e( 'Status', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<select name="status" id="status">
							<option value="active" <?php echo esc_attr( ( $listing->listing_status == 'active' ) ? 'selected' : '' ); ?>>Active</option>
							<option value="inactive" <?php echo esc_attr( ( $listing->listing_status == 'inactive' ) ? 'selected' : '' ); ?>>In Active</option>
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
							<img class="img-preview <?php echo ( ! empty( $directory_plugin_image_data ) ? '' : 'hide' ); ?>" src="<?php echo esc_url( $directory_plugin_image_data[1] ); ?>" width="100" height="auto" alt="image>"> 
						</div>
						<input type="hidden" class="wpx-img-field" id="preview_image" name="preview_image" value="<?php echo esc_attr( $directory_plugin_image_data ); ?>"/> 
						<input type="button" class="button wpx-browse" data-title="<?php esc_attr_e( 'Media Gallery' ); ?>" data-select-text="<?php esc_attr_e( 'Select Image', 'directory-plugin' ); ?>" value="<?php esc_attr_e( 'Upload/Edit Image', 'directory-plugin' ); ?>"/>
					</td>
				</tr>
			</tbody>

		</table>
			<input type="hidden" name="id" value="<?php echo esc_attr( $listing->id ); ?>">
			<?php wp_nonce_field( 'edit-address' ); ?>
			<?php submit_button( __( 'Update Address', 'directory-plugin' ), 'primary', 'submit_address' ); ?>
	</form>

</div>
