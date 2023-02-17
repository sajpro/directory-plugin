<?php
/**
 * Block Post Meta.
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP;

/**
 * Post Meta Class
 *
 */
class BlocksPostMeta {

	public function __construct() {
		add_filter( 'init', array( $this, 'register_post_meta' ) );
	}

	/**
	 * Register Post Meta.
	 */
	public function register_post_meta() {
		register_meta(
			'post',
			'_dp_attr',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'auth_callback' => array( $this, 'permission_check_callback' ),
			)
		);
	}

	/**
	 * Permission check
	 */
	public function permission_check_callback() {
		return current_user_can( 'edit_posts' );
	}
}
