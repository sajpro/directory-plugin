<?php
/**
 * Font Loader.
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP;

/**
 * Load google fonts.
 */

class BlocksFontLoader {


	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'fonts_loader' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'fonts_loader' ] );
	}

	/**
	 * Load fonts.
	 */
	public function fonts_loader() {
		global $post;

		if ( $post && isset( $post->ID ) ) {
			$fonts = (array) json_decode( get_post_meta( $post->ID, '_dp_attr', true ) );

			if ( ! empty( $fonts ) ) {
				$fonts = array_unique( $fonts );

				$system = [
					'Arial',
					'Tahoma',
					'Verdana',
					'Helvetica',
					'Times New Roman',
					'Trebuchet MS',
					'Georgia',
				];

				$gfonts = '';

				$gfonts_attr = ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';

				foreach ( $fonts as $font ) {
					if ( ! in_array( $font, $system, true ) && ! empty( $font ) ) {
						$gfonts .= str_replace( ' ', '+', trim( $font ) ) . $gfonts_attr . '|';
					}
				}

				if ( ! empty( $gfonts ) ) {
					$query_args = [
						'family' => $gfonts,
					];

					wp_register_style(
						'dp-blocks-google-fonts',
						add_query_arg( $query_args, '//fonts.googleapis.com/css' ),
						[]
					);

					wp_enqueue_style( 'dp-blocks-google-fonts' );
				}

				// Reset.
				$gfonts = '';
			}
		}
	}
}

