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
					'permission_callback' => '__return_true',
					'args'                => $this->get_collection_params(),
				],
				[
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_listings' ],
					'permission_callback' => [ $this, 'get_permission_check' ],
					'args'                => $this->listings_get_endpoint_args()
				],
				'schema' => [ $this, 'get_listings_schema' ],
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
		$number = $request->get_param( 'number' );
		$paged  = $request->get_param( 'paged' );

		$result['success'] = false;
		$listings          = [];
		$args              = [];
		if ( $number > 0 ) {
			$args['number'] = $number;
		}

		$prev = 1;
		$next = 2;
		if ( ( $number > 0 ) && ( $paged > 1 ) ) {
			$args['offset'] = ( $number * $paged ) - $number;
			$prev           = $paged;
			$next           = $paged + 1;
		}

		$listings       = directory_plugin_listing_get( $args );
		$total_listings = directory_plugin_listings_total_count();

		if ( $listings ) {
			$result['success']  = true;
			$result['total']    = $total_listings;
			$result['pages']    = ceil( $total_listings / count( $listings ) );
			$result['prev']     = $prev - 1;
			$result['next']     = $next;
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
		$id                = 0;
		$result['success'] = false;

		if ( empty( $request->get_param( 'title' ) ) ) {
			wp_send_json( $result );
		}

		$title          = $request->get_param( 'title' );
		$content        = $request->get_param( 'content' );
		$listing_status = $request->get_param( 'status' );
		$preview_image  = $request->get_param( 'image_id' );
		$author         = $request->get_param( 'author' );

		$id = directory_plugin_listing_insert(
			[
				'title'          => $title,
				'content'        => $content,
				'listing_status' => $listing_status,
				'preview_image'  => $preview_image,
				'author'         => $author,
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
		return current_user_can( 'edit_posts' );
	}


	/**
	 * Get the query params for collections of listings.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();

		$params['paged'] = [
			'description'       => __( 'Get result by page.', 'directory-plugin' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		];
		$params['number'] = [
			'description'       => __( 'Get number of results.', 'directory-plugin' ),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		];

		return $params;
	}

	/**
	 * Get the argument schema for listings endpoint.
	 * 
	 * @return array
	 */
	public function listings_get_endpoint_args() {
		$args = [];

		$args['title']    = [
			'description'       => esc_html__( 'This is the title arg for endpoint.', 'directory-plugin' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
			'sanitize_callback' => 'sanitize_text_field',
			'required'          => true,
		];
		$args['content']  = [
			'description'       => esc_html__( 'This is the content arg for endpoint.', 'directory-plugin' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
			'sanitize_callback' => 'sanitize_text_field',
		];
		$args['status']   = [
			'description'       => esc_html__( 'This is the status arg for endpoint.', 'directory-plugin' ),
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
			'sanitize_callback' => 'sanitize_text_field',
		];
		$args['author']   = [
			'description'       => esc_html__( 'This is the author arg for endpoint.', 'directory-plugin' ),
			'type'              => 'integer',
			'validate_callback' => 'rest_validate_request_arg',
			'sanitize_callback' => 'absint',
		];
		$args['image_id'] = [
			'description'       => esc_html__( 'This is the image_id arg for endpoint.', 'directory-plugin' ),
			'type'              => 'integer',
			'validate_callback' => 'rest_validate_request_arg',
			'sanitize_callback' => 'absint',
		];

		return $args;
	}

	/**
	 * Get schema for listings.
	 * 
	 * @return array
	 */
	public function get_listings_schema() {
		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'listings',
			'type'       => 'object',
			'properties' => [
				'id' => [
					'description' => esc_html__( 'The id of the listing record.', 'directory-plugin' ),
					'type'        => 'integer',
				],
				'title' => [
					'description' => esc_html__( 'Title of the listing object, if author was a user.', 'directory-plugin' ),
					'type'        => 'integer',
				],
				'content' => [
					'description' => esc_html__( 'Content of the object.', 'directory-plugin' ),
					'type'        => 'string',
				],
				'author' => [
					'description' => esc_html__( 'The id of the user object, if author was a user.', 'directory-plugin' ),
					'type'        => 'integer',
				],
				'preview_image' => [
					'description' => esc_html__( 'Attachemtn id of the object.', 'directory-plugin' ),
					'type'        => 'integer',
				],
				'listing_status' => [
					'description' => esc_html__( 'Listing status of the object.', 'directory-plugin' ),
					'type'        => 'string',
				],
			],
		];
		return $schema;
	}

}
