<?php
/**
 * Blocks loader
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\Blocks;

/**
 * The Style Loader Class handler
 * It will generate a new css file and upload to uploads dir
 */
class StyleLoader {


	private static $instance;

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_styles' ] );
		add_action( 'save_post', [ $this, 'stylegenerator_get_post_content' ], 10, 3 );
	}

	/**
	 * Enqueue frontend css for post/page if exists
	 */
	public function enqueue_frontend_styles() {
		global $post;

		if ( ! empty( $post ) && ! empty( $post->ID ) ) {
			$upload_dir = wp_upload_dir();

			// Page/Post Style Enqueue
			if ( file_exists( $upload_dir['basedir'] . '/dp-style/dp-style-' . abs( $post->ID ) . '.min.css' ) ) {
				$file_path = $upload_dir['baseurl'] . '/dp-style/dp-style-' . $post->ID . '.min.css';
				wp_enqueue_style( 'dp-block-style-' . $post->ID, $file_path, [], md5_file( $file_path ) );
			}
		}
	}

	/**
	 * Get post content when page is saved
	 */
	public function stylegenerator_get_post_content( $post_id, $post, $update ) {
		$post_type          = get_post_type( $post_id );
		$allowed_post_types = [
			'page',
			'post',
		];

		// If This page is draft, do nothing
		if ( isset( $post->post_status ) && 'auto-draft' == $post->post_status ) {
			return;
		}

		// Autosave, do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// If it's a post revision, do nothing
		if ( false !== wp_is_post_revision( $post_id ) ) {
			return;
		}

		$parsed_content = parse_blocks( $post->post_content );

		if ( is_array( $parsed_content ) && ! empty( $parsed_content ) ) {
			$dp_blocks       = [];
			$parsed_response = self::parsed_blocks_styles( $parsed_content, $dp_blocks );
			$style           = self::blocks_to_style_array( $parsed_response );
			// Write CSS file for this page
			self::write_block_styles( $style, $post );
		}
	}

	/**
	 * Function for parsing blocks
	 */
	public static function parsed_blocks_styles( $block, &$dp_blocks ) {
		if ( count( $block ) > 0 ) {
			foreach ( $block as $item ) {
				$attributes = $item['attrs'];

				$blockId = '';
				if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
					$blockId = $attributes['blockId'];
				}
				$blockStyles = '';
				if ( isset( $attributes['blockStyles'] ) && ! empty( $attributes['blockStyles'] ) ) {
					$blockStyles = $attributes['blockStyles'];
				}

				$wrapperCustomCss = '';
				if ( isset( $attributes['wrapperCustomCss'] ) && ! empty( $attributes['wrapperCustomCss'] ) ) {
					$wrapperCustomCss = $attributes['wrapperCustomCss'];
				}

				if ( isset( $item['innerBlocks'] ) && count( $item['innerBlocks'] ) > 0 ) {
					if ( isset( $attributes['blockStyles'] ) && ! empty( $attributes['blockStyles'] ) ) {
						$dp_blocks[ $blockId ] = [
							'blockStyles'      => $blockStyles,
							'wrapperCustomCss' => $wrapperCustomCss,
						];
					}
				} elseif ( isset( $attributes['blockStyles'] ) && ! empty( $attributes['blockStyles'] ) ) {
					$dp_blocks[ $blockId ] = [
						'blockStyles'      => $blockStyles,
						'wrapperCustomCss' => $wrapperCustomCss,
					];
				}
			}
		}

		return $dp_blocks;
	}

	/**
	 * Function, Blocks array to Style Array
	 */
	public static function blocks_to_style_array( $blocks ) {
		$style_array = [];
		if ( is_array( $blocks ) && count( $blocks ) > 0 ) {
			foreach ( $blocks as $blockId => $block ) {
				$style_array[ $blockId ] = [
					'desktop'          => '',
					'tablet'           => '',
					'mobile'           => '',
					'wrapperCustomCss' => '',
				];

				if ( is_array( $block ) && count( $block ) > 0 ) {
					foreach ( $block as $style ) {
						$value = (array) json_decode( $style );
						if ( is_array( $value ) && count( $value ) > 0 ) {
							if ( isset( $value['desktop'] ) ) {
								$style_array[ $blockId ]['desktop'] .= $value['desktop'];
							}
							if ( isset( $value['tablet'] ) ) {
								$style_array[ $blockId ]['tablet'] .= $value['tablet'];
							}
							if ( isset( $value['mobile'] ) ) {
								$style_array[ $blockId ]['mobile'] .= $value['mobile'];
							}
						} elseif ( isset( $block['wrapperCustomCss'] ) && is_string( $block['wrapperCustomCss'] ) && strlen( $block['wrapperCustomCss'] ) > 0 ) {
							$style_array[ $blockId ]['wrapperCustomCss'] .= $block['wrapperCustomCss'];
						}
					}
				}
			}
		}
		return $style_array;
	}

	/**
	 * write css in upload directory
	 */
	private static function write_block_styles( $block_styles, $post ) {

		// Write CSS for Page/Posts
		if ( ! empty( $css = self::build_css( $block_styles ) ) ) {
			$upload_dir = wp_upload_dir()['basedir'] . '/dp-style/';
			if ( ! file_exists( $upload_dir ) ) {
				mkdir( $upload_dir );
			}
			file_put_contents( $upload_dir . 'dp-style-' . abs( $post->ID ) . '.min.css', $css );
		}
	}

	/**
	 * Enqueue frontend css for post if exists
	 */
	public static function build_css( $style_object ) {
		$block_styles = $style_object;

		$css = '';
		foreach ( $block_styles as $block_style_key => $block_style ) {
			if ( ! empty( $block_css = (array) $block_style ) ) {
				$css .= sprintf(
					'/* %1$s Starts */',
					$block_style_key
				);
				foreach ( $block_css as $media => $style ) {
					switch ( $media ) {
						case 'desktop':
							$css .= preg_replace( '/\s+/', ' ', $style );
							break;
						case 'tablet':
							$css .= ' @media(max-width: 1024px){';
							$css .= preg_replace( '/\s+/', ' ', $style );
							$css .= '}';
							break;
						case 'mobile':
							$css .= ' @media(max-width: 767px){';
							$css .= preg_replace( '/\s+/', ' ', $style );
							$css .= '}';
							break;
					}
				}
				$css .= sprintf(
					'/* =%1$s= Ends */',
					$block_style_key
				);
			}
		}
		return trim( $css );
	}

}
