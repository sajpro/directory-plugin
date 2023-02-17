<?php
/**
 * Listings Dynamic Block handler
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\DynamicBlocks;

/**
 * The Listings Handler Class
 */
class Listings {
	/**
	 * Register blocks
	 */
	public static function register() {
		$self = new self();

		$blocks_dir       = DIRECTORY_PLUGIN_PATH . '/blocks/listings/block.json';
		$blocks_json      = file_get_contents( $blocks_dir );
		$attributes_array = json_decode( $blocks_json, true );

		register_block_type(
			'directory-plugin/listings',
			[
				'style'           => $attributes_array['style'],
				'editor_style'    => $attributes_array['editorStyle'],
				'editor_script'   => $attributes_array['editorScript'],
				'render_callback' => [ $self, 'listing_dynamic_render_callback' ],
				// 'attributes'      => $attributes_array['attributes'],
				'attributes'      => [
					'blockId' => [
						'type' => 'string',
						'default' => '',
					],
					'title' => [
						'type' => 'string',
						'default' => 'This is Title',
					],
					'subtitle' => [
						'type' => 'string',
						'default' => 'This is Subtitle',
					],
					'number' => [
						'type' => 'integer',
						'default' => 12,
					],
					'showPagination' => [
						'type' => 'boolean',
						'default' => true,
					],
					'showSubmitButton' => [
						'type' => 'boolean',
						'default' => true,
					],
					'align' => [
						'type' => 'string',
						'default' => 'wide',
					],
					'align' => [
						'type' => 'string',
						'default' => 'wide',
					],
					'secTitleNormalColor' => [
						'type' => 'string',
						'default' => '',
					],
					'secTitleHoverColor' => [
						'type' => 'string',
						'default' => '',
					],
					'secTitleTypography' => [
						'type' => 'object',
						'default' => [],
						'items' => [
							'type' => 'string',
						],
					],
					'wrapperMargin' => [
						'type' => 'object',
						'default' => [],
						'items' => [
							'type' => 'string',
						],
					],
					'wrapperBgType' => [
						'type' => 'string',
						'default' => 'classic',
					],					
					'wrapperBgColor' => [
						'type' => 'string',
						'default' => '',
					],
					'wrapperBgGradient' => [
						'type' => 'string',
						'default' => '',
					],
					'wrapperBgImage' => [
						'type' => 'object',
						'default' => [],
						'items' => [
							'type' => 'string',
						],
					],
					'wrapperHoverBgType' => [
						'type' => 'string',
						'default' => 'classic',
					],					
					'wrapperHoverBgColor' => [
						'type' => 'string',
						'default' => '',
					],
					'wrapperHoverBgGradient' => [
						'type' => 'string',
						'default' => '',
					],
					'wrapperBgTransition' => [
						'type' => 'string',
						'default' => '',
					],
					'wrapperCustomCss' => [
						'type' => 'string',
						'default' => '',
					],
					'wrapperHoverBgImage' => [
						'type' => 'object',
						'default' => [],
						'items' => [
							'type' => 'string',
						],
					],
				]
			]
		);
	}

