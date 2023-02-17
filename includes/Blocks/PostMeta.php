<?php
/**
 * Block Post Meta.
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\Blocks;

/**
 * Post Meta Class
 */
class PostMeta {

	public function __construct() {
		add_filter( 'init', [ $this, 'register_post_meta' ] );
	}

	/**
	 * Register Post Meta.
	 */
	public function register_post_meta() {
		register_meta(
			'post',
			'_dp_attr',
			[
				'show_in_rest'  => true,
				'single'        => true,
				'auth_callback' => [ $this, 'permission_check_callback' ],
			]
		);
	}

	/**
	 * Permission check
	 */
	public function permission_check_callback() {
		return current_user_can( 'edit_posts' );
	}
}
