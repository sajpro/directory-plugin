<div class="wrap">

	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Create Listing', 'directory-plugin' ); ?>
	</h1>

	<form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr class="row">
					<th scope="row">
						<label for="title"><?php esc_html_e( 'Title', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="title" id="title" class="regulr-text" value="">
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="content"><?php esc_html_e( 'Content', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<textarea name="content" id="content" class="regular-text"></textarea> 
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="author"><?php esc_html_e( 'Author', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<input type="text" name="author" id="author" class="regulr-text" value="">
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="status"><?php esc_html_e( 'Status', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<select name="status" id="status">
							<option value="active" selected><?php esc_html_e( 'Active', 'directory-plugin' ); ?></option>
							<option value="inactive"><?php esc_html_e( 'In Active', 'directory-plugin' ); ?></option>
						</select>
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="preview_image"><?php esc_html_e( 'Preview Image', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<div class="img-wrap">
							<span class="img-remove">X</span>
							<img class="img-preview hide" src="" width="100" height="auto" alt="image"> 
						</div>
						<input type="hidden" class="wpx-img-field" id="preview_image" name="preview_image" value=""/> 
						<input type="button" class="button wpx-browse" data-title="<?php esc_attr_e( 'Media Gallery' ); ?>" data-select-text="<?php esc_attr_e( 'Select Image', 'directory-plugin' ); ?>" value="<?php esc_attr_e( 'Upload/Edit Image', 'directory-plugin' ); ?>"/>
					</td>
				</tr>
			</tbody>

		</table>
			<?php wp_nonce_field( 'directory-listings' ); ?>
			<?php submit_button( esc_html__( 'Add Listing', 'directory-plugin' ), 'primary', 'submit_listings' ); ?>
	</form>

</div>