	/**
	 * Listing blocks dynaic content
	 */
	public function listing_dynamic_render_callback( $attributes, $content ) {
		$title           = '';
		$number          = 12;
		$show_pagination = true;
		$show_submit_btn = true;
		$align           = '';

		if ( isset( $attributes['title'] ) && ! empty( $attributes['title'] ) ) {
			$title = $attributes['title'];
		}

		if ( isset( $attributes['number'] ) && ! empty( $attributes['number'] ) ) {
			$number = $attributes['number'];
		}

		if ( isset( $attributes['showPagination'] ) ) {
			$show_pagination = $attributes['showPagination'];
		}

		if ( isset( $attributes['showSubmitButton'] ) ) {
			$show_submit_btn = $attributes['showSubmitButton'];
		}

		if ( isset( $attributes['align'] ) && ! empty( $attributes['align'] ) ) {
			$align = 'align' . $attributes['align'];
		}

		$api_url = add_query_arg(
			[
				'number' => $number,
			],
			get_rest_url( null, 'directory/v1/listings' )
		);

		$remote_request = wp_remote_get(
			$api_url,
			[
				'timeout' => apply_filters( 'dp_remote_get_timeout', 300 ),
			]
		);

		$response_code = wp_remote_retrieve_response_code( $remote_request );
		$response_body = (array) json_decode( wp_remote_retrieve_body( $remote_request ) );
		$pages         = $response_body['pages'];
		$prev          = $response_body['prev'];
		$next          = $response_body['next'];

		$classnames         = [];
		$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $classnames ) ] );

		ob_start(); ?>
			<div <?php echo esc_attr( $wrapper_attributes ); ?>>
				<?php if ( ( $response_code == 200 ) && ( $response_body['success'] == true ) ) : ?>
					<div id="listings-wrap">
						<div class="loader-wrap hidden">
							<div class="loader">
								<div class="svg-loader">
									<svg class="svg-container" height="100" width="100" viewBox="0 0 100 100">
										<circle class="loader-svg bg" cx="50" cy="50" r="45"></circle>
										<circle class="loader-svg animate" cx="50" cy="50" r="45"></circle>
									</svg>
								</div>
							</div>
						</div>
						<div class="wrapper <?php echo esc_attr( $align ); ?>">
							<?php
							if ( count( $response_body['listings'] ) > 0 ) {
								foreach ( $response_body['listings'] as $listing ) {
									$image_url = wp_get_attachment_url( $listing->preview_image );
									?>
										<div class="cell">
											<?php if ( $image_url ) : ?>
												<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $listing->id ); ?>">
											<?php endif; ?>
											<h2>ID: <?php echo esc_html( $listing->id ); ?></h2>
											<h5>Title: <?php echo esc_html( $listing->title ); ?></h5>
											<p><b>Content</b>: <?php echo esc_html( wp_trim_words( $listing->content, 12, '...' ) ); ?></p>
											<p><b>Status</b>: <?php echo esc_html( $listing->listing_status ); ?></p>
											<p><b>Author</b>: <?php echo esc_html( $listing->author ); ?></p>
											<p><b>Submitted</b>: <?php echo esc_html( $listing->created_at ); ?></p>
										</div>
									<?php
								}
							}
							?>
						</div>
					</div>
					<?php if ( $show_pagination ) : ?>
						<div class="listings-pagination">
							<input type="hidden" name="pages" id="pages" value="<?php echo esc_attr( $pages ); ?>">
							<input type="hidden" name="number" id="number" value="<?php echo esc_attr( $number ); ?>">
							<button class="prev-btn <?php echo esc_attr( $prev < 2 ? 'hidden' : '' ); ?>" value="<?php echo esc_attr( $prev ); ?>">Prev</button>
							<?php
							for ( $i = 0; $i < ( $pages ); $i++ ) {
								$current = $i + 1;
								echo '<button class="page-number ' . esc_attr( $current == ( $next - 1 ) ? 'active' : '' ) . '" value="' . esc_html( $current ) . '">' . esc_html( $current ) . '</button>';
							}
							?>
							<button class="next-btn <?php echo esc_attr( $next > $pages ? 'hidden' : '' ); ?>" value="<?php echo esc_attr( $next ); ?>">Next</button>
						</div>
					<?php endif; ?>
				<?php endif; ?>

				<?php if ( $show_submit_btn ) : ?>
					<button class="submit-toggle">Submit Listing</button>
				<?php endif; ?>

				<div class="dp-modal">
					<div class="listing-form <?php echo esc_attr( ! is_user_logged_in() ? 'login-first' : '' ); ?>">
						<span class="close-modal">&times;</span>
						<?php if ( is_user_logged_in() ) : ?>
							<form id="submit-listing-form" method="post" action="" enctype="multipart/form-data">
								<label for="title"><?php esc_html_e( 'Title:', 'directory-plugin' ); ?></label>
								<input type="text" id="title" name="title" placeholder="Title...">
								<span class="error hidden">Title can not be empty.</span><br>

								<label for="content"><?php esc_html_e( 'Content:', 'directory-plugin' ); ?></label>
								<textarea id="content" name="content" placeholder="Content..." style="height:200px"></textarea>

								<label for="status"><?php esc_html_e( 'Status:', 'directory-plugin' ); ?></label>
								<select id="status" name="status">
									<option value="active"><?php esc_html_e( 'Active', 'directory-plugin' ); ?></option>
									<option value="inactive"><?php esc_html_e( 'Inactive', 'directory-plugin' ); ?></option>
								</select>

								<label><?php esc_html_e( 'Image:', 'directory-plugin' ); ?></label>
								<div class="image-upload-area">
									<div>
										<input type="file" id="image" name="image" class="hidden">
										<label for="image" class="upload-btn">Upload Image</label>
									</div>
									<div class="preview-image hidden">
										<span>&times;</span>
										<img src="" alt="preview image">
									</div>
								</div>
								<br>
								<br>
								<?php wp_nonce_field( 'dp-listing-image-upload', 'dp-listing-image-upload-nonce' ); ?>
								<input type="hidden" id="autor" value="<? echo get_current_user_id(); ?>">
								<div class="submit-btn">
									<input type="submit" id="submit-listing" value="Submit">
									<div class="loader-wrap hidden">
										<div class="loader">
											<div class="svg-loader">
												<svg class="svg-container" height="100" width="100" viewBox="0 0 100 100">
													<circle class="loader-svg bg" cx="50" cy="50" r="45"></circle>
													<circle class="loader-svg animate" cx="50" cy="50" r="45"></circle>
												</svg>
											</div>
										</div>
									</div>
									<h1 class="success-msg hidden">Success</h1>
									<h1 class="error-msg hidden"></h1>
								</div>

							</form>
						<?php else : ?>
							<p class="no-access"><?php echo sprintf( __( 'You need to <a href="%1$s">login</a> first to submit a listing.', 'directory-plugin' ), esc_url( wp_login_url() ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}
}
