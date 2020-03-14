<?php

/**
 * Handle a page request
 */
class PRT_Page_Controller {

	public static function page_endpoint_args() {
		return array(
  		'slug' => array(
        'validate_callback' => array( 'PRT_Validate', 'string' ),
      ),
		);
	}

	public static function pages_endpoint_args() {
		return array(
			'id' => array(
				'validate_callback' => array( 'PRT_Validate', 'string' ),
			),
  		'slug' => array(
        'validate_callback' => array( 'PRT_Validate', 'string' ),
      ),
			'numberposts' => array(
        'validate_callback' => array( 'PRT_Validate', 'string' ),
      ),
			's' => array(
        'validate_callback' => array( 'PRT_Validate', 'string' ),
      ),
		);
	}

	protected $request = null;

	function __construct( $request ) {
		$this->request = $request;
	}

	public function get_page_endpoint() {
		$_slug = $this->get_param( 'slug' );
		$_response = null;

		if ( ! empty( $_slug ) ) {
			$_page = $this->get_page_by_slug( $_slug );
			if ( $_page ) $_response = array( 'page' => $_page, );
		}

		return rest_ensure_response( array_merge( array(
			'success' => boolval( null !== $_response ),
		), (array) $_response ) );
	}

	public function get_pages_endpoint() {
		$_page = $this->get_page_by_args();
		if ( $_page ) $_response = array( 'pages' => $_page, );

		return rest_ensure_response( array_merge( array(
			'success' => boolval( null !== $_response ),
		), (array) $_response ) );
	}

	public function get_page_by_args() {
		// start with basic params
		$args = array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'filter'      => 'raw',
		);
		// build additional params
		$_approved_params = $this->get_approved_params();

		if ( isset( $_approved_params['id'] ) ) {
			$args['post__in'] = explode( ',', $_approved_params['id'] );
			unset( $_approved_params['id'] );
		}

		if ( isset( $_approved_params['slug'] ) ) {
			$args['name'] = $_approved_params['slug'];
			unset( $_approved_params['slug'] );
		}

		$combined_args = array_merge( (array) $args, (array) $_approved_params );

		$pages = get_posts( $combined_args );

		if( $pages ) {
			return array_map( array( get_called_class(), 'build_page' ), $pages );
		}

		return false;
	}

	public function get_page_by_slug( $slug ) {
		$args = array(
			'name'        => $slug,
			'post_type'   => 'page',
			'post_status' => 'publish',
			'numberposts' => 1,
			'filter'      => 'raw',
		);
		$pages = get_posts( $args );
		$the_page = $pages[0];

		if( $the_page ) {
			return $this->build_page( $the_page );
		}

		return false;
	}

	/*
		Protected Methods
	*/

	/**
	 * retrieves safe param value by key
	 * @param  string $key the key for the param to retreive
	 * @return mixed       the param value if found, or null
	 */
	protected function get_param( $key ) {
		$parms = $this->request->get_params();
		return (isset( $parms[ $key ] ))
			? strip_tags( $parms[ $key ] )
			: null;
	}

	/**
	 * Gets only approved params and returns them as an array
	 * @return array array of params, if the params submitted by the request exist in the list of approved params.
	 */
	protected function get_approved_params() {
		$_approved = array_keys( self::pages_endpoint_args() );
		$_params = [];
		foreach ( $_approved as $key ) {
			$_p = $this->get_param( $key );
			if ( $_p ) {
				$_params[ $key ] = $_p;
			}
		}

		return $_params;
	}

	protected function build_page( $page ) {
		$_id = $page->ID;

		// add page modules if they exists
		if ( $_modules = get_field( 'prt_page_modules', $_id ) ) {
			$page->page_modules = $_modules;
		}

		return $page;
	}
}

?>
