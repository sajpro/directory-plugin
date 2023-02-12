<?php
/**
 * Api Endpoints.
 *
 * @package DirectoryPlugin
 */

namespace Sajib\DP\RestApi\v1;

/**
 * Api Endpoints class
 */
class Api_Endpoints extends \WP_REST_Controller {

	/**
	 * Api namespace
	 *
	 * @var string
	 */
	protected $namespace = 'directory/v1';
	protected $rest_base = 'listings';

	/**
	 * Init api
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Register Routes
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_all_listings' ],
					'permission_callback' => '__return_true', // [ $this, 'get_permission_check' ],
					'args'                => parent::get_endpoint_args_for_item_schema( true ),
				],
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_listings' ],
					'permission_callback' => '__return_true', // [ $this, 'get_permission_check' ],
					// 'args'                => parent::get_endpoint_args_for_item_schema( true ),
				],
			]
		);
	}

	/**
	 * Get All listings
	 *
	 * @return void
	 * @param array $request User request data.
	 */
	public function get_all_listings( $request ) {
		$result['success'] = false;
		$listings          = [];

		$listings = directory_plugin_listing_get();

		if ( $listings ) {
			$result['success']  = true;
			$result['listings'] = $listings;
		}

		wp_send_json( $result );
	}

	/**
	 * Create listings
	 *
	 * @return void
	 * @param array $request User request data.
	 */
	public function create_listings( $request ) {
		$result['success'] = false;
		$id                = 0;

		$title          = $request->get_param( 'title' );
		$content        = $request->get_param( 'content' );
		$listing_status = $request->get_param( 'listing_status' );
		$preview_image  = $request->get_param( 'preview_image' );
		$created_by     = $request->get_param( 'created_by' );

		$id = directory_plugin_listing_insert(
			[
				'title'          => $title,
				'content'        => $content,
				'listing_status' => $listing_status,
				'preview_image'  => $preview_image,
				'created_by'     => $created_by,
			]
		);

		if ( $id ) {
			$result['success'] = true;
			$result['data']    = $id;
		}

		wp_send_json( $result );
	}

	/**
	 * Permission check
	 *
	 * @return boolean
	 */
	public function get_permission_check() {
		return current_user_can( 'manage_options' );
	}

}
