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
						'type'    => 'string',
						'default' => '',
					],
					'blockStyles' => [
						'type'    => 'string',
						'default' => '',
					],
					'title' => [
						'type'    => 'string',
						'default' => 'This is Title',
					],
					'subtitle' => [
						'type'    => 'string',
						'default' => 'This is Subtitle',
					],
					'number' => [
						'type'    => 'integer',
						'default' => 12,
					],
					'showPagination' => [
						'type'    => 'boolean',
						'default' => true,
					],
					'showSubmitButton' => [
						'type'    => 'boolean',
						'default' => true,
					],
					'align' => [
						'type'    => 'string',
						'default' => 'wide',
					],
					'align' => [
						'type'    => 'string',
						'default' => 'wide',
					],
					'secTitleNormalColor' => [
						'type'    => 'string',
						'default' => '',
					],
					'secTitleHoverColor' => [
						'type'    => 'string',
						'default' => '',
					],
					'secTitleTransition' => [
						'type'    => 'string',
						'default' => '',
					],
					'secTitlePadding' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
					'secTitleTypography' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
					'secSubtitleNormalColor' => [
						'type'    => 'string',
						'default' => '',
					],
					'secSubtitleHoverColor' => [
						'type'    => 'string',
						'default' => '',
					],
					'secSubtitleTransition' => [
						'type'    => 'string',
						'default' => '',
					],
					'secSubtitlePadding' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
					'secSubtitleTypography' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
					'wrapperMargin' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
					'wrapperPadding' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
					'wrapperBgType' => [
						'type'    => 'string',
						'default' => 'classic',
					],
					'wrapperBgColor' => [
						'type'    => 'string',
						'default' => '',
					],
					'wrapperBgGradient' => [
						'type'    => 'string',
						'default' => '',
					],
					'wrapperBgImage' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
					'wrapperHoverBgType' => [
						'type'    => 'string',
						'default' => 'classic',
					],
					'wrapperHoverBgColor' => [
						'type'    => 'string',
						'default' => '',
					],
					'wrapperHoverBgGradient' => [
						'type'    => 'string',
						'default' => '',
					],
					'wrapperBgTransition' => [
						'type'    => 'string',
						'default' => '',
					],
					'wrapperCustomCss' => [
						'type'    => 'string',
						'default' => '',
					],
					'wrapperHoverBgImage' => [
						'type'    => 'object',
						'default' => [],
						'items'   => [
							'type' => 'string',
						],
					],
				],
			]
		);
	}

	/**
	 * Listing blocks dynaic content
	 */
	public function listing_dynamic_render_callback( $attributes, $content ) {
		$block_id        = '';
		$title           = '';
		$subtitle        = '';
		$number          = 12;
		$show_pagination = true;
		$show_submit_btn = true;
		$align           = '';

		if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
			$block_id = $attributes['blockId'];
		}

		if ( isset( $attributes['title'] ) && ! empty( $attributes['title'] ) ) {
			$title = $attributes['title'];
		}

		if ( isset( $attributes['subtitle'] ) && ! empty( $attributes['subtitle'] ) ) {
			$subtitle = $attributes['subtitle'];
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
		$pages         = $response_body['pages'] ? $response_body['pages'] : 0;
		$prev          = $response_body['prev'] ? $response_body['prev'] : 0;
		$total         = $response_body['total'] ? $response_body['total'] : 0;
		$next          = $response_body['next'] ? $response_body['next'] : 0;

		$classnames         = [];
		$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $classnames ) ] );

		ob_start(); ?>
			<div <?php echo esc_attr( $wrapper_attributes ); ?>>
				<div class="dp-listings-wrapper <?php echo esc_attr( $block_id ); ?>">
					<?php
					if ( $title ) {
						echo sprintf( __( '<h2 class="sec-title text-center">%s</h2>', 'directory-plugin' ), $title );
					}
					if ( $subtitle ) {
						echo sprintf( __( '<p class="sec-sub-title text-center">%s</p>', 'directory-plugin' ), $subtitle );
					}
					?>
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
							if ( ( $response_code == 200 ) && ( $response_body['success'] == true ) ){
								if ( count( $response_body['listings'] ) > 0 ) {
									foreach ( $response_body['listings'] as $listing ) {
										$image_url = wp_get_attachment_url( $listing->preview_image );
										$author    = get_userdata( $listing->author );
										?>
											<div class="cell">
												<?php if ( $image_url ) : ?>
													<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $listing->id ); ?>">
												<?php endif; ?>
												<h5><?php esc_html_e( 'Title', 'directory-plugin' ); ?>: <?php echo esc_html( $listing->title ); ?></h5>
												<p><b><?php esc_html_e( 'Content', 'directory-plugin' ); ?></b>: <?php echo esc_textarea( wp_trim_words( $listing->content, 12, '...' ) ); ?></p>
												<p><b><?php esc_html_e( 'Status', 'directory-plugin' ); ?></b>: <?php echo esc_html( $listing->listing_status ); ?></p>
												<p><b><?php esc_html_e( 'Author', 'directory-plugin' ); ?></b>: <?php echo esc_html( $author->data->display_name ); ?></p>
												<p><b><?php esc_html_e( 'Submitted', 'directory-plugin' ); ?></b>: <?php echo esc_html( $listing->created_at ); ?></p>
											</div>
										<?php
									}
								}
							}
							?>
						</div>
					</div>
					<?php if ( $show_pagination && ( $total > $number ) ) : ?>
						<div class="listings-pagination">
							<input type="hidden" name="allpage" id="allpage" value="<?php echo esc_attr( $total ); ?>">
							<input type="hidden" name="pages" id="pages" value="<?php echo esc_attr( $pages ); ?>">
							<input type="hidden" name="number" id="number" value="<?php echo esc_attr( $number ); ?>">
							<button class="prev-btn <?php echo esc_attr( $prev < 2 ? 'hidden' : '' ); ?>" value="<?php echo esc_attr( $prev ); ?>"><?php esc_html_e( 'Prev', 'directory-plugin' ); ?></button>
							<div class='pagi-num'>
								<?php
								for ( $i = 0; $i < ( $pages ); $i++ ) {
									$current = $i + 1;
									echo '<button class="page-number ' . esc_attr( $current == ( $next - 1 ) ? 'active' : '' ) . '" value="' . esc_attr( $current ) . '">' . esc_html( $current ) . '</button>';
								}
								?>
							</div>
							<button class="next-btn <?php echo esc_attr( $next > $pages ? 'hidden' : '' ); ?>" value="<?php echo esc_attr( $next ); ?>"><?php esc_html_e( 'Next', 'directory-plugin' ); ?></button>
						</div>
					<?php endif; ?>

					<?php if ( $show_submit_btn ) : ?>
						<button class="submit-toggle"><?php esc_html_e( 'Submit Listing', 'directory-plugin' ); ?></button>
					<?php endif; ?>

					<div class="dp-modal">
						<div class="listing-form <?php echo esc_attr( ! is_user_logged_in() ? 'login-first' : '' ); ?>">
							<span class="close-modal">&times;</span>
							<?php if ( is_user_logged_in() ) : ?>
								<form id="submit-listing-form" method="post" action="" enctype="multipart/form-data">
									<label for="title"><?php esc_html_e( 'Title:', 'directory-plugin' ); ?></label>
									<input type="text" id="title" name="title">
									<span class="error hidden"><?php esc_html_e( 'Title can not be empty.', 'directory-plugin' ); ?></span><br>

									<label for="content"><?php esc_html_e( 'Content:', 'directory-plugin' ); ?></label>
									<textarea id="content" name="content" style="height:200px"></textarea>

									<label for="status"><?php esc_html_e( 'Status:', 'directory-plugin' ); ?></label>
									<select id="status" name="status">
										<option value="active"><?php esc_html_e( 'Active', 'directory-plugin' ); ?></option>
										<option value="inactive"><?php esc_html_e( 'Inactive', 'directory-plugin' ); ?></option>
									</select>

									<label><?php esc_html_e( 'Image:', 'directory-plugin' ); ?></label>
									<div class="image-upload-area">
										<div>
											<input type="file" id="image" name="image" class="hidden">
											<label for="image" class="upload-btn"><?php esc_html_e( 'Upload Image', 'directory-plugin' ); ?></label>
										</div>
										<div class="preview-image hidden">
											<span>&times;</span>
											<img src="" alt="<?php esc_html_e( 'preview image', 'directory-plugin' ); ?>">
										</div>
									</div>
									<br>
									<br>
									<?php wp_nonce_field( 'dp-listing-image-upload', 'dp-listing-image-upload-nonce' ); ?>
									<input type="hidden" id="author" value="<?php echo get_current_user_id(); ?>">
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
										<h1 class="success-msg message hidden"><?php esc_html_e( 'Success', 'directory-plugin' ); ?></h1>
										<h1 class="error-msg message hidden"></h1>
									</div>

								</form>
							<?php else : ?>
								<p class="no-access"><?php echo sprintf( __( 'You need to <a href="%1$s">login</a> first to submit a listing.', 'directory-plugin' ), esc_url( wp_login_url() ) ); ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}
}
