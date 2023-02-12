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
							<option value="active" selected>Active</option>
							<option value="in-active">In Active</option>
						</select>
					</td>
				</tr>
				<tr class="row">
					<th scope="row">
						<label for="preview_image"><?php esc_html_e( 'Preview Image', 'directory-plugin' ); ?></label>
					</th>
					<td>
						<input type="file" name="preview_image" id="preview_image" class="regulr-text"/>
					</td>
				</tr>
			</tbody>

		</table>
			<?php wp_nonce_field( 'create-listing' ); ?>
			<?php submit_button( __( 'Add Address', 'directory-plugin' ), 'primary', 'submit_address' ); ?>
	</form>

</div>
